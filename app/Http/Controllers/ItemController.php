<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $items = $query->with(['assignedUser', 'approvedBy'])->paginate(10);

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
        return view('pages.item.add', compact('categories', 'suppliers', 'users'));
    }

    public function store(Request $request)
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
            'depreciation_cost' => 'nullable|numeric|min:0',
            'purchased_by' => 'nullable|exists:users,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
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

        $item = Item::create($request->all());

        return redirect()->route('item')
            ->with(['message' => 'Item added successfully. Waiting for admin approval.', 'alert' => 'alert-success']);
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

        return view('pages.item.edit', compact('item', 'categories', 'suppliers'));
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
            'depreciation_cost' => 'nullable|numeric|min:0',
            'purchased_by' => 'nullable|exists:users,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
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
        $item->update($request->all());

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
}
