@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Borrower List</h2>
            <p class="page-subtitle">Track asset assignments and borrowing history</p>
        </div>
        <a href="{{ route('borrower.showAdd') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Borrower
        </a>
    </div>

    <div class="card card-table shadow-sm border-0 mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable {{ request('sort') == 'name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Borrower Name
                        </th>
                        <th class="sortable {{ request('sort') == 'staff_id' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'staff_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Staff ID
                        </th>
                        <th>Department</th>
                        <th>Assigned Item</th>
                        <th class="sortable {{ request('sort') == 'created_at' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Borrow Date
                        </th>
                        <th class="sortable {{ request('sort') == 'status' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Status
                        </th>
                        <th>Authorized By</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrowers as $borrower)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($borrowers->currentPage() - 1) * $borrowers->perPage() + $loop->iteration }}</span></td>
                        <td>
                            <h6 class="mb-0 text-sm fw-bold">
                                <a href="{{ route('borrower.history', $borrower->id) }}" class="text-dark">{{ $borrower->name }}</a>
                            </h6>
                        </td>
                        <td><span class="text-sm">{{ $borrower->staff_id }}</span></td>
                        <td>
                            <span class="text-sm">
                                {{ $borrower->department->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm fw-bold">
                                {{ $borrower->item->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td><span class="text-sm">{{ $borrower->created_at->format('d M, Y') }}</span></td>
                        <td>
                            @if ($borrower->status == 1)
                                <span class="badge bg-success-soft text-success px-3">Active</span>
                            @else
                                <span class="badge bg-light text-muted px-3 border">Returned</span>
                            @endif
                        </td>
                        <td><span class="text-sm">{{ $borrower->user->name ?? 'N/A' }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('borrower.showEdit', ['id' => $borrower->id]) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('borrower.destroy', ['id' => $borrower->id]) }}" 
                                   class="btn-action btn-action-delete" 
                                   title="Delete"
                                   onclick="return confirm('Are you sure you want to delete this record?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (method_exists($borrowers, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $borrowers->firstItem() ?? 0 }} to {{ $borrowers->lastItem() ?? 0 }} of {{ $borrowers->total() }} results</span>
                    {!! $borrowers->links() !!}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(45, 206, 137, 0.1); }
</style>
@endsection
