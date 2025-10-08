<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin');
    }

    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('pages.user-management.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('pages.user-management.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,asset_manager,employee',
            'department_id' => 'required|exists:departments,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_admin' => $request->role === 'admin',
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('user-management.index')
            ->with(['message' => 'User created successfully', 'alert' => 'alert-success']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        return view('pages.user-management.edit', compact('user', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,asset_manager,employee',
            'password' => 'nullable|string|min:8|confirmed',
            'department_id' => 'required|exists:departments,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_admin' => $request->role === 'admin',
            'department_id' => $request->department_id,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('user-management.index')
            ->with(['message' => 'User updated successfully', 'alert' => 'alert-success']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent super admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->route('user-management.index')
                ->with(['message' => 'You cannot delete your own account', 'alert' => 'alert-danger']);
        }

        $user->delete();

        return redirect()->route('user-management.index')
            ->with(['message' => 'User deleted successfully', 'alert' => 'alert-success']);
    }

    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,asset_manager,employee',
                'designation_id' => 'required|exists:designations,id',
            ]);

            // Get the designation to determine the department
            $designation = \App\Models\Designation::findOrFail($request->designation_id);

            // Auto-generate email if not provided
            $email = $request->email;
            if (empty($email)) {
                $baseEmail = strtolower(str_replace(' ', '.', $request->name));
                $email = $baseEmail . '@company.com';
                
                // Ensure email is unique
                $counter = 1;
                $originalEmail = $email;
                while (User::where('email', $email)->exists()) {
                    $email = $baseEmail . $counter . '@company.com';
                    $counter++;
                }
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_admin' => $request->role === 'admin',
                'department_id' => $designation->department_id,
                'designation_id' => $request->designation_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User added successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
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
                'message' => 'An error occurred while adding the user: ' . $e->getMessage()
            ], 500);
        }
    }
}
