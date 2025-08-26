<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('incharge_name', 'like', "%$search%")
                  ->orWhere('contact_number', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('tax_number', 'like', "%$search%")
                  ->orWhere('payment_terms', 'like', "%$search%")
                  ->orWhere('notes', 'like', "%$search%")
                  ;
            });
        }

        $suppliers = $query->orderBy('name')->paginate(10)->appends($request->all());
        return view('pages.supplier.index', compact('suppliers'));
    }

    public function showAdd()
    {
        return view('pages.supplier.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'incharge_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        Supplier::create($request->all());

        return redirect()->route('supplier')->with(['message' => 'Supplier added', 'alert' => 'alert-success']);
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return redirect()->route('supplier')->with(['message' => 'Supplier not found', 'alert' => 'alert-danger']);
        }

        // Check if supplier has related purchases
        $purchaseCount = $supplier->purchases()->count();
        
        if ($purchaseCount > 0) {
            return redirect()->route('supplier')->with([
                'message' => "Cannot delete supplier '{$supplier->name}'. This supplier has {$purchaseCount} purchase record(s) associated with it. Please delete the purchase records first or contact an administrator.", 
                'alert' => 'alert-warning'
            ]);
        }

        $supplier->delete();

        return redirect()->route('supplier')->with(['message' => 'Supplier deleted successfully', 'alert' => 'alert-success']);
    }

    public function showEdit($id)
    {
        $supplier = Supplier::find($id);

        return view('pages.supplier.edit', compact('supplier'));
    }

    public function update($id, Request $request)
    {
        $supplier = Supplier::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'incharge_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $supplier->update($request->all());

        return redirect()->route('supplier')->with(['message' => 'Supplier updated', 'alert' => 'alert-success']);
    }

    public function purchases(Supplier $supplier)
    {
        $purchases = $supplier->purchases()->with(['items'])->orderByDesc('purchase_date')->get();
        return view('pages.supplier.purchases', compact('supplier', 'purchases'));
    }
}
