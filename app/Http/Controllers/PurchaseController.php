<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseImage;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Purchase::with(['supplier', 'department', 'images']);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('supplier', function($sq) use ($search) {
                    $sq->where('name', 'like', "%$search%");
                })
                ->orWhereHas('department', function($dq) use ($search) {
                    $dq->where('name', 'like', "%$search%");
                })
                ->orWhere('invoice_number', 'like', "%$search%")
                ->orWhereDate('purchase_date', $search);
            });
        }
        $purchases = $query->orderBy('purchase_date', 'desc')->get();
        return view('pages.purchase.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = \App\Models\Supplier::all();
        $users = \App\Models\User::all();
        $departments = \App\Models\Department::all();
        return view('pages.purchase.add', compact('suppliers', 'users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string|max:255',
            'invoice_images' => 'nullable|array|max:10',
            'invoice_images.*' => 'file|mimes:webp,jpeg,png,pdf|max:4096',
            'purchase_date' => 'required|date',
            'purchased_by' => 'required|exists:users,id',
            'received_by' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.item_type' => 'nullable|string|max:255',
        ]);

        // Handle multiple invoice image uploads
        $invoiceImagePath = null;
        if ($request->hasFile('invoice_images') && count($request->file('invoice_images')) > 0) {
            // For backward compatibility, use the first image as the main invoice image
            $file = $request->file('invoice_images')[0];
            $filename = 'invoices/' . uniqid('invoice_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $filename);
            $invoiceImagePath = $filename;
        }

        // Calculate total value
        $totalValue = collect($request->items)->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        // Create purchase
        $purchase = \App\Models\Purchase::create([
            'supplier_id' => $request->supplier_id,
            'invoice_number' => $request->invoice_number,
            'invoice_image' => $invoiceImagePath,
            'purchase_date' => $request->purchase_date,
            'total_value' => $totalValue,
            'purchased_by' => $request->purchased_by,
            'received_by' => $request->received_by,
            'department_id' => $request->department_id,
            'purchase_number' => \App\Models\Purchase::generatePurchaseNumber(),
        ]);

        // Handle multiple invoice image uploads
        if ($request->hasFile('invoice_images')) {
            foreach ($request->file('invoice_images') as $file) {
                $filename = 'purchase_images/' . uniqid('purchase_') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public', $filename);
                
                PurchaseImage::create([
                    'purchase_id' => $purchase->id,
                    'image_path' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Create purchase items and inventory items
        foreach ($request->items as $itemData) {
            $purchaseItem = $purchase->items()->create($itemData);
            // Auto-create inventory items
            for ($i = 0; $i < $itemData['quantity']; $i++) {
                \App\Models\Item::create([
                    'name' => $itemData['item_name'],
                    'asset_type' => in_array(($itemData['item_type'] ?? ''), ['fixed','current']) ? $itemData['item_type'] : 'fixed',
                    'status' => 'available',
                    'value' => $itemData['unit_price'],
                    'purchase_date' => $request->purchase_date,
                    'supplier_id' => $request->supplier_id,
                    'purchased_by' => $request->purchased_by,
                    'received_by' => $request->received_by,
                    'tracking_mode' => 'individual',
                    'quantity' => 1,
                    'serial_number' => 'AUTO-' . strtoupper(uniqid()) . '-' . ($i+1),
                    'asset_tag' => 'AUTO-' . strtoupper(uniqid()) . '-' . ($i+1),
                    'purchase_id' => $purchase->id,
                ]);
            }
        }

        return redirect()->route('purchase.index')->with([
            'message' => "Purchase {$purchase->purchase_number} created successfully!",
            'alert' => 'alert-success',
            'details' => [
                'items_count' => collect($request->items)->sum('quantity'),
                'total_value' => number_format($totalValue, 2),
                'department' => \App\Models\Department::find($request->department_id)->name ?? 'Unknown',
                'purchase_number' => $purchase->purchase_number
            ]
        ]);
    }

    public function show($id)
    {
        $purchase = \App\Models\Purchase::with(['supplier', 'items', 'purchasedBy', 'receivedBy', 'department', 'images'])->findOrFail($id);
        return view('pages.purchase.show', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = \App\Models\Purchase::with(['supplier', 'items', 'purchasedBy', 'receivedBy', 'department', 'images'])->findOrFail($id);
        $suppliers = \App\Models\Supplier::all();
        $users = \App\Models\User::all();
        $departments = \App\Models\Department::all();
        return view('pages.purchase.edit', compact('purchase', 'suppliers', 'users', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $purchase = \App\Models\Purchase::with('items')->findOrFail($id);
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string|max:255',
            'invoice_images' => 'nullable|array|max:10',
            'invoice_images.*' => 'file|mimes:webp,jpeg,png,pdf|max:4096',
            'purchase_date' => 'required|date',
            'purchased_by' => 'required|exists:users,id',
            'received_by' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.item_type' => 'nullable|string|max:255',
        ]);

        // Handle multiple invoice image uploads
        $invoiceImagePath = $purchase->invoice_image;
        if ($request->hasFile('invoice_images') && count($request->file('invoice_images')) > 0) {
            // For backward compatibility, use the first image as the main invoice image
            $file = $request->file('invoice_images')[0];
            $filename = 'invoices/' . uniqid('invoice_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $filename);
            $invoiceImagePath = $filename;
        }

        // Calculate total value
        $totalValue = collect($request->items)->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        // Update purchase
        $purchase->update([
            'supplier_id' => $request->supplier_id,
            'invoice_number' => $request->invoice_number,
            'invoice_image' => $invoiceImagePath,
            'purchase_date' => $request->purchase_date,
            'total_value' => $totalValue,
            'purchased_by' => $request->purchased_by,
            'received_by' => $request->received_by,
            'department_id' => $request->department_id,
        ]);

        // Handle multiple invoice image uploads (add new images)
        if ($request->hasFile('invoice_images')) {
            foreach ($request->file('invoice_images') as $file) {
                $filename = 'purchase_images/' . uniqid('purchase_') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public', $filename);
                
                PurchaseImage::create([
                    'purchase_id' => $purchase->id,
                    'image_path' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Delete old purchase items and inventory items (optional: only if you want to fully replace)
        $purchase->items()->delete();
        // Optionally, delete inventory items linked to this purchase (if you have such a link)

        // Create new purchase items and inventory items
        foreach ($request->items as $itemData) {
            $purchaseItem = $purchase->items()->create($itemData);
            // Auto-create inventory items
            for ($i = 0; $i < $itemData['quantity']; $i++) {
                \App\Models\Item::create([
                    'name' => $itemData['item_name'],
                    'asset_type' => in_array(($itemData['item_type'] ?? ''), ['fixed','current']) ? $itemData['item_type'] : 'fixed',
                    'status' => 'available',
                    'value' => $itemData['unit_price'],
                    'purchase_date' => $request->purchase_date,
                    'supplier_id' => $request->supplier_id,
                    'purchased_by' => $request->purchased_by,
                    'received_by' => $request->received_by,
                    'tracking_mode' => 'individual',
                    'quantity' => 1,
                    'serial_number' => 'AUTO-' . strtoupper(uniqid()) . '-' . ($i+1),
                    'asset_tag' => 'AUTO-' . strtoupper(uniqid()) . '-' . ($i+1),
                    'purchase_id' => $purchase->id,
                ]);
            }
        }

        return redirect()->route('purchase.show', $purchase->id)->with(['message' => 'Purchase updated successfully!', 'alert' => 'alert-success']);
    }

    public function destroy($id)
    {
        $purchase = \App\Models\Purchase::find($id);

        if (!$purchase) {
            return redirect()->route('purchase.index')->with(['message' => 'Purchase not found', 'alert' => 'alert-danger']);
        }

        // Check if purchase has related items
        $itemCount = \App\Models\Item::where('purchase_id', $purchase->id)->count();
        
        if ($itemCount > 0) {
            return redirect()->route('purchase.index')->with([
                'message' => "Cannot delete purchase '{$purchase->purchase_number}'. This purchase has {$itemCount} inventory item(s) associated with it. Please delete the inventory items first or contact an administrator.", 
                'alert' => 'alert-warning'
            ]);
        }

        // Delete purchase items first
        $purchase->items()->delete();
        
        // Delete purchase images
        $purchase->images()->delete();
        
        // Delete the purchase
        $purchase->delete();

        return redirect()->route('purchase.index')->with(['message' => 'Purchase deleted successfully', 'alert' => 'alert-success']);
    }

    public function deleteImage($id, $imageId)
    {
        $purchase = \App\Models\Purchase::findOrFail($id);
        $image = $purchase->images()->findOrFail($imageId);
        
        // Delete file from storage
        if (\Storage::disk('public')->exists($image->image_path)) {
            \Storage::disk('public')->delete($image->image_path);
        }
        
        // Delete from database
        $image->delete();
        
        return redirect()->back()->with(['message' => 'Image deleted successfully', 'alert' => 'alert-success']);
    }
}