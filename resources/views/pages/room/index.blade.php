@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Room Management</h2>
            <p class="page-subtitle">Configure office spaces and physical locations</p>
        </div>
        <a href="{{ route('room.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Room
        </a>
    </div>

    <div class="card card-table shadow-sm border-0 mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable {{ request('sort') == 'floor_id' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'floor_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Floor
                        </th>
                        <th class="sortable {{ request('sort') == 'name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Room Name
                        </th>
                        <th class="sortable {{ request('sort') == 'status' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Status
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
                    @forelse($rooms as $room)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($rooms->currentPage() - 1) * $rooms->perPage() + $loop->iteration }}</span></td>
                        <td>
                            <span class="text-sm">
                                <a href="{{ route('floor.show', $room->floor->id) }}" class="text-dark font-weight-bold">
                                    {{ $room->floor->name }}
                                </a>
                            </span>
                        </td>
                        <td><h6 class="mb-0 text-sm fw-bold">{{ $room->name }}</h6></td>
                        <td>
                            @if($room->status === 'active')
                                <span class="badge bg-success-soft text-success px-3">Active</span>
                            @elseif($room->status === 'inactive')
                                <span class="badge bg-light text-muted px-3 border">Inactive</span>
                            @else
                                <span class="badge bg-warning-soft text-warning px-3">Maintenance</span>
                            @endif
                        </td>
                        <td><span class="text-sm text-muted">{{ Str::limit($room->description, 50) }}</span></td>
                        <td><span class="text-sm">{{ $room->created_at->format('M d, Y') }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('room.show', $room->id) }}" class="btn-action btn-action-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('room.edit', $room->id) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('room.destroy', $room->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this room?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <p class="text-muted mb-0">No rooms found. <a href="{{ route('room.create') }}">Create the first room</a></p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (method_exists($rooms, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $rooms->firstItem() ?? 0 }} to {{ $rooms->lastItem() ?? 0 }} of {{ $rooms->total() }} results</span>
                    {!! $rooms->links() !!}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(45, 206, 137, 0.1); }
    .bg-warning-soft { background-color: rgba(fb, 99, 64, 0.1); }
</style>
@endsection