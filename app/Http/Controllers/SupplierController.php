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

    public function storeAjax(Request $request)
    {
        try {
            // Debug: Log the incoming request
            \Log::info('AJAX Supplier Request:', $request->all());
            
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

            $supplier = Supplier::create($request->all());
            
            // Debug: Log successful creation
            \Log::info('Supplier created successfully:', ['id' => $supplier->id, 'name' => $supplier->name]);

            return response()->json([
                'success' => true,
                'message' => 'Supplier added successfully',
                'supplier' => [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'incharge_name' => $supplier->incharge_name,
                    'contact_number' => $supplier->contact_number,
                    'email' => $supplier->email,
                    'address' => $supplier->address,
                    'tax_number' => $supplier->tax_number,
                    'payment_terms' => $supplier->payment_terms,
                    'notes' => $supplier->notes
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the supplier: ' . $e->getMessage()
            ], 500);
        }
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
