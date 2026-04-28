@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Floor Management</h2>
            <p class="page-subtitle">Define and manage the physical layout of your office levels</p>
        </div>
        <a href="{{ route('floor.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Floor
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
                            Floor Name
                        </th>
                        <th class="sortable {{ request('sort') == 'serial_number' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'serial_number', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Serial Number
                        </th>
                        <th class="sortable {{ request('sort') == 'rooms_count' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'rooms_count', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Room Count
                        </th>
                        <th>Description</th>
                        <th class="sortable {{ request('sort') == 'created_at' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Created
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($floors as $floor)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($floors->currentPage() - 1) * $floors->perPage() + $loop->iteration }}</span></td>
                        <td><h6 class="mb-0 text-sm fw-bold">{{ $floor->name }}</h6></td>
                        <td><code class="text-primary text-sm font-weight-bold">{{ $floor->serial_number }}</code></td>
                        <td>
                            <span class="badge bg-info-soft text-info px-3">
                                {{ $floor->rooms_count }} Room(s)
                            </span>
                        </td>
                        <td><span class="text-sm text-muted">{{ Str::limit($floor->description, 50) }}</span></td>
                        <td><span class="text-sm">{{ $floor->created_at->format('M d, Y') }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('floor.show', $floor->id) }}" class="btn-action btn-action-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('floor.edit', $floor->id) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($floor->rooms_count == 0)
                                    <form action="{{ route('floor.destroy', $floor->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this floor?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn-action bg-light text-muted cursor-not-allowed" disabled title="Cannot delete floor with rooms">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <p class="text-muted mb-0">No floors found. <a href="{{ route('floor.create') }}">Create the first floor</a></p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (method_exists($floors, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $floors->firstItem() ?? 0 }} to {{ $floors->lastItem() ?? 0 }} of {{ $floors->total() }} results</span>
                    {!! $floors->links() !!}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-info-soft { background-color: rgba(17, 201, 240, 0.1); }
    .cursor-not-allowed { cursor: not-allowed; }
</style>
@endsection