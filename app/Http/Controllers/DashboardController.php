<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $role = auth()->user()->role;
            
            // Add debugging to see what's happening
            \Log::info('Dashboard redirect for user role: ' . $role);
            
            switch ($role) {
                case 'super_admin':
                    // Default landing page for super admin: Item list
                    return redirect()->route('item');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'asset_manager':
                    return redirect()->route('asset-manager.dashboard');
                case 'employee':
                    return redirect()->route('employee.dashboard');
                default:
                    // If role is not recognized, show a default dashboard
                    \Log::warning('Unknown user role: ' . $role);
                    return view('dashboard');
            }
        }
        
        // If not authenticated, redirect to login
        return redirect()->route('index');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function assetManagerDashboard()
    {
        return view('asset-manager.dashboard');
    }

    public function employeeDashboard()
    {
        return view('employee.dashboard');
    }
}
