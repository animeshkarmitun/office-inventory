<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function index()
    {
        $companies = Company::orderBy('name')->paginate(10);
        return view('pages.company.index', compact('companies'));
    }

    public function showEdit($id)
    {
        $company = Company::findOrFail($id);
        return view('pages.company.edit', compact('company'));
    }

    public function update($id, Request $request)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $id,
        ]);

        $company->update([
            'name' => $request->name,
        ]);

        return redirect()->route('company')->with(['message' => 'Company updated successfully', 'alert' => 'alert-success']);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        // Check if company has items
        if ($company->items()->exists()) {
            return redirect()->route('company')->with(['message' => 'Cannot delete company because it has associated items.', 'alert' => 'alert-danger']);
        }

        $company->delete();

        return redirect()->route('company')->with(['message' => 'Company deleted successfully', 'alert' => 'alert-success']);
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
        ]);

        try {
            $company = Company::create([
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'company' => $company,
                'message' => 'Company created successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create company: ' . $e->getMessage(),
            ], 500);
        }
    }
}
