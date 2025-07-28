<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        return view('pages.user-management.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,asset_manager,employee',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_admin' => $request->role === 'admin',
        ]);

        return redirect()->route('user-management.index')
            ->with(['message' => 'User created successfully', 'alert' => 'alert-success']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user-management.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,asset_manager,employee',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_admin' => $request->role === 'admin',
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
}
