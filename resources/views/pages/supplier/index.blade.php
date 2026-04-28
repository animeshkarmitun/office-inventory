@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Suppliers</h2>
            <p class="page-subtitle">Manage your product and service providers</p>
        </div>
        <a href="{{ route('supplier.showAdd') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Supplier
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6">
            <form method="GET" action="{{ route('supplier') }}">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="form-control form-control-premium" placeholder="Search suppliers..." value="{{ request('search') }}">
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
                        <th class="sortable {{ request('sort') == 'name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Supplier Name
                        </th>
                        <th class="sortable {{ request('sort') == 'incharge_name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'incharge_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Contact Person
                        </th>
                        <th class="sortable {{ request('sort') == 'contact_number' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'contact_number', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Phone
                        </th>
                        <th class="sortable {{ request('sort') == 'email' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'email', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Email
                        </th>
                        <th class="sortable {{ request('sort') == 'address' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'address', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Address
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="fas fa-truck text-primary text-xs"></i>
                                </div>
                                <h6 class="mb-0 text-sm fw-bold">
                                    <a href="{{ route('supplier.purchases', $supplier->id) }}" class="text-dark">{{ $supplier->name }}</a>
                                </h6>
                            </div>
                        </td>
                        <td><span class="text-sm">{{ $supplier->incharge_name ?? 'N/A' }}</span></td>
                        <td><span class="text-sm">{{ $supplier->contact_number }}</span></td>
                        <td><span class="text-sm">{{ $supplier->email ?? 'N/A' }}</span></td>
                        <td><span class="text-sm text-muted">{{ Str::limit($supplier->address, 30) }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('supplier.showEdit', ['id' => $supplier->id]) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('supplier.destroy', ['id' => $supplier->id]) }}" 
                                   class="btn-action btn-action-delete"
                                   title="Delete"
                                   onclick="return confirm('Are you sure you want to delete this supplier?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (method_exists($suppliers, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $suppliers->firstItem() ?? 0 }} to {{ $suppliers->lastItem() ?? 0 }} of {{ $suppliers->total() }} results</span>
                    {!! $suppliers->links() !!}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(94, 114, 228, 0.1); }
</style>
@endsection
