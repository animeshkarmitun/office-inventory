<div class="table-responsive">
    <table class="table table-hover align-items-center">
        <thead>
            <tr>
                <th>#</th>
                <th class="sortable {{ request('sort') == 'name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                    onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                    Name
                </th>
                <th class="sortable {{ request('sort') == 'asset_tag' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                    onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'asset_tag', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                    Asset Tag
                </th>
                <th>Barcode</th>
                <th class="sortable {{ request('sort') == 'company_id' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                    onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'company_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                    Company
                </th>
                <th>Location</th>
                <th>Condition</th>
                <th>Type</th>
                <th class="sortable {{ request('sort') == 'status' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                    onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                    Status
                </th>
                <th class="sortable {{ request('sort') == 'is_approved' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                    onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'is_approved', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                    Approval
                </th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td><span class="text-xs font-weight-bold">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</span></td>
                <td>
                    <h6 class="mb-0 text-sm fw-bold">
                        <a href="{{ route('item.history', $item->id) }}" class="text-dark">{{ $item->name }}</a>
                    </h6>
                </td>
                <td><span class="text-sm">{{ $item->asset_tag ?? 'N/A' }}</span></td>
                <td>
                    @php
                        $barcodeValue = $item->barcode ?: $item->asset_tag;
                    @endphp
                    @if($barcodeValue)
                    <div class="d-flex align-items-center gap-2">
                        <canvas id="barcode-{{ $item->id }}" style="width: 80px; height: 25px; border-radius: 4px;"></canvas>
                        <button class="btn btn-link p-0 text-primary" onclick="downloadBarcodeFromTable('{{ $barcodeValue }}', '{{ $item->id }}')" title="Download Barcode">
                            <i class="fas fa-download fa-xs"></i>
                        </button>
                    </div>
                    @else
                        <span class="text-muted text-xs">N/A</span>
                    @endif
                </td>
                <td><span class="text-sm">{{ $item->company->name ?? 'N/A' }}</span></td>
                <td>
                    <span class="text-sm">{{ $item->floor_level }} - {{ $item->room_number }}</span>
                    @if($item->location)
                        <br><small class="text-muted">{{ $item->location }}</small>
                    @endif
                </td>
                <td><span class="text-sm">{{ $item->condition ?? 'N/A' }}</span></td>
                <td><span class="badge bg-light text-dark border">{{ ucfirst($item->asset_type) }}</span></td>
                <td>
                    <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                </td>
                <td class="text-center">
                    @if($item->is_approved)
                        <span class="badge bg-success-soft text-success">
                            <i class="fas fa-check-circle me-1"></i>Approved
                        </span>
                    @else
                        <span class="badge bg-warning-soft text-warning">
                            <i class="fas fa-clock me-1"></i>Pending
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('item.showEdit', ['id' => $item->id]) }}" class="btn-action btn-action-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('asset.movement.history', ['id' => $item->id]) }}" class="btn-action btn-action-view" title="History">
                            <i class="fas fa-history"></i>
                        </a>
                        @if(!$item->is_approved && (Auth::user()->is_admin || Auth::user()->role === 'super_admin'))
                            <form action="{{ route('item.approve', ['id' => $item->id]) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-action btn-action-view bg-success" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('item.destroy', ['id' => $item->id]) }}" 
                           class="btn-action btn-action-delete"
                           title="Delete"
                           onclick="return confirm('Are you sure you want to delete this item?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if (method_exists($items, 'links'))
    <div class="px-4 py-3 border-top">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted text-sm">Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results</span>
            {!! $items->links() !!}
        </div>
    </div>
@endif

<style>
    .bg-success-soft { background-color: rgba(45, 206, 137, 0.1); }
    .bg-warning-soft { background-color: rgba(fb, 99, 64, 0.1); }
</style>