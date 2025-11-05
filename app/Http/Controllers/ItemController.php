<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\AssetMovement;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('serial_number', 'like', "%$search%")
                  ->orWhere('asset_tag', 'like', "%$search%")
                  ->orWhere('barcode', 'like', "%$search%")
                  ->orWhere('rfid_tag', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%")
                  ->orWhere('condition', 'like', "%$search%")
                  ->orWhere('asset_type', 'like', "%$search%")
                  ->orWhere('value', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%")
                  ->orWhere('remarks', 'like', "%$search%")
                  ->orWhere('floor_level', 'like', "%$search%")
                  ->orWhere('room_number', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('assigned_to', 'like', "%$search%")
                  ->orWhere('purchased_by', 'like', "%$search%")
                  ->orWhere('supplier_id', 'like', "%$search%")
                  ->orWhere('purchase_date', 'like', "%$search%")
                  ->orWhere('received_by', 'like', "%$search%")
                  ;
            });
        }

        $items = $query->with(['assignedUser', 'approvedBy'])->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            $html = view('pages.item.partials.table', compact('items'))->render();
            return response()->json(['html' => $html]);
        }
        return view('pages.item.index', compact('items'));
    }

    public function showAdd()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $users = \App\Models\User::all();
        $floors = \App\Models\Floor::all();
        $rooms = \App\Models\Room::all();
        
        // Get the default supplier ID
        $defaultSupplier = Supplier::where('name', 'Default Supplier')->first();
        
        return view('pages.item.add', compact('categories', 'suppliers', 'users', 'defaultSupplier', 'floors', 'rooms'));
    }

    public function assignFromPurchase($purchaseId, $itemId)
    {
        // Get the purchase and purchase item
        $purchase = \App\Models\Purchase::with(['supplier', 'department', 'purchasedBy', 'receivedBy'])->findOrFail($purchaseId);
        $purchaseItem = \App\Models\PurchaseItem::findOrFail($itemId);
        
        // Get required data for the form
        $categories = Category::all();
        $suppliers = Supplier::all();
        $users = \App\Models\User::all();
        $floors = \App\Models\Floor::all();
        $rooms = \App\Models\Room::all();
        
        // Pre-populate data from purchase
        $prefilledData = [
            'name' => $purchaseItem->item_name,
            'description' => $purchaseItem->item_type,
            'specifications' => $purchaseItem->item_type,
            'value' => $purchaseItem->unit_price,
            'quantity' => $purchaseItem->quantity,
            'supplier_id' => $purchase->supplier_id,
            'purchase_date' => $purchase->purchase_date ? $purchase->purchase_date->format('Y-m-d') : '',
            'purchased_by' => $purchase->purchased_by,
            'received_by' => $purchase->received_by,
            'department_id' => $purchase->department_id,
            'purchase_id' => $purchase->id,
            'purchase_item_id' => $purchaseItem->id,
        ];
        
        return view('pages.item.add', compact('categories', 'suppliers', 'users', 'floors', 'rooms', 'prefilledData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tracking_mode' => 'required|in:bulk,individual',
            'quantity' => 'required_if:tracking_mode,bulk|nullable|integer|min:1',
            'individual_count' => 'required_if:tracking_mode,individual|nullable|integer|min:1',
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'asset_tag' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'rfid_tag' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string|max:1000',
            'asset_type' => 'required|in:fixed,current',
            'value' => 'nullable|numeric|min:0',
            'purchased_by' => 'nullable|exists:users,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'nullable|date',
            'received_by' => 'nullable|exists:users,id',
            'status' => 'required|in:available,in_use,maintenance,not_traceable,disposed',
            'remarks' => 'nullable|string',
            'floor_level' => 'required|string|max:255',
            'room_number' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'condition' => 'nullable|string|max:255',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'purchase_id' => 'nullable|exists:purchases,id',
            'purchase_item_id' => 'nullable|exists:purchase_items,id',
        ]);

        $fields = $request->except(['individual_count', '_token', 'images', 'purchase_item_id']);
        $fields['tracking_mode'] = $request->tracking_mode;
        
        // Add purchase_id if provided
        if ($request->filled('purchase_id')) {
            $fields['purchase_id'] = $request->purchase_id;
        }

        // Auto-approve items created by super admin
        if (Auth::user()->role === 'super_admin') {
            $fields['is_approved'] = true;
            $fields['approved_by'] = Auth::id();
            $fields['approved_at'] = now();
        }

        // Calculate depreciation cost automatically if value and depreciation rate are provided
        if ($request->filled('value') && $request->filled('depreciation_rate')) {
            $fields['depreciation_cost'] = ($request->value * $request->depreciation_rate) / 100;
        }

        // Handle multiple image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $result = $this->imageService->processAndStore($image);
                
                if ($result['success']) {
                    $imagePaths[] = $result['processed_path'];
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['images.' . $index => 'Image upload failed: ' . $result['error']]);
                }
            }
        }


        $itemsCreated = 0;
        $totalQuantity = 0;

        try {
            if ($request->tracking_mode === 'bulk') {
                // Create one item with bulk quantity
                $fields['quantity'] = $request->quantity;
                $fields['serial_number'] = Item::generateSerialNumber('BULK');
                $fields['asset_tag'] = Item::generateAssetTag('BULK');
                $fields['barcode'] = $fields['asset_tag']; // Auto-generate barcode from asset tag
                $item = Item::create($fields);
                $itemsCreated = 1;
                $totalQuantity = $request->quantity;
                
                // Save images for this item
                foreach ($imagePaths as $sortOrder => $imagePath) {
                    \App\Models\ItemImage::create([
                        'item_id' => $item->id,
                        'image_path' => $imagePath,
                        'original_name' => $request->file('images')[$sortOrder]->getClientOriginalName(),
                        'file_type' => $request->file('images')[$sortOrder]->getClientMimeType(),
                        'file_size' => $request->file('images')[$sortOrder]->getSize(),
                        'sort_order' => $sortOrder,
                    ]);
                }
                
            } else {
                // Create individual items
                $count = $request->individual_count ?? 1;
                $itemsCreated = $count;
                $totalQuantity = $count;
                
                for ($i = 0; $i < $count; $i++) {
                    $fields['quantity'] = 1;
                    $fields['serial_number'] = Item::generateSerialNumber(($i+1));
                    $fields['asset_tag'] = Item::generateAssetTag(($i+1));
                    $fields['barcode'] = $fields['asset_tag']; // Auto-generate barcode from asset tag
                    $item = Item::create($fields);
                    
                    // Save images for this item
                    foreach ($imagePaths as $sortOrder => $imagePath) {
                        \App\Models\ItemImage::create([
                            'item_id' => $item->id,
                            'image_path' => $imagePath,
                            'original_name' => $request->file('images')[$sortOrder]->getClientOriginalName(),
                            'file_type' => $request->file('images')[$sortOrder]->getClientMimeType(),
                            'file_size' => $request->file('images')[$sortOrder]->getSize(),
                            'sort_order' => $sortOrder,
                        ]);
                    }
                    
                }
            }
        } catch (\Exception $e) {
            \Log::error('Item creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create item(s). Please try again.']);
        }

        // Generate appropriate success message
        $purchaseInfo = '';
        if ($request->filled('purchase_id')) {
            $purchase = \App\Models\Purchase::find($request->purchase_id);
            $purchaseInfo = " from purchase {$purchase->purchase_number}";
        }
        
        if ($request->tracking_mode === 'bulk') {
            if (Auth::user()->role === 'super_admin') {
                $message = "Bulk item '{$request->name}' created and approved successfully with quantity {$totalQuantity}{$purchaseInfo}.";
            } else {
                $message = "Bulk item '{$request->name}' created successfully with quantity {$totalQuantity}{$purchaseInfo}. Waiting for admin approval.";
            }
        } else {
            if (Auth::user()->role === 'super_admin') {
                $message = "{$itemsCreated} individual item(s) of '{$request->name}' created and approved successfully{$purchaseInfo}.";
            } else {
                $message = "{$itemsCreated} individual item(s) of '{$request->name}' created successfully{$purchaseInfo}. Waiting for admin approval.";
            }
        }
        
        
        return redirect()->route('item')
            ->with(['message' => $message, 'alert' => 'alert-success']);
    }

    public function destroy($id)
    {
        $item = Item::find($id);
        
        // Delete associated images if they exist
        if ($item->image) {
            $this->imageService->deleteImage($item->image);
        }
        
        $item->delete();

        return redirect()->route('item')->with(['message' => 'Item deleted', 'alert' => 'alert-success']);
    }

    public function showEdit($id)
    {
        $item = Item::with(['images', 'assignedUser', 'supplier', 'floor', 'room'])->find($id);
        $categories = Category::all();
        $suppliers = Supplier::all();
        $users = \App\Models\User::all();
        $floors = \App\Models\Floor::all();
        $rooms = \App\Models\Room::all();
        
        // Get the default supplier ID for fallback
        $defaultSupplier = Supplier::where('name', 'Default Supplier')->first();
        
        return view('pages.item.edit', compact('item', 'categories', 'suppliers', 'users', 'defaultSupplier', 'floors', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'asset_tag' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'rfid_tag' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string|max:1000',
            'asset_type' => 'required|in:fixed,current',
            'value' => 'nullable|numeric|min:0',
            'purchased_by' => 'nullable|exists:users,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'nullable|date',
            'received_by' => 'nullable|exists:users,id',
            'status' => 'required|in:available,in_use,maintenance,not_traceable,disposed',
            'remarks' => 'nullable|string',
            'floor_level' => 'required|string|max:255',
            'room_number' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'condition' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        $item = Item::findOrFail($id);
        
        $updateData = $request->except(['image', 'images']);
        
        // Calculate depreciation cost automatically if value and depreciation rate are provided
        if ($request->filled('value') && $request->filled('depreciation_rate')) {
            $updateData['depreciation_cost'] = ($request->value * $request->depreciation_rate) / 100;
        }

        // Handle single image update using ImageService (legacy)
        if ($request->hasFile('image')) {
            $result = $this->imageService->updateImage(
                $request->file('image'), 
                $item->image ?? ''
            );
            
            if ($result['success']) {
                $updateData['image'] = $result['processed_path'];
            } else {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image' => 'Image update failed: ' . $result['error']]);
            }
        }

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            \Log::info('Multiple images upload detected', [
                'count' => count($request->file('images')),
                'item_id' => $item->id
            ]);
            
            foreach ($request->file('images') as $index => $image) {
                \Log::info('Processing image', [
                    'index' => $index,
                    'filename' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'mime' => $image->getMimeType()
                ]);
                
                $result = $this->imageService->processAndStore($image);
                
                \Log::info('Image processing result', [
                    'success' => $result['success'],
                    'error' => $result['error'] ?? null,
                    'processed_path' => $result['processed_path'] ?? null
                ]);
                
                if ($result['success']) {
                    // Create ItemImage record
                    $itemImage = \App\Models\ItemImage::create([
                        'item_id' => $item->id,
                        'image_path' => $result['processed_path'],
                        'original_name' => $image->getClientOriginalName(),
                        'file_type' => $image->getMimeType(),
                        'file_size' => $image->getSize(),
                        'sort_order' => $item->images()->max('sort_order') + 1,
                    ]);
                    
                    \Log::info('ItemImage created', [
                        'id' => $itemImage->id,
                        'image_path' => $itemImage->image_path
                    ]);
                } else {
                    \Log::error('Image upload failed', [
                        'index' => $index,
                        'error' => $result['error']
                    ]);
                    
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['images.' . $index => 'Image upload failed: ' . $result['error']]);
                }
            }
        }
        
        $item->update($updateData);

        return redirect()->route('item')
            ->with(['message' => 'Item updated successfully', 'alert' => 'alert-success']);
    }

    public function approve($id)
    {
        // Check if user has permission to approve items
        if (!Auth::user()->is_admin && Auth::user()->role !== 'super_admin') {
            return redirect()->route('item')
                ->with(['message' => 'You do not have permission to approve items', 'alert' => 'alert-danger']);
        }

        $item = Item::findOrFail($id);
        
        if (!$item->is_approved) {
            $item->update([
                'is_approved' => true,
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            return redirect()->route('item')
                ->with(['message' => 'Item approved successfully', 'alert' => 'alert-success']);
        }

        return redirect()->route('item')
            ->with(['message' => 'Item is already approved', 'alert' => 'alert-warning']);
    }

    public function depreciationReport()
    {
        $items = Item::with(['assignedUser', 'approvedBy'])->get();
        return view('pages.item.depreciation-report', compact('items'));
    }

    public function export()
    {
        $filename = 'items_' . date('Y-m-d_H-i-s') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ItemsExport, $filename);
    }

    public function showImport()
    {
        return view('pages.item.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $import = new \App\Imports\ItemsImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            $importedCount = $import->getImportedCount();
            $updatedCount = $import->getUpdatedCount();
            $errors = $import->getErrors();

            $message = "Import completed successfully! ";
            if ($importedCount > 0) {
                $message .= "{$importedCount} new item(s) imported. ";
            }
            if ($updatedCount > 0) {
                $message .= "{$updatedCount} item(s) updated. ";
            }
            if (!empty($errors)) {
                $message .= "Errors: " . implode(', ', $errors);
            }

            return redirect()->route('item')
                ->with(['message' => $message, 'alert' => empty($errors) ? 'alert-success' : 'alert-warning']);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['message' => 'Import failed: ' . $e->getMessage(), 'alert' => 'alert-danger']);
        }
    }

    public function downloadTemplate()
    {
        $filename = 'items_template_' . date('Y-m-d') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ItemsTemplateExport, $filename);
    }

    public function history(Item $item)
    {
        $item->load([
            'images', 
            'floor', 
            'room', 
            'assignedUser', 
            'category', 
            'supplier', 
            'purchasedBy', 
            'receivedBy', 
            'approvedBy',
            'purchase'
        ]);
        $movements = \App\Models\AssetMovement::where('item_id', $item->id)
            ->with(['fromUser', 'toUser', 'movedBy'])
            ->orderByDesc('created_at')
            ->get();
        return view('pages.item.history', compact('item', 'movements'));
    }

    public function exportHistoryPdf(Item $item)
    {
        $item->load([
            'images', 
            'floor', 
            'room', 
            'assignedUser', 
            'category', 
            'supplier', 
            'purchasedBy', 
            'receivedBy', 
            'approvedBy',
            'purchase'
        ]);
        $movements = \App\Models\AssetMovement::where('item_id', $item->id)
            ->with(['fromUser', 'toUser', 'movedBy'])
            ->orderByDesc('created_at')
            ->get();

        $pdf = \PDF::loadView('pages.item.history-pdf', compact('item', 'movements'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'fontDir' => storage_path('fonts/'),
            'fontCache' => storage_path('fonts/'),
            'isUnicode' => true,
            'debugKeepTemp' => true,
        ]);
        
        $filename = 'item_history_' . $item->name . '_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function removeImage($id)
    {
        try {
            $itemImage = \App\Models\ItemImage::findOrFail($id);
            
            // Delete the image files using ImageService
            if ($itemImage->image_path) {
                $this->imageService->deleteImage($itemImage->image_path);
            }
            
            // Delete the database record
            $itemImage->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Image removed successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to remove image: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeLegacyImage($id)
    {
        try {
            $item = Item::findOrFail($id);
            
            // Delete the legacy image file using ImageService
            if ($item->image) {
                $this->imageService->deleteImage($item->image);
                
                // Clear the image field in the database
                $item->update(['image' => null]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Legacy image removed successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to remove legacy image: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove legacy image: ' . $e->getMessage()
            ], 500);
        }
    }
}
