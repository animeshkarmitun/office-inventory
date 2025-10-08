@extends('layouts.app')

@section('content')

@include('inc.alert')
<h1 class="mb-4" style="font-size:2.1rem;font-weight:600;">List of Items</h1>
<div class="item-controls mb-4 d-flex flex-column flex-md-row align-items-center gap-3">
    <form method="GET" action="{{ route('item') }}" class="search-form">
        <div class="input-group search-group">
            <input type="text" name="search" class="form-control search-input" placeholder="Search items..." value="{{ request('search') }}" autocomplete="off">
            <button class="btn btn-search" type="submit"><i class="fas fa-search"></i> Search</button>
        </div>
    </form>
    <div class="d-flex flex-row gap-2 button-group">
        <a href="{{ route('item.showAdd') }}" class="btn btn-add">
            <span class="btn-add-icon me-2">
                <i class="fas fa-plus"></i>
                <svg class="fallback-plus" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:none;vertical-align:middle;">
                    <rect x="7.5" width="3" height="18" rx="1.5" fill="currentColor"/>
                    <rect y="10.5" width="3" height="18" rx="1.5" transform="rotate(-90 0 10.5)" fill="currentColor"/>
                </svg>
            </span>
            Add new item
        </a>
        <a href="{{ route('item.export') }}" class="btn btn-export" id="exportBtn">
            <span class="btn-export-icon me-2">
                <i class="fas fa-file-excel"></i>
            </span>
            <span class="export-text">Export to Excel</span>
            <span class="export-loading" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Exporting...
            </span>
        </a>
        <a href="{{ route('item.showImport') }}" class="btn btn-import">
            <span class="btn-import-icon me-2">
                <i class="fas fa-file-import"></i>
            </span>
            Import from Excel
        </a>
    </div>
</div>
<div id="items-table-wrapper" style="overflow: visible !important; position: relative;">
    @include('pages.item.partials.table')
</div>

@endsection

