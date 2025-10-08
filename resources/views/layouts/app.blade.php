<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Inventory Management System') }}</title>
    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS (if not already included) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS for typography and spacing -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        body { background-color: #f8f9fa; }
        
        /* Ensure Font Awesome icons display properly */
        .fas, .far, .fab {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands" !important;
            font-weight: 900;
        }
        
        .far {
            font-weight: 400;
        }
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }
        .sidebar {
            background: #212529;
            color: #fff;
            transition: width 0.2s;
            width: 250px;
            z-index: 1030;
            border-right: 1px solid #343a40;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .sidebar.collapsed { width: 70px !important; }
        .sidebar .nav-link { color: #adb5bd; display: flex; align-items: center; gap: 0.75rem; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { color: #fff; background: #343a40; }
        .sidebar .sidebar-header { font-size: 1.5rem; font-weight: bold; padding: 1.5rem 1rem 1.5rem 1rem; white-space: nowrap; display: flex; align-items: center; justify-content: space-between; }
        .sidebar .sidebar-header svg { vertical-align: middle; margin-right: 0.5rem; }
        .sidebar .sidebar-header .app-name { display: inline; transition: opacity 0.2s; }
        .sidebar.collapsed .sidebar-header .app-name { display: none; }
        .sidebar .link-text { display: inline; transition: opacity 0.2s; }
        .sidebar.collapsed .link-text { display: none; }
        .sidebar .user-section { position: absolute; bottom: 1rem; width: 100%; }
        .sidebar .user-section .user-name { display: inline; }
        .sidebar.collapsed .user-section .user-name { display: none; }
        .sidebar .user-section .dropdown-toggle::after { display: none; }
        .sidebar .user-section .dropdown-menu { left: 70px !important; }
        .sidebar .sidebar-collapse-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            padding: 0.25rem 0.5rem;
            margin-left: 0.5rem;
            outline: none;
            box-shadow: none;
            transition: all 0.2s ease;
        }
        .sidebar .sidebar-collapse-btn:focus { outline: none; box-shadow: none; }
        .sidebar .sidebar-collapse-btn:hover {
            background: #343a40;
            border-radius: 4px;
        }
        .sidebar.collapsed .sidebar-collapse-btn {
            background: #343a40;
            border-radius: 4px;
            margin-left: 0;
            width: 100%;
            justify-content: center;
            position: absolute;
            top: 1rem;
            left: 0;
            z-index: 10;
        }
        .main-content {
            flex: 1 1 0%;
            padding: 2rem;
            background: #f8f9fa;
            min-width: 0;
            transition: margin-left 0.2s;
        }
        @media (max-width: 991.98px) {
            .layout-wrapper { flex-direction: column; }
            .sidebar { position: fixed; left: -250px; top: 0; width: 250px; height: 100vh; transition: left 0.3s, width 0.2s; }
            .sidebar.show { left: 0; }
            .sidebar.collapsed { width: 70px; left: -70px; }
            .main-content { margin-left: 0 !important; padding: 1rem; }
        }
        @media (min-width: 992px) {
            .layout-wrapper { flex-direction: row; }
            .sidebar { position: relative; left: 0; top: 0; height: 100vh; }
            .main-content { margin-left: 0 !important; }
        }
    </style>
    @yield('head')
</head>

<body style="font-family: 'Roboto', Arial, sans-serif;">
    <div id="app">
        <div class="layout-wrapper">
            <nav class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <a href="{{ route('dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="36" height="36" rx="8" fill="#1976d2"/>
                            <text x="50%" y="55%" text-anchor="middle" fill="#fff" font-size="18" font-family="Arial, sans-serif" dy=".3em">IM</text>
                        </svg>
                        <span class="app-name ms-2 d-none d-lg-inline">{{ env('APP_NAME', 'Inventory') }}</span>
                    </a>
                    <button class="sidebar-collapse-btn" id="sidebarCollapseBtn" type="button" aria-label="Collapse sidebar">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
                <ul class="nav nav-pills flex-column mb-auto mt-2">
                    @auth
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard"><i class="bi bi-speedometer2"></i> <span class="link-text">Dashboard</span></a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('item') ? 'active' : '' }}" href="{{ route('item') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Items"><i class="bi bi-box-seam"></i> <span class="link-text">Items</span></a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('supplier') ? 'active' : '' }}" href="{{ route('supplier') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Suppliers"><i class="bi bi-truck"></i> <span class="link-text">Suppliers</span></a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('purchase.*') ? 'active' : '' }}" href="{{ route('purchase.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Purchases"><i class="bi bi-cart4"></i> <span class="link-text">Purchases</span></a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('category') ? 'active' : '' }}" href="{{ route('category') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Category"><i class="bi bi-tags"></i> <span class="link-text">Category</span></a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('department*') ? 'active' : '' }}" href="{{ route('department') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Department Management"><i class="bi bi-building"></i> <span class="link-text">Department</span></a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('borrower') ? 'active' : '' }}" href="{{ route('borrower') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Borrower"><i class="bi bi-people"></i> <span class="link-text">Borrower</span></a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('depreciation.report') ? 'active' : '' }}" href="{{ route('depreciation.report') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Depreciation Report"><i class="bi bi-graph-up"></i> <span class="link-text">Depreciation Report</span></a></li>                        
                        @if(in_array(Auth::user()->role, ['super_admin', 'admin']))
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('floor.*') ? 'active' : '' }}" href="{{ route('floor.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Floor Management"><i class="bi bi-building"></i> <span class="link-text">Floor Management</span></a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('room.*') ? 'active' : '' }}" href="{{ route('room.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Room Management"><i class="bi bi-door-open"></i> <span class="link-text">Room Management</span></a></li>
                        @endif
                        @if(Auth::user()->role === 'super_admin')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('user-management.*') ? 'active' : '' }}" href="{{ route('user-management.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="User Management"><i class="bi bi-person-gear"></i> <span class="link-text">User Management</span></a></li>
                            <li class="nav-item">
                                <form id="clear-cache-form" action="{{ route('superadmin.clear-cache') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link text-start w-100" onclick="return confirm('Are you sure you want to clear the cache?')" data-bs-toggle="tooltip" data-bs-placement="right" title="Clear Cache">
                                        <i class="bi bi-arrow-clockwise"></i> <span class="link-text">Clear Cache</span>
                                    </button>
                                </form>
                            </li>
                        @endif
                    @endauth
                </ul>
                <div class="user-section mt-auto p-3">
                    @guest
                        <a class="nav-link text-white" href="{{ route('index') }}">Sign in</a>
                        <a class="nav-link text-white" href="{{ route('register') }}">Sign up</a>
                    @else
                        <div class="dropdown">
                            <a href="#" class="d-block text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i><span class="user-name">{{ Auth::user() ? Auth::user()->name : '' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Log out</a></li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </nav>
            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS (if not already included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Barcode Library -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        // Sidebar toggle for mobile and desktop
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
            // Mobile show/hide
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            // Desktop/mobile collapse
            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    
                    // Change button icon based on sidebar state
                    const icon = sidebarCollapseBtn.querySelector('i');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.className = 'bi bi-chevron-right';
                        sidebarCollapseBtn.setAttribute('aria-label', 'Expand sidebar');
                    } else {
                        icon.className = 'bi bi-list';
                        sidebarCollapseBtn.setAttribute('aria-label', 'Collapse sidebar');
                    }
                    
                    // No margin-left on .main-content, flexbox handles layout
                    // Enable tooltips when collapsed
                    if (sidebar.classList.contains('collapsed')) {
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('.sidebar .nav-link'));
                        tooltipTriggerList.forEach(function (el) {
                            new bootstrap.Tooltip(el);
                        });
                    } else {
                        var tooltipList = [].slice.call(document.querySelectorAll('.sidebar .nav-link'));
                        tooltipList.forEach(function (el) {
                            if (el._tooltip) el._tooltip.dispose();
                        });
                    }
                });
            }
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>

</html>
