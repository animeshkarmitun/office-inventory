@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-decoration-underline mb-4">Purchase Details</h1>
    <a href="{{ route('purchase.index') }}" class="btn btn-secondary mb-3">Back to Purchases</a>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">
                Purchase: {{ $purchase->purchase_number ?? 'PUR-' . $purchase->id }}
                <span class="badge bg-success ms-2">Created</span>
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="card-text mb-1"><strong>Supplier:</strong> {{ $purchase->supplier ? $purchase->supplier->name : '-' }}</p>
                    <p class="card-text mb-1"><strong>Invoice Number:</strong> {{ $purchase->invoice_number }}</p>
                    <p class="card-text mb-1"><strong>Purchase Date:</strong> {{ $purchase->purchase_date->format('d-M-Y') }}</p>
                    <p class="card-text mb-1"><strong>Total Value:</strong> <span class="text-success fw-bold">${{ number_format($purchase->total_value, 2) }}</span></p>
                </div>
                <div class="col-md-6">
                    <p class="card-text mb-1"><strong>Department:</strong> {{ $purchase->department ? $purchase->department->name . ' (Level ' . $purchase->department->location . ')' : '-' }}</p>
                    <p class="card-text mb-1"><strong>Purchased By:</strong> {{ $purchase->purchasedBy ? $purchase->purchasedBy->name . ' (' . $purchase->purchasedBy->email . ')' : '-' }}</p>
                    <p class="card-text mb-1"><strong>Received By:</strong> {{ $purchase->receivedBy ? $purchase->receivedBy->name . ' (' . $purchase->receivedBy->email . ')' : '-' }}</p>
                    @if($purchase->invoice_image)
                        <p class="card-text mb-1"><strong>Main Invoice Image:</strong><br>
                            <a href="{{ asset('storage/' . $purchase->invoice_image) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Main Invoice</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <h4>Purchased Items</h4>
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Type/Model</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->items as $item)
            <tr>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->item_type }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($purchase->images->count() > 0)
        <div class="mt-4">
            <h4>Invoice Images ({{ $purchase->images->count() }} images)</h4>
            <div class="row">
                @foreach($purchase->images as $image)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="{{ $image->image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Invoice Image" data-bs-toggle="modal" data-bs-target="#imageModal{{ $image->id }}">
                            <div class="card-body p-2">
                                <h6 class="card-title text-truncate" title="{{ $image->original_name }}">{{ $image->original_name }}</h6>
                                <small class="text-muted">{{ $image->file_size_human }}</small>
                                <div class="mt-2">
                                    <a href="{{ $image->image_url }}" target="_blank" class="btn btn-sm btn-outline-primary">View Full Size</a>
                                    <form method="POST" action="{{ route('purchase.deleteImage', [$purchase->id, $image->id]) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this image?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Modal -->
                    <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $image->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imageModalLabel{{ $image->id }}">{{ $image->original_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ $image->image_url }}" class="img-fluid" alt="Invoice Image">
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ $image->image_url }}" target="_blank" class="btn btn-primary">View Full Size</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection 