@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Purchases</h2>
            <p class="page-subtitle">Manage procurement records and supplier invoices</p>
        </div>
        <a href="{{ route('purchase.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Purchase
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 col-lg-4">
            <form method="GET" action="{{ route('purchase.index') }}">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control form-control-premium" placeholder="Search by supplier, invoice, or date..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <div class="card card-table shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable {{ request('sort') == 'purchase_number' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'purchase_number', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Purchase #
                        </th>
                        <th>Supplier</th>
                        <th class="sortable {{ request('sort') == 'invoice_number' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'invoice_number', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Invoice Number
                        </th>
                        <th class="sortable {{ request('sort') == 'purchase_date' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'purchase_date', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Date
                        </th>
                        <th>Department</th>
                        <th class="sortable {{ request('sort') == 'total_value' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'total_value', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Total Value
                        </th>
                        <th>Status/Files</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($purchases->currentPage() - 1) * $purchases->perPage() + $loop->iteration }}</span></td>
                        <td><span class="text-xs font-weight-bold">{{ $purchase->purchase_number ?? 'PUR-' . $purchase->id }}</span></td>
                        <td>
                            <h6 class="mb-0 text-sm fw-bold">
                                @if($purchase->supplier)
                                    <a href="{{ route('supplier.purchases', $purchase->supplier->id) }}" class="text-dark">{{ $purchase->supplier->name }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </h6>
                        </td>
                        <td><span class="text-sm">{{ $purchase->invoice_number }}</span></td>
                        <td><span class="text-sm">{{ $purchase->purchase_date ? $purchase->purchase_date->format('d M, Y') : '-' }}</span></td>
                        <td><span class="text-sm">{{ $purchase->department ? $purchase->department->name : '-' }}</span></td>
                        <td><span class="text-sm fw-bold">{{ number_format($purchase->total_value, 2) }}</span></td>
                        <td>
                            @if($purchase->images->count() > 0)
                                <span class="badge bg-info-soft text-info px-3">
                                    <i class="fas fa-image me-1"></i> {{ $purchase->images->count() }} Files
                                </span>
                            @else
                                <span class="text-muted text-xs">No images</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('purchase.show', $purchase->id) }}" class="btn-action btn-action-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('purchase.edit', $purchase->id) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-action-delete btn-delete-modal" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (method_exists($purchases, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $purchases->firstItem() ?? 0 }} to {{ $purchases->lastItem() ?? 0 }} of {{ $purchases->total() }} results</span>
                    {!! $purchases->links() !!}
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <p class="text-muted">Are you sure you want to delete this purchase record? This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-3 px-4 shadow-sm" id="confirmDeleteBtn">Delete Record</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-info-soft { background-color: rgba(17, 201, 240, 0.1); }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let formToDelete = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.querySelectorAll('.btn-delete-modal').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            formToDelete = btn.closest('form');
            deleteModal.show();
        });
    });
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (formToDelete) {
            formToDelete.submit();
        }
    });
});
</script>
@endpush
@endsection