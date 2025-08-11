<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
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
            'specifications' => 'nullable|array|max:6',
            'specifications.*' => 'required|string|max:255',
            'asset_type' => 'required|in:fixed,current',
            'value' => 'nullable|numeric|min:0',
            'invoice_number' => 'nullable|string|max:255',
            'purchased_by' => 'nullable|exists:users,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required_with:invoice_number|nullable|date',
            'received_by' => 'nullable|exists:users,id',
            'status' => 'required|in:available,in_use,maintenance,not_traceable,disposed',
            'remarks' => 'nullable|string',
            'floor_level' => 'required|string|max:255',
            'room_number' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'condition' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $fields = $request->except(['individual_count', '_token', 'image', 'invoice_number']);
        $fields['tracking_mode'] = $request->tracking_mode;

        // Calculate depreciation cost automatically if value and depreciation rate are provided
        if ($request->filled('value') && $request->filled('depreciation_rate')) {
            $fields['depreciation_cost'] = ($request->value * $request->depreciation_rate) / 100;
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'items/' . uniqid('item_') . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $filename);
            $imagePath = $filename;
        }
        $fields['image'] = $imagePath;

        // Create purchase record if invoice number is provided
        $purchase = null;
        if ($request->filled('invoice_number') && $request->filled('supplier_id') && $request->filled('purchase_date')) {
            $purchase = \App\Models\Purchase::create([
                'supplier_id' => $request->supplier_id,
                'invoice_number' => $request->invoice_number,
                'purchase_date' => $request->purchase_date,
                'total_value' => $request->value ?? 0,
            ]);
        }

        if ($request->tracking_mode === 'bulk') {
            $fields['quantity'] = $request->quantity;
            $fields['serial_number'] = Item::generateSerialNumber('BULK');
            $fields['asset_tag'] = Item::generateAssetTag('BULK');
            if ($purchase) {
                $fields['purchase_id'] = $purchase->id;
            }
            $item = Item::create($fields);
            
            // Create purchase item if purchase exists
            if ($purchase) {
                \App\Models\PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_name' => $request->name,
                    'quantity' => $request->quantity,
                    'unit_price' => $request->value ?? 0,
                    'item_type' => $request->asset_type,
                ]);
            }
        } else {
            $count = $request->individual_count ?? 1;
            for ($i = 0; $i < $count; $i++) {
                $fields['quantity'] = 1;
                $fields['serial_number'] = Item::generateSerialNumber(($i+1));
                $fields['asset_tag'] = Item::generateAssetTag(($i+1));
                $fields['image'] = $imagePath;
                if ($purchase) {
                    $fields['purchase_id'] = $purchase->id;
                }
                $item = Item::create($fields);
                
                // Create purchase item if purchase exists (only for first item to avoid duplicates)
                if ($purchase && $i === 0) {
                    \App\Models\PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'item_name' => $request->name,
                        'quantity' => $count,
                        'unit_price' => $request->value ?? 0,
                        'item_type' => $request->asset_type,
                    ]);
                }
            }
        }

        $message = 'Item(s) added successfully. Waiting for admin approval.';
        if ($purchase) {
            $message .= ' Purchase record created with invoice #' . $request->invoice_number;
        }
        
        return redirect()->route('item')
            ->with(['message' => $message, 'alert' => 'alert-success']);
    }

    public function destroy($id)
    {
        $item = Item::find($id);
        $item->delete();

        return redirect()->route('item')->with(['message' => 'Item deleted', 'alert' => 'alert-success']);
    }

    public function showEdit($id)
    {
        $item = Item::find($id);
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
            'specifications' => 'nullable|array|max:6',
            'specifications.*' => 'required|string|max:255',
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
            'condition' => 'nullable|string|max:255'
        ]);

        $item = Item::findOrFail($id);
        
        $updateData = $request->all();
        
        // Calculate depreciation cost automatically if value and depreciation rate are provided
        if ($request->filled('value') && $request->filled('depreciation_rate')) {
            $updateData['depreciation_cost'] = ($request->value * $request->depreciation_rate) / 100;
        }
        
        $item->update($updateData);

        return redirect()->route('item')
            ->with(['message' => 'Item updated successfully', 'alert' => 'alert-success']);
    }

    public function approve($id)
    {
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
        $item->load(['floor', 'room', 'assignedUser']);
        $movements = \App\Models\AssetMovement::where('item_id', $item->id)
            ->with(['user', 'fromRoom', 'toRoom', 'fromFloor', 'toFloor'])
            ->orderByDesc('created_at')
            ->get();
        return view('pages.item.history', compact('item', 'movements'));
    }
}
