@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Item History: <span class="fw-bold">{{ $item->name }}</span></h2>
        <div class="btn-group">
            <a href="{{ route('item.history.pdf', $item->id) }}" class="btn btn-primary">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
            <a href="{{ route('item') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Items
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Item Image Section -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Item Image</h6>
                </div>
                <div class="card-body text-center">
                    @if($item->images && $item->images->count() > 0)
                        <!-- Display multiple images -->
                        <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($item->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div style="max-height: 300px; max-width: 100%; overflow: hidden;">
                                            @if($image->image_path && file_exists(storage_path('app/public/' . $image->image_path)))
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     alt="Image of {{ $item->name }}" 
                                                     class="img-fluid rounded shadow-sm"
                                                     style="max-height: 300px; max-width: 100%; object-fit: contain;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px; border: 2px dashed #dee2e6;">
                                                    <div class="text-center">
                                                        <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                                        <p class="text-muted mb-2">Image not found</p>
                                                        <small class="text-muted">{{ $image->original_name ?? 'Unknown' }}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($item->images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                        
                        <div class="mt-2">
                            @if($item->images->first() && $item->images->first()->image_path && file_exists(storage_path('app/public/' . $item->images->first()->image_path)))
                                <a href="{{ asset('storage/' . $item->images->first()->image_path) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i> View Full Size
                                </a>
                            @endif
                            <small class="text-muted d-block mt-1">{{ $item->images->count() }} image(s) available</small>
                        </div>
                    @elseif($item->image && \App\Helpers\ImageHelper::imageExists($item->image))
                        <!-- Display legacy single image -->
                        <div style="max-height: 300px; max-width: 100%; overflow: hidden;">
                            {!! \App\Helpers\ImageHelper::responsiveImage($item->image, 'Image of ' . $item->name, [
                                'class' => 'img-fluid rounded shadow-sm',
                                'style' => 'max-height: 300px; max-width: 100%; object-fit: contain;'
                            ]) !!}
                        </div>
                        <div class="mt-2">
                            <a href="{{ \App\Helpers\ImageHelper::getOriginalUrl($item->image) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt"></i> View Full Size
                            </a>
                        </div>
                    @else
                        <!-- No images available -->
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
                    <h5 class="card-title">Item Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Item Name:</strong>
                                        <br><span class="text-muted">{{ $item->name }}</span>
                                    </div>
                                </li>
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
                                        <strong>Barcode:</strong>
                                        @php
                                            $barcodeValue = $item->barcode ?: $item->asset_tag;
                                        @endphp
                                        @if($barcodeValue)
                                            <br><span class="text-muted">{{ $barcodeValue }}</span>
                                            <div class="mt-2">
                                                <canvas id="barcodeCanvas" style="width: 200px; height: 60px;"></canvas>
                                                <br>
                                                <button class="btn btn-sm btn-outline-primary mt-2" onclick="downloadBarcode()" title="Download Barcode">
                                                    <i class="fas fa-download me-1"></i>Download Barcode
                                                </button>
                                            </div>
                                        @else
                                            <br><span class="text-muted">N/A</span>
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>RFID Tag:</strong>
                                        <br><span class="text-muted">{{ $item->rfid_tag ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Category:</strong>
                                        <br><span class="text-muted">{{ $item->category->name ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Asset Type:</strong>
                                        <br><span class="text-muted">{{ $item->asset_type ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Description:</strong>
                                        <br><span class="text-muted">{{ $item->description ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Specifications:</strong>
                                        <br><span class="text-muted">{{ $item->specifications ?? 'N/A' }}</span>
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
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Quantity:</strong>
                                        <br><span class="text-muted">{{ $item->quantity ?? 'N/A' }}</span>
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
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Location:</strong>
                                        <br><span class="text-muted">{{ $item->location ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Remarks:</strong>
                                        <br><span class="text-muted">{{ $item->remarks ?? 'N/A' }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase Information Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Purchase Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Supplier:</strong>
                                        <br><span class="text-muted">{{ $item->supplier->name ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Purchase Date:</strong>
                                        <br><span class="text-muted">{{ $item->purchase_date ? $item->purchase_date->format('M d, Y') : 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Purchased By:</strong>
                                        <br><span class="text-muted">{{ $item->purchasedBy->name ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Received By:</strong>
                                        <br><span class="text-muted">{{ $item->receivedBy->name ?? 'N/A' }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Depreciation Method:</strong>
                                        <br><span class="text-muted">{{ $item->depreciation_method ? ucfirst(str_replace('_', ' ', $item->depreciation_method)) : 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Depreciation Rate:</strong>
                                        <br><span class="text-muted">{{ $item->depreciation_rate ? $item->depreciation_rate . '%' : 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Depreciation Cost:</strong>
                                        <br><span class="text-muted">{{ $item->depreciation_cost ? '$' . number_format($item->depreciation_cost, 2) : 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Current Book Value:</strong>
                                        <br><span class="text-muted">{{ $item->currentBookValue() ? '$' . number_format($item->currentBookValue(), 2) : 'N/A' }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Information Section -->
    @if($item->is_approved || $item->approved_by)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Approval Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Approval Status:</strong>
                                        <br>
                                        <span class="badge bg-{{ $item->is_approved ? 'success' : 'warning' }}">
                                            {{ $item->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Approved By:</strong>
                                        <br><span class="text-muted">{{ $item->approvedBy->name ?? 'N/A' }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Approved At:</strong>
                                        <br><span class="text-muted">{{ $item->approved_at ? $item->approved_at->format('M d, Y H:i') : 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>Tracking Mode:</strong>
                                        <br><span class="text-muted">{{ $item->tracking_mode ? ucfirst(str_replace('_', ' ', $item->tracking_mode)) : 'N/A' }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
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
</div>

<!-- Print Styles -->
<style>
@media print {
    .btn-group, .btn, .no-print {
        display: none !important;
    }
    
    .container {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        break-inside: avoid;
    }
    
    .table {
        font-size: 12px !important;
    }
    
    .table th, .table td {
        border: 1px solid #000 !important;
        padding: 4px !important;
    }
    
    h2, h5, h6 {
        color: #000 !important;
    }
    
    .text-muted {
        color: #666 !important;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
    }
    
    .bg-success {
        background-color: #d4edda !important;
    }
    
    .bg-primary {
        background-color: #cce7ff !important;
    }
    
    .bg-warning {
        background-color: #fff3cd !important;
    }
    
    .bg-danger {
        background-color: #f8d7da !important;
    }
    
    .bg-secondary {
        background-color: #e2e3e5 !important;
    }
}
</style>
@endsection

@push('scripts')
<script>
    // Barcode functionality for history page
    function generateBarcode() {
        const barcodeValue = '{{ $barcodeValue }}';
        if (!barcodeValue) {
            return;
        }

        try {
            JsBarcode(document.getElementById('barcodeCanvas'), barcodeValue, {
                format: "CODE128",
                width: 2,
                height: 60,
                displayValue: true,
                fontSize: 14,
                margin: 5
            });
        } catch (error) {
            console.error('Error generating barcode:', error);
        }
    }

    function downloadBarcode() {
        const canvas = document.getElementById('barcodeCanvas');
        const link = document.createElement('a');
        link.download = `barcode_{{ $barcodeValue }}.png`;
        link.href = canvas.toDataURL();
        link.click();
    }

    // Generate barcode when page loads
    document.addEventListener('DOMContentLoaded', function() {
        generateBarcode();
    });
</script>
@endpush 