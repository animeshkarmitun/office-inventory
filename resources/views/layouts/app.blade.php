<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['app_title'] ?? config('app.name', 'Inventory Management System') }}</title>
    @if(isset($settings['app_favicon']))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($settings['app_favicon']) }}">
    @endif
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
        :root {
            --sidebar-bg: {{ $settings['sidebar_bg_color'] ?? '#212529' }};
            --sidebar-text: {{ $settings['sidebar_text_color'] ?? '#ffffff' }};
            --sidebar-font-size: {{ $settings['sidebar_text_size'] ?? '1rem' }};
            --table-header-bg: {{ $settings['table_header_bg'] ?? '#f8f9fa' }};
            --table-font-size: {{ $settings['table_text_size'] ?? '0.875rem' }};
            --btn-primary-bg: {{ $settings['btn_primary_bg'] ?? '#0d6efd' }};
            --btn-primary-color: {{ $settings['btn_primary_color'] ?? '#ffffff' }};
            --header-text-color: {{ $settings['header_text_color'] ?? '#212529' }};
            --header-font-size: {{ $settings['header_text_size'] ?? '1.75rem' }};
            --table-header-text-color: {{ $settings['table_header_text_color'] ?? '#495057' }};
            --app-bg-color: {{ $settings['app_bg_color'] ?? '#f8f9fa' }};
            --link-color: {{ $settings['link_color'] ?? '#5e72e4' }};
            --body-text-color: {{ $settings['body_text_color'] ?? '#525f7f' }};
            --link-hover-color: {{ $settings['link_hover_color'] ?? '#324cdd' }};
        }

        body { 
            background-color: var(--app-bg-color); 
            color: var(--body-text-color);
        }
        
        /* Link Styles */
        a { 
            text-decoration: none !important; 
            color: var(--link-color);
            transition: all 0.2s ease;
        }
        a:hover { 
            text-decoration: none !important; 
            color: var(--link-hover-color) !important;
        }
        
        /* Dynamic Styles */
        .sidebar {
            background: var(--sidebar-bg) !important;
            color: var(--sidebar-text) !important;
            border-right: 1px solid rgba(255,255,255,0.1) !important;
        }
        .sidebar .nav-link {
            color: var(--sidebar-text) !important;
            opacity: 0.8;
            font-size: var(--sidebar-font-size) !important;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            opacity: 1;
            background: rgba(255,255,255,0.1) !important;
            color: #fff !important;
        }
        .sidebar .sidebar-header .app-name {
            color: var(--sidebar-text) !important;
        }
        
        .table thead th {
            background-color: var(--table-header-bg) !important;
        }
        .table {
            font-size: var(--table-font-size) !important;
        }
        
        .btn-primary {
            background-color: var(--btn-primary-bg) !important;
            border-color: var(--btn-primary-bg) !important;
            color: var(--btn-primary-color) !important;
        }
        .btn-primary:hover {
            opacity: 0.9;
            background-color: var(--btn-primary-bg) !important;
            border-color: var(--btn-primary-bg) !important;
        }
        
        h2, .h2 {
            color: var(--header-text-color) !important;
            font-size: var(--header-font-size) !important;
        }

        /* Premium Table Styles */
        .card-table {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            background: #fff;
        }
        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }
        .table thead th {
            background-color: var(--table-header-bg) !important;
            color: var(--table-header-text-color) !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #edf2f9;
        }
        .table tbody td {
            padding: 1.25rem 1rem;
            color: #5e6e82;
            border-bottom: 1px solid #edf2f9;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.01);
        }

        /* Standardized Action Buttons */
        .btn-action {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s ease;
            border: none;
            margin: 0 2px;
            color: #fff;
        }
        .btn-action i {
            font-size: 0.9rem;
        }
        .btn-action-edit {
            background-color: #5e72e4;
            box-shadow: 0 4px 6px rgba(94, 114, 228, 0.2);
        }
        .btn-action-edit:hover {
            background-color: #324cdd;
            transform: translateY(-2px);
            color: #fff;
        }
        .btn-action-delete {
            background-color: #f5365c;
            box-shadow: 0 4px 6px rgba(245, 54, 92, 0.2);
        }
        .btn-action-delete:hover {
            background-color: #ec0c38;
            transform: translateY(-2px);
            color: #fff;
        }
        .btn-action-view {
            background-color: #2dce89;
            box-shadow: 0 4px 6px rgba(45, 206, 137, 0.2);
        }
        .btn-action-view:hover {
            background-color: #24a46d;
            transform: translateY(-2px);
            color: #fff;
        }

        /* Page Headers */
        .page-header {
            margin-bottom: 2rem;
        }
        .page-title {
            font-weight: 800;
            color: var(--header-text-color);
            margin-bottom: 0.25rem;
        }
        .page-subtitle {
            color: #8898aa;
            font-size: 0.875rem;
        }

        /* Form Control Premium */
        .form-control-premium, .form-select-premium {
            border-radius: 12px !important;
            border: 1px solid #e9ecef !important;
            padding: 0.6rem 1rem !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02) !important;
            transition: all 0.2s ease !important;
            height: auto !important;
        }
        .form-control-premium:focus, .form-select-premium:focus {
            border-color: var(--btn-primary-bg) !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
            background-color: #fff !important;
        }

        .search-container {
            position: relative;
        }
        .search-container .form-control-premium {
            padding-left: 2.75rem !important;
        }
        .search-container .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            z-index: 5;
        }

        /* Sortable Table Headers */
        .table thead th.sortable {
            cursor: pointer;
            position: relative;
            padding-right: 1.5rem !important;
            transition: background-color 0.2s;
        }
        .table thead th.sortable:hover {
            background-color: rgba(0,0,0,0.03) !important;
        }
        .table thead th.sortable::after {
            content: '\f0dc';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 0.5rem;
            color: #adb5bd;
            font-size: 0.7rem;
            opacity: 0.5;
        }
        .table thead th.sortable.asc::after {
            content: '\f0de';
            color: var(--btn-primary-bg);
            opacity: 1;
        }
        .table thead th.sortable.desc::after {
            content: '\f0dd';
            color: var(--btn-primary-bg);
            opacity: 1;
        }

        /* Buttons Premium */
        .btn-dark-premium {
            background-color: #344767 !important;
            border-color: #344767 !important;
            color: #fff !important;
            border-radius: 12px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .btn-dark-premium:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08) !important;
            background-color: #2c3c58 !important;
        }

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
            overflow-x: hidden;
        }
        .sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 280px;
            z-index: 1030;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            box-shadow: 10px 0 30px rgba(0,0,0,0.05);
        }
        .sidebar.collapsed { width: 80px !important; }
        
        .sidebar-nav-container {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0;
        }
        
        /* Custom Scrollbar for Sidebar */
        .sidebar-nav-container::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav-container::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .sidebar-nav-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar .sidebar-header { 
            padding: 1.5rem 1.25rem; 
            border-bottom: 1px solid rgba(255,255,255,0.05);
            margin-bottom: 0.5rem;
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
        }
        .sidebar .sidebar-header svg, .sidebar .sidebar-header img { 
            vertical-align: middle; 
            transition: transform 0.3s ease;
        }
        .sidebar:not(.collapsed) .sidebar-header svg:hover, 
        .sidebar:not(.collapsed) .sidebar-header img:hover {
            transform: scale(1.05);
        }

        .sidebar .nav-link { 
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 1rem;
            border-radius: 12px;
            display: flex; 
            align-items: center; 
            gap: 1rem; 
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
        .sidebar .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08) !important;
            transform: translateX(5px);
        }
        .sidebar .nav-link.active { 
            background: rgba(255,255,255,0.15) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            font-weight: 600;
        }

        .sidebar .app-name { 
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            display: inline; 
            transition: opacity 0.2s; 
        }
        .sidebar.collapsed .app-name { display: none; }
        .sidebar .link-text { display: inline; transition: opacity 0.2s; }
        .sidebar.collapsed .link-text { display: none; }
        
        .sidebar .user-section { 
            padding: 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.1);
            margin-top: auto;
        }
        .sidebar .user-section .user-name { font-weight: 500; }
        .sidebar.collapsed .user-section .user-name { display: none; }
        
        .sidebar .sidebar-collapse-btn {
            background: rgba(255,255,255,0.05);
            border: none;
            color: inherit;
            font-size: 1.2rem;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .sidebar .sidebar-collapse-btn:hover {
            background: rgba(255,255,255,0.15);
        }
        .sidebar.collapsed .sidebar-collapse-btn {
            margin-left: 0;
            width: 100%;
            border-radius: 0;
            height: 50px;
            position: relative;
            top: 0;
        }
        .main-content {
            flex: 1 1 0%;
            padding: 2rem;
            background: #f8f9fa;
            min-width: 0;
            transition: margin-left 0.2s;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 1025;
        }
        .sidebar-overlay.show {
            display: block;
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
            <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>
            <nav class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <a href="{{ route('dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
                        @if(isset($settings['app_logo']))
                            <img src="{{ Storage::url($settings['app_logo']) }}" alt="Logo" width="36" height="36" class="rounded-2">
                        @else
                            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="36" height="36" rx="8" fill="#1976d2"/>
                                <text x="50%" y="55%" text-anchor="middle" fill="#fff" font-size="18" font-family="Arial, sans-serif" dy=".3em">IM</text>
                            </svg>
                        @endif
                        <span class="app-name ms-2 d-none d-lg-inline">{{ $settings['app_title'] ?? config('app.name', 'Inventory') }}</span>
                    </a>
                    <button class="sidebar-collapse-btn" id="sidebarCollapseBtn" type="button" aria-label="Collapse sidebar">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
                <div class="sidebar-nav-container">
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
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('company.*') ? 'active' : '' }}" href="{{ route('company') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Company Management"><i class="bi bi-building"></i> <span class="link-text">Company Management</span></a></li>
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('floor.*') ? 'active' : '' }}" href="{{ route('floor.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Floor Management"><i class="bi bi-layers"></i> <span class="link-text">Floor Management</span></a></li>
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('room.*') ? 'active' : '' }}" href="{{ route('room.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Room Management"><i class="bi bi-door-open"></i> <span class="link-text">Room Management</span></a></li>
                            @endif
                            @if(Auth::user()->role === 'super_admin')
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" href="{{ route('settings.index') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Settings"><i class="bi bi-gear"></i> <span class="link-text">Settings</span></a></li>
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
                </div>
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
                        {{-- Always-visible logout link (not hidden in dropdown) --}}
                        <a class="nav-link text-white mt-2" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right me-1"></i><span class="user-name">Log out</span>
                        </a>
                    @endguest
                </div>
            </nav>
            <div class="main-content">
                {{-- Mobile menu toggle (visible on small screens) --}}
                <div class="d-lg-none d-flex align-items-center justify-content-between mb-3">
                    <button class="btn btn-outline-secondary" id="sidebarToggle" type="button" aria-label="Open menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="fw-semibold">{{ env('APP_NAME', 'Inventory') }}</span>
                </div>
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
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            const isMobile = () => window.matchMedia('(max-width: 991.98px)').matches;

            const openSidebar = () => {
                // On mobile, always open the full sidebar (not collapsed)
                if (isMobile()) {
                    sidebar.classList.remove('collapsed');
                }
                sidebar.classList.add('show');
                if (sidebarOverlay) sidebarOverlay.classList.add('show');

                // On mobile, use the header button as a close (X) button
                if (isMobile() && sidebarCollapseBtn) {
                    const icon = sidebarCollapseBtn.querySelector('i');
                    if (icon) icon.className = 'bi bi-x-lg';
                    sidebarCollapseBtn.setAttribute('aria-label', 'Close menu');
                }
            };

            const closeSidebar = () => {
                sidebar.classList.remove('show');
                if (sidebarOverlay) sidebarOverlay.classList.remove('show');

                // Reset header button icon when sidebar closes (mobile)
                if (isMobile() && sidebarCollapseBtn) {
                    const icon = sidebarCollapseBtn.querySelector('i');
                    if (icon) icon.className = 'bi bi-list';
                    sidebarCollapseBtn.setAttribute('aria-label', 'Open menu');
                }
            };

            // Mobile show/hide
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (sidebar.classList.contains('show')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    closeSidebar();
                });
            }
            // Close sidebar after clicking a nav link on mobile
            if (sidebar) {
                sidebar.addEventListener('click', function(e) {
                    const target = e.target;
                    if (!isMobile()) return;
                    if (target && target.closest && target.closest('a.nav-link')) {
                        closeSidebar();
                    }
                });
            }
            // Desktop/mobile collapse
            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', function() {
                    // On mobile, this button should close the sidebar (not collapse it)
                    if (isMobile()) {
                        closeSidebar();
                        return;
                    }

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
            // Ensure overlay is hidden when resizing to desktop
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    closeSidebar();

                    // Restore desktop icon state based on collapse status
                    if (sidebarCollapseBtn) {
                        const icon = sidebarCollapseBtn.querySelector('i');
                        if (icon) {
                            icon.className = sidebar.classList.contains('collapsed') ? 'bi bi-chevron-right' : 'bi bi-list';
                        }
                        sidebarCollapseBtn.setAttribute(
                            'aria-label',
                            sidebar.classList.contains('collapsed') ? 'Expand sidebar' : 'Collapse sidebar'
                        );
                    }
                }
            });
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>

</html>
