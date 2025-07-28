@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Floor Details: {{ $floor->name }}</h4>
                    <div>
                        <a href="{{ route('room.create') }}?floor_id={{ $floor->id }}" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Add Room
                        </a>
                        <a href="{{ route('floor.edit', $floor->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Floor
                        </a>
                        <a href="{{ route('floor.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Floors
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Floor Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Name:</th>
                                    <td>{{ $floor->name }}</td>
                                </tr>
                                <tr>
                                    <th>Serial Number:</th>
                                    <td><code>{{ $floor->serial_number }}</code></td>
                                </tr>
                                <tr>
                                    <th>Room Count:</th>
                                    <td><span class="badge bg-info">{{ $floor->room_count }} rooms</span></td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $floor->description ?: 'No description provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $floor->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Quick Stats</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h3>{{ $floor->rooms->where('status', 'active')->count() }}</h3>
                                            <p class="mb-0">Active Rooms</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h3>{{ $floor->rooms->where('status', 'maintenance')->count() }}</h3>
                                            <p class="mb-0">Under Maintenance</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5>Rooms in this Floor</h5>
                    @if($floor->rooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Room Number</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($floor->rooms as $room)
                                    <tr>
                                        <td><strong>{{ $room->room_number }}</strong></td>
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
                                        <td>
                                            <div class="btn-group" role="group">
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No rooms found in this floor. 
                            <a href="{{ route('room.create') }}?floor_id={{ $floor->id }}" class="alert-link">Add the first room</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 