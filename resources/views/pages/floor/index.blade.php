@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Floor Management</h4>
                    <a href="{{ route('floor.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Floor
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
                                    <th>Name</th>
                                    <th>Serial Number</th>
                                    <th>Room Count</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($floors as $floor)
                                <tr>
                                    <td>{{ $floor->id }}</td>
                                    <td>{{ $floor->name }}</td>
                                    <td><code>{{ $floor->serial_number }}</code></td>
                                    <td>
                                        <span class="badge bg-info">{{ $floor->room_count }} rooms</span>
                                    </td>
                                    <td>{{ Str::limit($floor->description, 50) }}</td>
                                    <td>{{ $floor->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('floor.show', $floor->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <a href="{{ route('floor.edit', $floor->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            @if($floor->room_count == 0)
                                                <form action="{{ route('floor.destroy', $floor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this floor?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot delete floor with rooms">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No floors found. <a href="{{ route('floor.create') }}">Create the first floor</a></td>
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