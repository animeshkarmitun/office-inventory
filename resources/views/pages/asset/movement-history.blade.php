@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container">
    <h1 class="mb-4" style="font-size:2.2rem;font-weight:600;">
        Movement History for 
        <span class="product-name-highlight">{{ $item->name }}</span>
    </h1>
    <a href="{{ route('item') }}" class="btn btn-custom mt-3 mb-4">Back to Assets</a>

    <table class="table custom-table mt-3">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Movement Type</th>
                <th scope="col">From User</th>
                <th scope="col">To User</th>
                <th scope="col">From Location</th>
                <th scope="col">To Location</th>
                <th scope="col">Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($item->movements as $movement)
            <tr>
                <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $movement->movement_type }}</td>
                <td>{{ $movement->fromUser->name ?? 'N/A' }}</td>
                <td>{{ $movement->toUser->name ?? 'N/A' }}</td>
                <td>{{ $movement->from_location ?? 'N/A' }}</td>
                <td>{{ $movement->to_location ?? 'N/A' }}</td>
                <td>{{ $movement->notes ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">No movement history found for this asset.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('styles')
<style>
    .product-name-highlight {
        color: #1976d2;
        background: #e3f0fc;
        padding: 0.2em 0.7em;
        border-radius: 1em;
        font-size: 1.15em;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 1px 4px rgba(25, 118, 210, 0.07);
        display: inline-block;
        margin-left: 0.3em;
    }
    .custom-table {
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .custom-table th {
        background: #f5f7fa;
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #e3e6ea;
        vertical-align: middle;
    }
    .custom-table td {
        vertical-align: middle;
        border-top: 1px solid #e3e6ea;
        padding: 0.75rem 1rem;
    }
    .custom-table tbody tr:nth-child(even) {
        background: #f8fafc;
    }
    .custom-table tbody tr:nth-child(odd) {
        background: #fff;
    }
    .btn-custom {
        background: #1976d2;
        color: #fff;
        border-radius: 24px;
        padding: 0.5em 1.5em;
        font-weight: 500;
        border: none;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(25, 118, 210, 0.08);
    }
    .btn-custom:hover, .btn-custom:focus {
        background: #1256a3;
        color: #fff;
        box-shadow: 0 4px 16px rgba(25, 118, 210, 0.13);
        text-decoration: none;
    }
    .container {
        padding-top: 2.5rem;
        padding-bottom: 2.5rem;
    }
</style>
@endpush 