@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Items Inventory</h2>
            <p class="page-subtitle">Manage and track your office assets and equipment</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('item.export') }}" class="btn btn-outline-success rounded-3 px-3 shadow-sm">
                <i class="fas fa-file-excel me-2"></i> Export
            </a>
            <a href="{{ route('item.showImport') }}" class="btn btn-outline-warning rounded-3 px-3 shadow-sm text-dark">
                <i class="fas fa-file-import me-2"></i> Import
            </a>
            <a href="{{ route('item.showAdd') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Add New Item
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12">
            <form method="GET" action="{{ route('item') }}" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="search-container mb-0">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-control form-control-premium" placeholder="Search by name, tag, or barcode..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="company_id" class="form-select form-select-premium" onchange="this.form.submit()">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark-premium w-100" style="height: 46px;">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('item') }}" class="btn btn-outline-secondary rounded-3 w-100 d-flex align-items-center justify-content-center" style="height: 46px;">
                        <i class="fas fa-undo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div id="items-table-wrapper" class="card card-table shadow-sm border-0">
        @include('pages.item.partials.table')
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Barcode functionality for table
    function generateTableBarcodes() {
        @foreach($items as $item)
            @php
                $barcodeValue = $item->barcode ?: $item->asset_tag;
            @endphp
            @if($barcodeValue)
                try {
                    JsBarcode(document.getElementById('barcode-{{ $item->id }}'), '{{ $barcodeValue }}', {
                        format: "CODE128",
                        width: 1.2,
                        height: 30,
                        displayValue: false,
                        margin: 1
                    });
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

    document.addEventListener('DOMContentLoaded', function() {
        generateTableBarcodes();
    });
</script>
@endpush
