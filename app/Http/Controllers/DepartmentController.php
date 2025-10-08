<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,admin');
    }

    public function index()
    {
        $departments = Department::with('designations')->orderBy('location', 'ASC')->get();
        return view('pages.department.index', compact('departments'));
    }

    public function showAdd()
    {
        return view('pages.department.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()->route('department')->with(['message' => 'Department added', 'alert' => 'alert-success']);
    }

    public function destroy($id)
    {
        $department = Department::find($id)->delete();

        return redirect()->route('department')->with(['message' => 'Department deleted', 'alert' => 'alert-danger']);
    }

    public function showEdit($id)
    {
        $department = Department::find($id);

        return view('pages.department.edit', compact('department'));
    }

    public function update($id, Request $request)
    {
        $department = Department::find($id);

        $request->validate([
            'name' => 'required',
        ]);

        $department->name = $request->name;
        $department->save();

        return redirect()->route('department')->with(['message' => 'Department updated', 'alert' => 'alert-success']);
    }

    // Designation Management Methods
    public function designations(Request $request)
    {
        $query = Designation::with('department');
        
        // Filter by department if specified
        if ($request->has('department') && $request->department) {
            $query->where('department_id', $request->department);
        }
        
        $designations = $query->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        
        return view('pages.department.designations', compact('designations', 'departments'));
    }

    public function createDesignation()
    {
        $departments = Department::orderBy('name')->get();
        return view('pages.department.create-designation', compact('departments'));
    }

    public function storeDesignation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        Designation::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('department.designations')
            ->with(['message' => 'Designation created successfully', 'alert' => 'alert-success']);
    }

    public function showDesignation(Designation $designation)
    {
        $designation->load('department');
        return view('pages.department.show-designation', compact('designation'));
    }

    public function editDesignation(Designation $designation)
    {
        $departments = Department::orderBy('name')->get();
        return view('pages.department.edit-designation', compact('designation', 'departments'));
    }

    public function updateDesignation(Request $request, Designation $designation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $designation->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('department.designations')
            ->with(['message' => 'Designation updated successfully', 'alert' => 'alert-success']);
    }

    public function destroyDesignation(Designation $designation)
    {
        $designation->delete();

        return redirect()->route('department.designations')
            ->with(['message' => 'Designation deleted successfully', 'alert' => 'alert-danger']);
    }
}
