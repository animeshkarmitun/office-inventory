@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Item History: <span class="fw-bold">{{ $item->name }}</span></h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Current Details</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Serial Number:</strong> {{ $item->serial_number }}</li>
                <li class="list-group-item"><strong>Current Floor:</strong> {{ $item->floor->name ?? $item->floor_level ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Current Room:</strong> {{ $item->room->name ?? $item->room_number ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Condition:</strong> {{ $item->condition ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Assigned User:</strong> {{ $item->assignedUser->name ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($item->status) }}</li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Movement History</h5>
            @if($movements->isEmpty())
                <p>No movement history found for this item.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>From User</th>
                                <th>To User</th>
                                <th>From Location</th>
                                <th>To Location</th>
                                <th>Moved By</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($movements as $move)
                            <tr>
                                <td>{{ $move->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $move->fromUser->name ?? 'N/A' }}</td>
                                <td>{{ $move->toUser->name ?? 'N/A' }}</td>
                                <td>{{ $move->from_location ?? 'N/A' }}</td>
                                <td>{{ $move->to_location ?? 'N/A' }}</td>
                                <td>{{ $move->movedBy->name ?? 'N/A' }}</td>
                                <td>{{ $move->notes ?? '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <a href="{{ route('item') }}" class="btn btn-secondary mt-4">Back to Items</a>
</div>
@endsection 