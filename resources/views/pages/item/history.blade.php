@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Item History: <span class="fw-bold">{{ $item->name }}</span></h2>
    
    <div class="row">
        <!-- Item Image Section -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Item Image</h6>
                </div>
                <div class="card-body text-center">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" 
                             alt="Image of {{ $item->name }}" 
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 300px; max-width: 100%; object-fit: contain;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="placeholder-image" style="display: none;">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px; border: 2px dashed #dee2e6;">
                                <div class="text-center">
                                    <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                    <p class="text-muted mb-2">Image not available</p>
                                    <small class="text-muted">{{ $item->name }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $item->image) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt"></i> View Full Size
                            </a>
                        </div>
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px; border: 2px dashed #dee2e6;">
                            <div class="text-center">
                                <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                <p class="text-muted mb-2">No image uploaded</p>
                                <small class="text-muted">{{ $item->name }}</small>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                <i class="fas fa-image"></i> No Image Available
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Item Details Section -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Current Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Serial Number:</strong>
                                        <br><span class="text-muted">{{ $item->serial_number }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Asset Tag:</strong>
                                        <br><span class="text-muted">{{ $item->asset_tag ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Current Floor:</strong>
                                        <br><span class="text-muted">{{ $item->floor->name ?? $item->floor_level ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Current Room:</strong>
                                        <br><span class="text-muted">{{ $item->room->name ?? $item->room_number ?? 'N/A' }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Condition:</strong>
                                        <br><span class="text-muted">{{ $item->condition ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Assigned User:</strong>
                                        <br><span class="text-muted">{{ $item->assignedUser->name ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Status:</strong>
                                        <br>
                                        <span class="badge bg-{{ $item->status == 'available' ? 'success' : ($item->status == 'in_use' ? 'primary' : ($item->status == 'maintenance' ? 'warning' : ($item->status == 'disposed' ? 'danger' : 'secondary'))) }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Value:</strong>
                                        <br><span class="text-muted">{{ $item->value ? '$' . number_format($item->value, 2) : 'N/A' }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Movement History</h5>
            @if($movements->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No movement history found for this item.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
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
                                <td>
                                    <small class="text-muted">{{ $move->created_at->format('M d, Y') }}</small><br>
                                    <strong>{{ $move->created_at->format('H:i') }}</strong>
                                </td>
                                <td>{{ $move->fromUser->name ?? 'N/A' }}</td>
                                <td>{{ $move->toUser->name ?? 'N/A' }}</td>
                                <td>{{ $move->from_location ?? 'N/A' }}</td>
                                <td>{{ $move->to_location ?? 'N/A' }}</td>
                                <td>{{ $move->movedBy->name ?? 'N/A' }}</td>
                                <td>
                                    @if($move->notes)
                                        <span class="text-muted">{{ Str::limit($move->notes, 50) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <a href="{{ route('item') }}" class="btn btn-secondary mt-4">
        <i class="fas fa-arrow-left"></i> Back to Items
    </a>
</div>
@endsection 