@push('styles')
<style>
    .item-controls {
        margin-bottom: 2.5rem;
        gap: 1.2rem;
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .search-form {
        flex: 0 0 auto;
        min-width: 300px;
    }
    .search-group {
        box-shadow: 0 2px 8px rgba(25, 118, 210, 0.07);
        border-radius: 2rem;
        overflow: hidden;
        background: #fff;
    }
    .search-input {
        border: none;
        border-radius: 2rem 0 0 2rem;
        font-size: 1rem;
        padding-left: 1rem;
        background: #f8fafc;
        height: 2.5rem;
        width: 250px;
    }
    .search-input:focus {
        box-shadow: none;
        background: #fff;
    }
    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 0 2rem 2rem 0;
        font-weight: 600;
        padding: 0 1rem;
        height: 2.5rem;
        width: 50px;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .btn-search::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    .btn-search:hover::before {
        left: 100%;
    }
    .btn-search:hover, .btn-search:focus {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        color: #fff;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
        transform: translateY(-1px);
    }
    .btn-search:active {
        transform: translateY(0);
    }
    .btn-add {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.5rem 1.2rem;
        height: 2.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.25), 0 2px 8px rgba(118, 75, 162, 0.15);
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
        cursor: pointer;
        outline: none;
        position: relative;
        overflow: hidden;
    }
    .btn-add::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    .btn-add:hover::before {
        left: 100%;
    }
    .btn-add:hover, .btn-add:focus {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        color: #fff;
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.35), 0 8px 20px rgba(118, 75, 162, 0.25);
        text-decoration: none;
        transform: translateY(-2px) scale(1.02);
    }
    .btn-add:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .btn-add-icon {
        display: flex;
        align-items: center;
        font-size: 1.2em;
    }
    /* Fallback for plus icon if Font Awesome is missing */
    .btn-add .fa-plus { display: inline-block; }
    .btn-add .fallback-plus { display: none; }
    .btn-add .fa-plus:empty + .fallback-plus { display: inline-block; }
    
    .btn-export {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #fff;
        border-radius: 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.5rem 1.2rem;
        height: 2.5rem;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.25), 0 2px 8px rgba(56, 239, 125, 0.15);
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
        cursor: pointer;
        outline: none;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
    .btn-export::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    .btn-export:hover::before {
        left: 100%;
    }
    .btn-export:hover, .btn-export:focus {
        background: linear-gradient(135deg, #0f8a7e 0%, #2dd465 100%);
        color: #fff;
        box-shadow: 0 12px 35px rgba(17, 153, 142, 0.35), 0 8px 20px rgba(56, 239, 125, 0.25);
        text-decoration: none;
        transform: translateY(-2px) scale(1.02);
    }
    .btn-export:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
    }
    .btn-export-icon {
        display: flex;
        align-items: center;
        font-size: 1.1em;
    }
    
    .btn-import {
        background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
        color: #fff;
        border-radius: 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.5rem 1.2rem;
        height: 2.5rem;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.25), 0 2px 8px rgba(255, 167, 38, 0.15);
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
        cursor: pointer;
        outline: none;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
    .btn-import::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    .btn-import:hover::before {
        left: 100%;
    }
    .btn-import:hover, .btn-import:focus {
        background: linear-gradient(135deg, #ff5252 0%, #ff9800 100%);
        color: #fff;
        box-shadow: 0 12px 35px rgba(255, 107, 107, 0.35), 0 8px 20px rgba(255, 167, 38, 0.25);
        text-decoration: none;
        transform: translateY(-2px) scale(1.02);
    }
    .btn-import:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    }
    .btn-import-icon {
        display: flex;
        align-items: center;
        font-size: 1.1em;
    }
    
    .button-group {
        position: relative;
    }
    
    .button-group .btn {
        position: relative;
        z-index: 1;
    }
    
    .button-group .btn:nth-child(1) {
        animation-delay: 0.1s;
    }
    
    .button-group .btn:nth-child(2) {
        animation-delay: 0.2s;
    }
    
    .button-group .btn:nth-child(3) {
        animation-delay: 0.3s;
    }
    
    .button-group .btn {
        animation: slideInRight 0.5s ease-out forwards;
        opacity: 0;
        transform: translateX(30px);
    }
    
    @keyframes slideInRight {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    /* Dropdown menu styling - Simplified approach */
    .dropdown {
        position: relative;
        display: inline-block;
    }
    
    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        left: auto;
        z-index: 9999;
        display: none;
        min-width: 160px;
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        font-size: 1rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        opacity: 1;
        visibility: visible;
        transition: all 0.15s ease-in-out;
    }
    
    .dropdown-menu.show {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    .dropdown-item {
        display: block !important;
        width: 100% !important;
        padding: 0.5rem 1rem !important;
        clear: both;
        font-weight: 400;
        color: #212529 !important;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        transition: background-color 0.15s ease-in-out;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #16181b;
    }
    
    .dropdown-item.text-danger:hover {
        background-color: #f8d7da;
        color: #721c24 !important;
    }
    
    .dropdown-item.text-success:hover {
        background-color: #d1e7dd;
        color: #0f5132 !important;
    }
    
    .dropdown-item.text-warning:hover {
        background-color: #fff3cd;
        color: #664d03 !important;
    }
    
    /* Ensure table and all containers have overflow visible */
    #items-table-wrapper {
        overflow: visible !important;
    }
    
    .table-responsive {
        overflow: visible !important;
    }
    
    .table {
        overflow: visible !important;
    }
    
    table {
        overflow: visible !important;
    }
    
    table td {
        overflow: visible !important;
        position: relative;
    }
    
    table th {
        overflow: visible !important;
        position: relative;
    }
    
    table tbody {
        overflow: visible !important;
    }
    
    table thead {
        overflow: visible !important;
    }
    
    /* Ensure parent containers don't clip dropdowns */
    .main-content {
        overflow: visible !important;
    }
    
    .layout-wrapper {
        overflow: visible !important;
    }
    
    #app {
        overflow: visible !important;
    }
    
    /* Ensure dropdown items are visible */
    .dropdown-menu li {
        display: block !important;
        width: 100% !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    .dropdown-menu li a,
    .dropdown-menu li button {
        display: block !important;
        width: 100% !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    /* Additional overflow fixes for better dropdown visibility */
    body {
        overflow-x: auto !important;
    }
    
    html {
        overflow-x: auto !important;
    }
    
    /* Ensure dropdowns can extend beyond table boundaries */
    .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        right: 0 !important;
        left: auto !important;
        z-index: 9999 !important;
        transform: none !important;
    }
    /* Custom Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin: 2rem 0;
        padding: 0;
        list-style: none;
    }
    
    .pagination .page-item {
        margin: 0;
    }
    
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        color: #6b7280;
        background-color: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .pagination .page-link:hover {
        color: #2563eb;
        background-color: #f8fafc;
        border-color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }
    
    .pagination .page-item.active .page-link {
        color: #fff;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
    
    .pagination .page-item.disabled .page-link {
        color: #9ca3af;
        background-color: #f9fafb;
        border-color: #e5e7eb;
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    /* Previous/Next button styling */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-weight: 600;
        min-width: 80px;
    }
    
    /* Ellipsis styling */
    .pagination .page-item .page-link[aria-label*="ellipsis"] {
        background: none;
        border: none;
        box-shadow: none;
        cursor: default;
    }
    
    .pagination .page-item .page-link[aria-label*="ellipsis"]:hover {
        transform: none;
        box-shadow: none;
        background: none;
        border: none;
    }
    
    /* Pagination wrapper */
    .pagination-wrapper {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        margin: 2rem 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }
    
    /* Pagination info text */
    .pagination-info {
        text-align: center;
        color: #6b7280;
        font-size: 0.875rem;
        margin: 0 0 1.5rem 0;
        font-weight: 500;
        background: #f8fafc;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    /* Override Bootstrap pagination styles */
    .pagination .page-item .page-link {
        border-radius: 8px !important;
        border: 1px solid #e5e7eb !important;
        margin: 0 0.125rem !important;
        padding: 0.5rem 0.75rem !important;
    }
    
    /* Remove any existing large icons or special characters */
    .pagination .page-item .page-link i,
    .pagination .page-item .page-link svg {
        display: none !important;
    }
    
    /* Ensure no large special characters are displayed */
    .pagination .page-item .page-link::before,
    .pagination .page-item .page-link::after {
        display: none !important;
    }
    
    /* Responsive pagination */
    @media (max-width: 640px) {
        .pagination {
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .pagination .page-link {
            min-width: 36px;
            height: 36px;
            font-size: 0.8rem;
            padding: 0.375rem 0.5rem;
        }
        
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            min-width: 60px;
        }
        
        .pagination-wrapper {
            padding: 1rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .item-controls {
            flex-direction: column;
        }
        .button-group {
            flex-direction: column;
        }
        .btn-add, .btn-export, .btn-import {
            min-width: 100%;
        }
        .search-form {
            min-width: 100%;
        }
        .search-input {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced dropdown toggle functionality
    document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var menu = this.nextElementSibling;
            var isCurrentlyOpen = menu && menu.classList.contains('show');
            
            // Close all other dropdowns first
            document.querySelectorAll('.dropdown-menu.show').forEach(function(otherMenu) {
                otherMenu.classList.remove('show');
                otherMenu.style.display = 'none';
            });
            
            // Toggle current dropdown
            if (menu) {
                if (isCurrentlyOpen) {
                    // Close the dropdown
                    menu.classList.remove('show');
                    menu.style.display = 'none';
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    // Open the dropdown
                    menu.classList.add('show');
                    menu.style.display = 'block';
                    menu.style.opacity = '1';
                    menu.style.visibility = 'visible';
                    menu.style.zIndex = '9999';
                    menu.style.position = 'absolute';
                    menu.style.top = '100%';
                    menu.style.right = '0';
                    menu.style.left = 'auto';
                    this.setAttribute('aria-expanded', 'true');
                }
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
                menu.style.display = 'none';
            });
        }
    });
    
    // Close dropdowns when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
                menu.style.display = 'none';
            });
        }
    });

    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Export button functionality
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        const exportText = exportBtn.querySelector('.export-text');
        const exportLoading = exportBtn.querySelector('.export-loading');
        
        exportBtn.addEventListener('click', function() {
            // Show loading state
            exportText.style.display = 'none';
            exportLoading.style.display = 'inline';
            exportBtn.style.pointerEvents = 'none';
            
            // The download will start automatically
            // Reset button after a delay (in case download fails)
            setTimeout(function() {
                exportText.style.display = 'inline';
                exportLoading.style.display = 'none';
                exportBtn.style.pointerEvents = 'auto';
            }, 5000);
        });
    }
    });

    // Barcode functionality for table
    function generateTableBarcodes() {
        @foreach($items as $item)
            @php
                $barcodeValue = $item->barcode ?: $item->asset_tag;
            @endphp
            @if($barcodeValue)
                try {
                    console.log('Generating barcode for item {{ $item->id }} with value: {{ $barcodeValue }}');
                        JsBarcode(document.getElementById('barcode-{{ $item->id }}'), '{{ $barcodeValue }}', {
                            format: "CODE128",
                            width: 1.2,
                            height: 30,
                            displayValue: false,
                            margin: 1
                        });
                    console.log('Barcode generated successfully for item {{ $item->id }}');
                } catch (error) {
                    console.error('Error generating barcode for item {{ $item->id }}:', error);
                }
            @endif
        @endforeach
    }

    function downloadBarcodeFromTable(barcodeValue, itemId) {
        const canvas = document.getElementById('barcode-' + itemId);
        const link = document.createElement('a');
        link.download = `barcode_${barcodeValue}.png`;
        link.href = canvas.toDataURL();
        link.click();
    }

    // Generate barcodes when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Generating table barcodes...');
        generateTableBarcodes();
        console.log('Table barcodes generation completed');
    });
</script>
@endpush
