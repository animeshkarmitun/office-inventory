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
                        <p class="card-text mb-1"><strong>Invoice Image:</strong><br>
                            <a href="{{ asset('storage/' . $purchase->invoice_image) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Invoice</a>
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
</div>
@endsection 