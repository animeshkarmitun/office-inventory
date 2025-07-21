@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-decoration-underline mb-4">Add Purchase</h1>
    @include('inc.alert')
    <form action="#" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                <select name="supplier_id" id="supplier_id" class="form-select" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name ?? 'No Name' }}</option>
                    @endforeach
                </select>
                {{-- @error('supplier_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror --}}
            </div>
            <div class="col-md-4">
                <label for="invoice_number" class="form-label">Invoice Number <span class="text-danger">*</span></label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control" required>
                {{-- @error('invoice_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror --}}
            </div>
            <div class="col-md-4">
                <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                <input type="date" name="purchase_date" id="purchase_date" class="form-control" required>
                {{-- @error('purchase_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror --}}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="invoice_image" class="form-label">Invoice Image (webp/pdf)</label>
                <input type="file" name="invoice_image" id="invoice_image" class="form-control" accept="image/webp, image/jpeg, image/png, application/pdf">
                {{-- @error('invoice_image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror --}}
            </div>
            <div class="col-md-6">
                <label for="total_value" class="form-label">Total Value (auto-calculated)</label>
                <input type="text" name="total_value" id="total_value" class="form-control" readonly>
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
                <tr>
                    <td><input type="text" name="items[0][item_name]" class="form-control" required></td>
                    <td><input type="text" name="items[0][item_type]" class="form-control"></td>
                    <td><input type="number" name="items[0][quantity]" class="form-control item-qty" min="1" required></td>
                    <td><input type="number" name="items[0][unit_price]" class="form-control item-price" min="0" step="0.01" required></td>
                    <td><input type="text" class="form-control item-subtotal" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-remove-row">Remove</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-secondary mb-3" id="add-row">Add Item</button>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Save Purchase</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIdx = 1;
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
});
</script>
@endpush
@endsection 