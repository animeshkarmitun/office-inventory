<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Serial Number</th>
            <th scope="col">Asset Tag</th>
            <th scope="col">Barcode</th>
            <th scope="col">RFID Tag</th>
            <th scope="col">Location</th>
            <th scope="col">Assigned User</th>
            <th scope="col">Condition</th>
            <th scope="col">Asset Type</th>
            <th scope="col">Value</th>
            <th scope="col">Status</th>
            <th scope="col">Approval</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <th scope="row">{{ $item->id }}</th>
            <td>{{ $item->name }}</td>
            <td>{{ $item->serial_number }}</td>
            <td>{{ $item->asset_tag ?? 'N/A' }}</td>
            <td>{{ $item->barcode ?? 'N/A' }}</td>
            <td>{{ $item->rfid_tag ?? 'N/A' }}</td>
            <td>
                {{ $item->floor_level }} - {{ $item->room_number }}
                @if($item->location)
                    <br><small>{{ $item->location }}</small>
                @endif
            </td>
            <td>{{ $item->assignedUser->name ?? 'N/A' }}</td>
            <td>{{ $item->condition ?? 'N/A' }}</td>
            <td>{{ ucfirst($item->asset_type) }}</td>
            <td>{{ $item->value ? number_format($item->value, 2) : 'N/A' }}</td>
            <td>
                <span class="badge bg-{{ $item->status === 'available' ? 'success' : ($item->status === 'in_use' ? 'primary' : ($item->status === 'maintenance' ? 'warning' : ($item->status === 'not_traceable' ? 'danger' : 'secondary'))) }}">
                    {{ ucfirst($item->status) }}
                </span>
            </td>
            <td>
                @if($item->is_approved)
                    <span class="badge bg-success">Approved</span>
                    <br>
                    <small>By: {{ $item->approvedBy->name }}</small>
                    <br>
                    <small>{{ $item->approved_at->format('Y-m-d H:i') }}</small>
                @else
                    <span class="badge bg-warning">Pending</span>
                    @if(Auth::user()->is_admin)
                        <form action="{{ route('item.approve', ['id' => $item->id]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                    @endif
                @endif
            </td>
            <td>
                <a href="{{ route('item.destroy', ['id' => $item->id]) }}" class="btn btn-danger">Delete</a>
                <a href="{{ route('item.showEdit', ['id' => $item->id]) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('asset.movement.history', ['id' => $item->id]) }}" class="btn btn-info">Movement History</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@if (method_exists($items, 'links'))
    <div class="d-flex justify-content-center">
        {!! $items->links() !!}
    </div>
@endif 