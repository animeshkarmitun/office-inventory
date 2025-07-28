@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Borrower History: <span class="fw-bold">{{ $borrower->name }}</span></h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Borrowed Items</h5>
            @if($history->isEmpty())
                <p>No borrowing history found for this borrower.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Borrow Date</th>
                                <th>Return Date</th>
                                <th>Condition</th>
                                <th>Department</th>
                                <th>Authorized By</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($history as $record)
                            <tr>
                                <td>{{ $record->item->name ?? 'N/A' }}</td>
                                <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($record->status == 0 && $record->updated_at != $record->created_at)
                                        {{ $record->updated_at->format('Y-m-d H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $record->item->condition ?? 'N/A' }}</td>
                                <td>{{ $record->department->name ?? 'N/A' }}</td>
                                <td>{{ $record->user->name ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <a href="{{ route('borrower') }}" class="btn btn-secondary mt-4">Back to Borrowers</a>
</div>
@endsection 