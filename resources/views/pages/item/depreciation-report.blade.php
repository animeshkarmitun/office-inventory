@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Depreciation Report</h2>
            <p class="page-subtitle">Track asset valuation and annual depreciation calculations</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary rounded-3 px-3 shadow-sm" onclick="window.print()">
                <i class="fas fa-print me-2"></i> Print Report
            </button>
            <a href="{{ route('item.export') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
                <i class="fas fa-file-export me-2"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card card-table shadow-sm border-0 mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable {{ request('sort') == 'name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Asset Name
                        </th>
                        <th>Purchase Date</th>
                        <th class="sortable {{ request('sort') == 'value' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'value', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Initial Value
                        </th>
                        <th>Method</th>
                        <th>Rate (%)</th>
                        <th>Annual Depr.</th>
                        <th>Book Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-sm bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="fas fa-chart-line text-primary text-xs"></i>
                                </div>
                                <h6 class="mb-0 text-sm fw-bold">
                                    <a href="{{ route('item.history', $item->id) }}" class="text-dark">{{ $item->name }}</a>
                                </h6>
                            </div>
                        </td>
                        <td><span class="text-sm">{{ $item->purchase_date ? $item->purchase_date->format('d M, Y') : 'N/A' }}</span></td>
                        <td><span class="text-sm fw-bold">{{ $item->value ? number_format($item->value, 2) : '0.00' }}</span></td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $item->depreciation_method ? ucwords(str_replace('_', ' ', $item->depreciation_method)) : 'Straight Line' }}
                            </span>
                        </td>
                        <td><span class="text-sm">{{ $item->depreciation_rate !== null ? number_format($item->depreciation_rate, 2) : '0.00' }}%</span></td>
                        <td><span class="text-sm text-danger fw-bold">-{{ $item->annualDepreciation() !== null ? number_format($item->annualDepreciation(), 2) : '0.00' }}</span></td>
                        <td><span class="text-sm text-success fw-bold">{{ $item->currentBookValue() !== null ? number_format($item->currentBookValue(), 2) : '0.00' }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <p class="text-muted mb-0">No assets found for depreciation reporting.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (method_exists($items, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results</span>
                    {!! $items->links() !!}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(94, 114, 228, 0.1); }
    @media print {
        .sidebar, .page-header .btn, .px-4.py-3.border-top { display: none !important; }
        .main-content { margin-left: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endsection