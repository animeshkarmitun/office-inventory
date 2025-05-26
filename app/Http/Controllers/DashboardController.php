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
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'asset_manager') {
                return redirect()->route('asset-manager.dashboard');
            } else {
                return redirect()->route('employee.dashboard');
            }
        }
        return view('dashboard');
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
