@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-decoration-underline mb-4">Edit Purchase</h1>
    <a href="{{ route('purchase.index') }}" class="btn btn-secondary mb-3">Back to Purchases</a>
    <form action="{{ route('purchase.update', $purchase->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                <select name="supplier_id" id="supplier_id" class="form-select" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $supplier->id == $purchase->supplier_id ? 'selected' : '' }}>{{ $supplier->name ?? 'No Name' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="invoice_number" class="form-label">Invoice Number <span class="text-danger">*</span></label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ $purchase->invoice_number }}" required>
            </div>
            <div class="col-md-3">
                <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ $purchase->purchase_date->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-3">
                <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department_id" id="department_id" class="form-select" required>
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ $department->id == $purchase->department_id ? 'selected' : '' }}>{{ $department->name }} (Level {{ $department->location }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="purchased_by" class="form-label">Purchased By <span class="text-danger">*</span></label>
                <select name="purchased_by" id="purchased_by" class="form-select" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $purchase->purchased_by ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="received_by" class="form-label">Received By <span class="text-danger">*</span></label>
                <select name="received_by" id="received_by" class="form-select" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $purchase->received_by ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="invoice_image" class="form-label">Invoice Image (webp/pdf)</label>
                <input type="file" name="invoice_image" id="invoice_image" class="form-control" accept="image/webp, image/jpeg, image/png, application/pdf">
                @if($purchase->invoice_image)
                    <div class="mt-2">
                        <strong>Current Invoice:</strong> <a href="{{ asset('storage/' . $purchase->invoice_image) }}" target="_blank">View Invoice</a>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <label for="total_value" class="form-label">Total Value (auto-calculated)</label>
                <input type="text" name="total_value" id="total_value" class="form-control" value="{{ number_format($purchase->total_value, 2) }}" readonly>
            </div>
        </div>
        <h4 class="mt-4">Purchased Items</h4>
        <table class="table table-bordered align-middle" id="items-table">
            <thead>
                <tr>
                    <th>Item Name <span class="text-danger">*</span></th>
                    <th>Type/Model</th>
                    <th>Quantity <span class="text-danger">*</span></th>
                    <th>Unit Price <span class="text-danger">*</span></th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $idx => $item)
                <tr>
                    <td><input type="text" name="items[{{ $idx }}][item_name]" class="form-control" value="{{ $item->item_name }}" required></td>
                    <td><input type="text" name="items[{{ $idx }}][item_type]" class="form-control" value="{{ $item->item_type }}"></td>
                    <td><input type="number" name="items[{{ $idx }}][quantity]" class="form-control item-qty" min="1" value="{{ $item->quantity }}" required></td>
                    <td><input type="number" name="items[{{ $idx }}][unit_price]" class="form-control item-price" min="0" step="0.01" value="{{ $item->unit_price }}" required></td>
                    <td><input type="text" class="form-control item-subtotal" value="{{ number_format($item->quantity * $item->unit_price, 2) }}" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-remove-row">Remove</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" class="btn btn-secondary mb-3" id="add-row">Add Item</button>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Update Purchase</button>
        </div>
    </form>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIdx = {{ count($purchase->items) }};
    function recalcTotals() {
        let total = 0;
        document.querySelectorAll('#items-table tbody tr').forEach(function(row) {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const subtotal = qty * price;
            row.querySelector('.item-subtotal').value = subtotal.toFixed(2);
            total += subtotal;
        });
        document.getElementById('total_value').value = total.toFixed(2);
    }
    document.getElementById('add-row').addEventListener('click', function() {
        const tbody = document.querySelector('#items-table tbody');
        const newRow = tbody.rows[0].cloneNode(true);
        Array.from(newRow.querySelectorAll('input')).forEach(input => {
            input.value = '';
            input.name = input.name.replace(/\d+/, rowIdx);
        });
        tbody.appendChild(newRow);
        rowIdx++;
    });
    document.querySelector('#items-table').addEventListener('input', function(e) {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) {
            recalcTotals();
        }
    });
    document.querySelector('#items-table').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-row')) {
            const row = e.target.closest('tr');
            if (document.querySelectorAll('#items-table tbody tr').length > 1) {
                row.remove();
                recalcTotals();
            }
        }
    });
    recalcTotals();
});
</script>
@endpush
@endsection 