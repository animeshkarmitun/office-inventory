@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-decoration-underline mb-4">Purchase Details</h1>
    <a href="{{ route('purchase.index') }}" class="btn btn-secondary mb-3">Back to Purchases</a>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Supplier: {{ $purchase->supplier ? $purchase->supplier->name : '-' }}</h5>
            <p class="card-text mb-1"><strong>Invoice Number:</strong> {{ $purchase->invoice_number }}</p>
            <p class="card-text mb-1"><strong>Purchase Date:</strong> {{ $purchase->purchase_date->format('d-M-Y') }}</p>
            <p class="card-text mb-1"><strong>Total Value:</strong> {{ number_format($purchase->total_value, 2) }}</p>
            @if($purchase->invoice_image)
                <p class="card-text mb-1"><strong>Invoice Image:</strong><br>
                    <a href="{{ asset('storage/' . $purchase->invoice_image) }}" target="_blank">View Invoice</a>
                </p>
            @endif
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