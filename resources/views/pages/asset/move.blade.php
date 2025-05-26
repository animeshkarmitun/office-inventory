@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container">
    <h1 class="text-decoration-underline">Move Asset: {{ $item->name }}</h1>
    <a href="{{ route('item') }}" class="btn btn-secondary mt-3">Back to Assets</a>

    <div class="border border-dark mt-3 p-3">
        <form action="{{ route('asset.movement.store', ['id' => $item->id]) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="movement_type" class="form-label">Movement Type</label>
                <select class="form-select" name="movement_type" id="movement_type" required>
                    <option value="assignment">Assignment</option>
                    <option value="transfer">Transfer</option>
                    <option value="return">Return</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="to_user_id" class="form-label">To User</label>
                <select class="form-select" name="to_user_id" id="to_user_id">
                    <option value="">-- None --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="to_location" class="form-label">To Location</label>
                <input type="text" name="to_location" class="form-control" id="to_location" required>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" class="form-control" id="notes"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection 