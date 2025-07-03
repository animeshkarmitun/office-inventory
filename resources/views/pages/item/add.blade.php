@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container">
    <h1 class="text-decoration-underline">Add New Item</h1>
    <a href="{{ route('item') }}" class="btn btn-secondary mt-3">Back to Items</a>

    <div class="border border-dark mt-3 p-3">
        <form action="{{ route('item.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <h3>Basic Information</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Item Name</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control" id="serial_number">
                    </div>
                    <div class="mb-3">
                        <label for="asset_tag" class="form-label">Asset Tag</label>
                        <input type="text" name="asset_tag" class="form-control" id="asset_tag">
                    </div>
                    <div class="mb-3">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" name="barcode" class="form-control" id="barcode">
                    </div>
                    <div class="mb-3">
                        <label for="rfid_tag" class="form-label">RFID Tag</label>
                        <input type="text" name="rfid_tag" class="form-control" id="rfid_tag">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <h3>Asset Details</h3>
                    <div class="mb-3">
                        <label for="asset_type" class="form-label">Asset Type</label>
                        <select name="asset_type" class="form-select" id="asset_type" required>
                            <option value="fixed">Fixed Asset</option>
                            <option value="current">Current Asset</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" step="0.01" name="value" class="form-control" id="value">
                    </div>
                    <div class="mb-3">
                        <label for="depreciation_cost" class="form-label">Depreciation Cost</label>
                        <input type="number" step="0.01" name="depreciation_cost" class="form-control" id="depreciation_cost">
                    </div>
                    <div class="mb-3">
                        <label for="depreciation_method" class="form-label">Depreciation Method</label>
                        <select name="depreciation_method" class="form-select" id="depreciation_method">
                            <option value="">-- Select Method --</option>
                            <option value="straight_line">Straight Line</option>
                            <option value="reducing_balance">Reducing Balance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="depreciation_rate" class="form-label">Depreciation Rate (%)</label>
                        <input type="number" step="0.01" name="depreciation_rate" class="form-control" id="depreciation_rate">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" id="status" required>
                            <option value="available">Available</option>
                            <option value="in_use">In Use</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="not_traceable">Not Traceable</option>
                            <option value="disposed">Disposed</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h3>Purchase Information</h3>
                    <div class="mb-3">
                        <label for="purchased_by" class="form-label">Purchased By</label>
                        <select name="purchased_by" class="form-select" id="purchased_by">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" id="supplier_id">
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" id="purchase_date">
                    </div>
                    <div class="mb-3">
                        <label for="received_by" class="form-label">Received By</label>
                        <select name="received_by" class="form-select" id="received_by">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <h3>Location Information</h3>
                    <div class="mb-3">
                        <label for="floor_level" class="form-label">Floor Level</label>
                        <input type="text" name="floor_level" class="form-control" id="floor_level" required>
                    </div>
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Room Number</label>
                        <input type="text" name="room_number" class="form-control" id="room_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Additional Location Details</label>
                        <input type="text" name="location" class="form-control" id="location">
                    </div>
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select" id="assigned_to">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h3>Specifications</h3>
                    <div id="specifications-container">
                        <div class="mb-3 specification-row">
                            <div class="input-group">
                                <input type="text" name="specifications[]" class="form-control" placeholder="Enter specification">
                                <button type="button" class="btn btn-danger remove-specification">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-specification">Add Specification</button>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" id="remarks" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('specifications-container');
        const addButton = document.getElementById('add-specification');
        const maxSpecifications = 6;

        addButton.addEventListener('click', function() {
            const rows = container.getElementsByClassName('specification-row');
            if (rows.length < maxSpecifications) {
                const newRow = document.createElement('div');
                newRow.className = 'mb-3 specification-row';
                newRow.innerHTML = `
                    <div class="input-group">
                        <input type="text" name="specifications[]" class="form-control" placeholder="Enter specification">
                        <button type="button" class="btn btn-danger remove-specification">Remove</button>
                    </div>
                `;
                container.appendChild(newRow);
            }
            if (rows.length >= maxSpecifications) {
                addButton.disabled = true;
            }
        });

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-specification')) {
                e.target.closest('.specification-row').remove();
                addButton.disabled = false;
            }
        });
    });
</script>
@endpush
@endsection
