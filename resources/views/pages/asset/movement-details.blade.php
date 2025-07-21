@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container">
    <h1 class="text-decoration-underline">Movement Details</h1>
    <a href="{{ route('item') }}" class="btn btn-secondary mt-3">Back to Assets</a>

    <div class="border border-dark mt-3 p-3">
        <div class="row">
            <div class="col-md-6">
                <h3>Asset Information</h3>
                <p><strong>Asset Name:</strong> {{ $movement->item ? $movement->item->name : 'N/A' }}</p>
                <p><strong>Asset Tag:</strong> {{ $movement->item->asset_tag ?? 'N/A' }}</p>
                <p><strong>Serial Number:</strong> {{ $movement->item->serial_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h3>Movement Information</h3>
                <p><strong>Movement Type:</strong> {{ ucfirst($movement->movement_type) }}</p>
                <p><strong>Date:</strong> {{ $movement->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>Moved By:</strong> {{ $movement->movedBy ? $movement->movedBy->name : 'N/A' }}</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h3>From</h3>
                <p><strong>User:</strong> {{ $movement->fromUser->name ?? 'N/A' }}</p>
                <p><strong>Location:</strong> {{ $movement->from_location ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h3>To</h3>
                <p><strong>User:</strong> {{ $movement->toUser->name ?? 'N/A' }}</p>
                <p><strong>Location:</strong> {{ $movement->to_location }}</p>
            </div>
        </div>

        @if($movement->notes)
        <div class="row mt-4">
            <div class="col-12">
                <h3>Notes</h3>
                <p>{{ $movement->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 