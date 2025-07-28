@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Purchases from <span class="fw-bold">{{ $supplier->name }}</span></h2>
    <div class="card">
        <div class="card-body">
            @if($purchases->isEmpty())
                <p>No purchases found for this supplier.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice Number</th>
                                <th>Date</th>
                                <th>Total Value</th>
                                <th>Supplied Items</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->invoice_number }}</td>
                                <td>{{ $purchase->purchase_date ? $purchase->purchase_date->format('d-M-Y') : '-' }}</td>
                                <td>{{ number_format($purchase->total_value, 2) }}</td>
                                <td>
                                    @if($purchase->items->isEmpty())
                                        <em>No items</em>
                                    @else
                                        <ul class="mb-0">
                                            @foreach($purchase->items as $item)
                                                <li>{{ $item->item_name }} (x{{ $item->quantity }}) @if($item->unit_price) - {{ number_format($item->unit_price, 2) }} each @endif</li>
                                            @endforeach
                                        </ul>
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
    <a href="{{ route('purchase.index') }}" class="btn btn-secondary mt-4">Back to Purchases</a>
</div>
@endsection 