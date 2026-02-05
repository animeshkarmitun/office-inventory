<table class="table">
    <thead class="table-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Asset Tag</th>
            <th scope="col">Barcode</th>
            <th scope="col">Company</th>
            <th scope="col">Location</th>
            <th scope="col">Assigned User</th>
            <th scope="col">Condition</th>
            <th scope="col">Asset Type</th>
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
            <td>{{ $item->asset_tag ?? 'N/A' }}</td>
            <td>
                @php
                    $barcodeValue = $item->barcode ?: $item->asset_tag;
                @endphp
                @if($barcodeValue)
                <div class="d-flex align-items-center">
                    <canvas id="barcode-{{ $item->id }}" style="width: 100px; height: 30px; border: 1px solid #ddd; border-radius: 3px;"></canvas>
                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="downloadBarcodeFromTable('{{ $barcodeValue }}', '{{ $item->id }}')" title="Download Barcode">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                @else
                    N/A
                @endif
            </td>
            <td>{{ $item->company->name ?? 'N/A' }}</td>
            <td>
                {{ $item->floor_level }} - {{ $item->room_number }}
                @if($item->location)
                    <br><small>{{ $item->location }}</small>
                @endif
            </td>
            <td>{{ $item->assignedUser->name ?? 'N/A' }}</td>
            <td>{{ $item->condition ?? 'N/A' }}</td>
            <td>{{ ucfirst($item->asset_type) }}</td>
            <td>
                <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
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
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionsDropdown{{ $item->id }}" aria-expanded="false">
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
                        @if(!$item->is_approved)
                            @if(Auth::user()->is_admin || Auth::user()->role === 'super_admin')
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
                                    <span class="dropdown-item text-muted">
                                        <i class="bi bi-hourglass-split"></i> Pending Approval
                                    </span>
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
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results
        </div>
        <div class="d-flex justify-content-center">
            {!! $items->links() !!}
        </div>
    </div>
@endif 