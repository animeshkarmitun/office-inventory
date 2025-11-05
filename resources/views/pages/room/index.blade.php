@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Room Management</h4>
                    <a href="{{ route('room.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Room
                    </a>
                </div>
                <div class="card-body">
                    @if(session('message'))
                        <div class="alert {{ session('alert') }} alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Floor</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $room)
                                <tr>
                                    <td>{{ $room->id }}</td>
                                    <td>
                                        <a href="{{ route('floor.show', $room->floor->id) }}" class="text-decoration-none">
                                            {{ $room->floor->name }}
                                        </a>
                                    </td>
                                    <td>{{ $room->name }}</td>
                                    <td>
                                        @if($room->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($room->status === 'inactive')
                                            <span class="badge bg-secondary">Inactive</span>
                                        @else
                                            <span class="badge bg-warning">Maintenance</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($room->description, 50) }}</td>
                                    <td>{{ $room->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('room.show', $room->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <a href="{{ route('room.edit', $room->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('room.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this room?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No rooms found. <a href="{{ route('room.create') }}">Create the first room</a></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 