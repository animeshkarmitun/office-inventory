@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-decoration-underline mb-4">Add Purchase</h1>
    @include('inc.alert')
    <form action="{{ route('purchase.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name ?? 'No Name' }}</option>
                    @endforeach
                </select>
                @error('supplier_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label for="invoice_number" class="form-label">Invoice Number <span class="text-danger">*</span></label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" value="{{ old('invoice_number') }}" required>
                @error('invoice_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                <input type="date" name="purchase_date" id="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date') }}" required>
                @error('purchase_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }} (Level {{ $department->location }})</option>
                    @endforeach
                </select>
                @error('department_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="purchased_by" class="form-label">Purchased By <span class="text-danger">*</span></label>
                <select name="purchased_by" id="purchased_by" class="form-select @error('purchased_by') is-invalid @enderror" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('purchased_by') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('purchased_by') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label for="received_by" class="form-label">Received By <span class="text-danger">*</span></label>
                <select name="received_by" id="received_by" class="form-select @error('received_by') is-invalid @enderror" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('received_by') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('received_by') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                <span class="btn-text">Save Purchase</span>
            </button>
            <div class="text-center">
                <small class="text-muted">All items will be automatically added to inventory</small>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Auto-fill received_by with purchased_by when purchased_by changes
document.getElementById('purchased_by').addEventListener('change', function() {
    const purchasedBy = this.value;
    const receivedBy = document.getElementById('received_by');
    if (purchasedBy && !receivedBy.value) {
        receivedBy.value = purchasedBy;
    }
});

// Calculate subtotals and total
function calculateSubtotals() {
    let total = 0;
    document.querySelectorAll('#items-table tbody tr').forEach(function(row) {
        const quantity = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const subtotal = quantity * price;
        row.querySelector('.item-subtotal').value = subtotal.toFixed(2);
        total += subtotal;
    });
    document.getElementById('total_value').value = total.toFixed(2);
}

// Add event listeners for quantity and price changes
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) {
        calculateSubtotals();
    }
});

// Add row functionality
document.getElementById('add-row').addEventListener('click', function() {
    const tbody = document.querySelector('#items-table tbody');
    const rowCount = tbody.children.length;
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="text" name="items[${rowCount}][item_name]" class="form-control" required></td>
        <td><input type="text" name="items[${rowCount}][item_type]" class="form-control"></td>
        <td><input type="number" name="items[${rowCount}][quantity]" class="form-control item-qty" min="1" required></td>
        <td><input type="number" name="items[${rowCount}][unit_price]" class="form-control item-price" min="0" step="0.01" required></td>
        <td><input type="text" class="form-control item-subtotal" readonly></td>
        <td><button type="button" class="btn btn-danger btn-remove-row">Remove</button></td>
    `;
    tbody.appendChild(newRow);
    
    // Add event listeners to new row
    newRow.querySelector('.item-qty').addEventListener('input', calculateSubtotals);
    newRow.querySelector('.item-price').addEventListener('input', calculateSubtotals);
    newRow.querySelector('.btn-remove-row').addEventListener('click', function() {
        newRow.remove();
        calculateSubtotals();
    });
});

// Remove row functionality
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove-row')) {
        e.target.closest('tr').remove();
        calculateSubtotals();
    }
});

// Initialize calculations
calculateSubtotals();

// Form submission loading state
document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submit-btn');
    const spinner = submitBtn.querySelector('.spinner-border');
    const btnText = submitBtn.querySelector('.btn-text');
    
    submitBtn.disabled = true;
    spinner.classList.remove('d-none');
    btnText.textContent = 'Creating Purchase...';
});

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