@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Room Details: {{ $room->name }}</h4>
                    <div>
                        <a href="{{ route('room.edit', $room->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Room
                        </a>
                        <a href="{{ route('room.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Rooms
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Room Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Room Number:</th>
                                    <td><strong>{{ $room->room_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $room->name }}</td>
                                </tr>
                                <tr>
                                    <th>Floor:</th>
                                    <td>
                                        <a href="{{ route('floor.show', $room->floor->id) }}" class="text-decoration-none">
                                            {{ $room->floor->name }} ({{ $room->floor->serial_number }})
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($room->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($room->status === 'inactive')
                                            <span class="badge bg-secondary">Inactive</span>
                                        @else
                                            <span class="badge bg-warning">Under Maintenance</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $room->description ?: 'No description provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $room->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $room->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Floor Information</h5>
                            <div class="card">
                                <div class="card-body">
                                    <h6>{{ $room->floor->name }}</h6>
                                    <p class="text-muted mb-2">Serial: {{ $room->floor->serial_number }}</p>
                                    <p class="mb-2">{{ $room->floor->description ?: 'No description' }}</p>
                                    <small class="text-muted">{{ $room->floor->room_count }} total rooms on this floor</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 