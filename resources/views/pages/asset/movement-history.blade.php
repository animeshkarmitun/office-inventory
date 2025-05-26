@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container">
    <h1 class="text-decoration-underline">Movement History for {{ $item->name }}</h1>
    <a href="{{ route('item') }}" class="btn btn-secondary mt-3">Back to Assets</a>

    <table class="table mt-3">
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
            @foreach ($item->movements as $movement)
            <tr>
                <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $movement->movement_type }}</td>
                <td>{{ $movement->fromUser->name ?? 'N/A' }}</td>
                <td>{{ $movement->toUser->name ?? 'N/A' }}</td>
                <td>{{ $movement->from_location ?? 'N/A' }}</td>
                <td>{{ $movement->to_location ?? 'N/A' }}</td>
                <td>{{ $movement->notes ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 