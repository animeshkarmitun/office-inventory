<table class="table">
    <thead class="table-dark">
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
            <th scope="col">Annual Depreciation</th>
            <th scope="col">Book Value</th>
            <th scope="col">Status</th>
            <th scope="col">Approval</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <th scope="row">{{ $item->id }}</th>
            <td><a href="{{ route('item.history', $item->id) }}">{{ $item->name }}</a></td>
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
            <td>{{ $item->annualDepreciation() !== null ? number_format($item->annualDepreciation(), 2) : 'N/A' }}</td>
            <td>{{ $item->currentBookValue() !== null ? number_format($item->currentBookValue(), 2) : 'N/A' }}</td>
            <td>
                @if($item->status === 'pending')
                    <span class="badge bg-warning" data-bs-toggle="tooltip" title="Pending approval">Pending</span>
                @elseif($item->status === 'approved')
                    <span class="badge bg-success" data-bs-toggle="tooltip" title="Approved by: {{ $item->approvedBy ? $item->approvedBy->name : 'N/A' }}&#10;{{ $item->approved_at ? $item->approved_at->format('Y-m-d H:i') : '' }}">Approved</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                @endif
            </td>
            <td class="text-center">
                @if($item->is_approved)
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved</span>
                    <br>
                    <small>By: {{ $item->approvedBy ? $item->approvedBy->name : 'N/A' }}</small>
                    <br>
                    <small>{{ $item->approved_at->format('Y-m-d H:i') }}</small>
                @else
                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                @endif
            </td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionsDropdown{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="actionsDropdown{{ $item->id }}">
                        <li>
                            <a class="dropdown-item" href="{{ route('item.showEdit', ['id' => $item->id]) }}">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('item.destroy', ['id' => $item->id]) }}" onclick="return confirm('Are you sure?');">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('asset.movement.history', ['id' => $item->id]) }}">
                                <i class="bi bi-clock-history"></i> Movement History
                            </a>
                        </li>
                        @if(Auth::user()->is_admin)
                            @if(!$item->is_approved)
                                <li>
                                    <form action="{{ route('item.approve', ['id' => $item->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-success">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <form action="#" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-warning">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </form>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
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