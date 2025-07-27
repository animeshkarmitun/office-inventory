@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-decoration-underline mb-4">Purchases</h1>
    <a href="{{ route('purchase.create') }}" class="btn btn-primary mb-3">Add Purchase</a>
    @include('inc.alert')
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Total Value</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
            <tr>
                <td>{{ $purchase->id }}</td>
                <td>{{ $purchase->supplier ? $purchase->supplier->name : '-' }}</td>
                <td>{{ $purchase->invoice_number }}</td>
                <td>{{ $purchase->purchase_date ? $purchase->purchase_date->format('d-M-Y') : '-' }}</td>
                <td>{{ number_format($purchase->total_value, 2) }}</td>
                <td>
                    <a href="{{ route('purchase.show', $purchase->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('purchase.edit', $purchase->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST" style="display:inline-block" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm btn-delete-modal" data-id="{{ $purchase->id }}">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this purchase?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let formToDelete = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.querySelectorAll('.btn-delete-modal').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            formToDelete = btn.closest('form');
            deleteModal.show();
        });
    });
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (formToDelete) {
            formToDelete.submit();
        }
    });
});
</script>
@endpush
@endsection 