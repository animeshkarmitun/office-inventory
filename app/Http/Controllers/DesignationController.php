<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin');
    }

    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:designations,name',
                'department_id' => 'nullable|exists:departments,id',
                'department_name' => 'nullable|string|max:255',
            ]);

            // Handle department creation or selection
            $departmentId = $request->department_id;
            
            // If department_name is provided and department_id is null, create new department
            if ($request->filled('department_name') && !$request->filled('department_id')) {
                $department = \App\Models\Department::create([
                    'name' => $request->department_name,
                    'location' => 'Office', // Default location
                ]);
                $departmentId = $department->id;
            }

            // Validate that we have a department_id
            if (!$departmentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Department is required',
                    'errors' => ['department_id' => ['Please select or create a department']]
                ], 422);
            }

            $designation = Designation::create([
                'name' => $request->name,
                'department_id' => $departmentId,
            ]);

            // Load the department relationship
            $designation->load('department');

            return response()->json([
                'success' => true,
                'message' => 'Designation created successfully',
                'designation' => [
                    'id' => $designation->id,
                    'name' => $designation->name,
                    'department_name' => $designation->department->name,
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
                'message' => 'An error occurred while creating the designation: ' . $e->getMessage()
            ], 500);
        }
    }
}