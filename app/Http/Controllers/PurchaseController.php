<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Purchase::with(['supplier', 'department']);
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
            'invoice_number' => 'required|string|max:255',
            'invoice_image' => 'nullable|file|mimes:webp,jpeg,png,pdf|max:4096',
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

        // Handle invoice image upload
        $invoiceImagePath = null;
        if ($request->hasFile('invoice_image')) {
            $file = $request->file('invoice_image');
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
        $purchase = \App\Models\Purchase::with(['supplier', 'items', 'purchasedBy', 'receivedBy', 'department'])->findOrFail($id);
        return view('pages.purchase.show', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = \App\Models\Purchase::with(['supplier', 'items', 'purchasedBy', 'receivedBy', 'department'])->findOrFail($id);
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
            'invoice_number' => 'required|string|max:255',
            'invoice_image' => 'nullable|file|mimes:webp,jpeg,png,pdf|max:4096',
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

        // Handle invoice image upload
        $invoiceImagePath = $purchase->invoice_image;
        if ($request->hasFile('invoice_image')) {
            $file = $request->file('invoice_image');
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
        // Delete a purchase
    }
} 