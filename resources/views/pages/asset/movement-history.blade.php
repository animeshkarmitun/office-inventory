@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Movement History</h2>
            <p class="page-subtitle">Tracking audit trail for: <span class="text-primary fw-bold">{{ $item->name }}</span></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('asset.movement.create', $item->id) }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
                <i class="fas fa-exchange-alt me-2"></i> Move Asset
            </a>
            <a href="{{ route('item') }}" class="btn btn-outline-secondary rounded-3 px-3 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Back to Assets
            </a>
        </div>
    </div>

    <div class="card card-table shadow-sm border-0 mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable {{ request('sort') == 'created_at' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Date & Time
                        </th>
                        <th class="sortable {{ request('sort') == 'movement_type' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'movement_type', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Type
                        </th>
                        <th>From User</th>
                        <th>To User</th>
                        <th class="sortable {{ request('sort') == 'from_location' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'from_location', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            From Location
                        </th>
                        <th class="sortable {{ request('sort') == 'to_location' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'to_location', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            To Location
                        </th>
                        <th>Moved By</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($movements->currentPage() - 1) * $movements->perPage() + $loop->iteration }}</span></td>
                        <td><span class="text-sm">{{ $movement->created_at->format('d M, Y H:i') }}</span></td>
                        <td>
                            <span class="badge 
                                @if($movement->movement_type === 'assignment') bg-primary-soft text-primary
                                @elseif($movement->movement_type === 'transfer') bg-warning-soft text-warning
                                @elseif($movement->movement_type === 'location_change') bg-info-soft text-info
                                @elseif($movement->movement_type === 'return') bg-success-soft text-success
                                @elseif($movement->movement_type === 'maintenance') bg-danger-soft text-danger
                                @else bg-secondary-soft text-secondary
                                @endif px-3">
                                {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                            </span>
                        </td>
                        <td><span class="text-sm">{{ $movement->fromUser->name ?? 'N/A' }}</span></td>
                        <td><span class="text-sm">{{ $movement->toUser->name ?? 'N/A' }}</span></td>
                        <td><span class="text-sm text-muted">{!! $movement->from_location ?? 'N/A' !!}</span></td>
                        <td><span class="text-sm fw-bold">{!! $movement->to_location ?? 'N/A' !!}</span></td>
                        <td><span class="text-xs">{{ $movement->movedBy->name ?? 'System' }}</span></td>
                        <td><span class="text-xs text-muted">{{ Str::limit($movement->notes, 30) }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <p class="text-muted mb-0">No movement history found for this asset.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (method_exists($movements, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $movements->firstItem() ?? 0 }} to {{ $movements->lastItem() ?? 0 }} of {{ $movements->total() }} results</span>
                    {!! $movements->links() !!}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(94, 114, 228, 0.1); }
    .bg-warning-soft { background-color: rgba(fb, 99, 64, 0.1); }
    .bg-info-soft { background-color: rgba(17, 201, 240, 0.1); }
    .bg-success-soft { background-color: rgba(45, 206, 137, 0.1); }
    .bg-danger-soft { background-color: rgba(245, 54, 92, 0.1); }
    .bg-secondary-soft { background-color: rgba(136, 152, 170, 0.1); }
</style>
@endsection