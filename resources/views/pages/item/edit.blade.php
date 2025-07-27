@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container">
    <h1 class="text-decoration-underline">Edit Item</h1>
    <a href="{{ route('item') }}" class="btn btn-secondary mt-3">Back to Items</a>

    <div class="border border-dark mt-3 p-3">
        <form action="{{ route('item.update', ['id' => $item->id]) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <h3>Basic Information</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $item->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control" id="serial_number" value="{{ $item->serial_number }}" readonly>
                        <small class="form-text text-muted">Serial number is auto-generated and cannot be changed.</small>
                    </div>
                    <div class="mb-3">
                        <label for="asset_tag" class="form-label">Asset Tag</label>
                        <input type="text" name="asset_tag" class="form-control" id="asset_tag" value="{{ $item->asset_tag }}" readonly>
                        <small class="form-text text-muted">Asset tag is auto-generated and cannot be changed.</small>
                    </div>
                    <div class="mb-3">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" name="barcode" class="form-control" id="barcode" value="{{ $item->barcode }}">
                    </div>
                    <div class="mb-3">
                        <label for="rfid_tag" class="form-label">RFID Tag</label>
                        <input type="text" name="rfid_tag" class="form-control" id="rfid_tag" value="{{ $item->rfid_tag }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3">{{ $item->description }}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <h3>Asset Details</h3>
                    <div class="mb-3">
                        <label for="asset_type" class="form-label">Asset Type <span class="text-danger">*</span></label>
                        <select name="asset_type" class="form-select" id="asset_type" required>
                            <option value="fixed" {{ $item->asset_type === 'fixed' ? 'selected' : '' }}>Fixed Asset</option>
                            <option value="current" {{ $item->asset_type === 'current' ? 'selected' : '' }}>Current Asset</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" step="0.01" name="value" class="form-control" id="value" value="{{ $item->value }}">
                    </div>

                    <div class="mb-3">
                        <label for="depreciation_method" class="form-label">Depreciation Method</label>
                        <select name="depreciation_method" class="form-select" id="depreciation_method">
                            <option value="">-- Select Method --</option>
                            <option value="straight_line" {{ ($item->depreciation_method === 'straight_line' || !$item->depreciation_method) ? 'selected' : '' }}>Straight Line</option>
                            <option value="reducing_balance" {{ $item->depreciation_method === 'reducing_balance' ? 'selected' : '' }}>Reducing Balance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="depreciation_rate" class="form-label">Depreciation Rate (%)</label>
                        <input type="number" step="0.01" name="depreciation_rate" class="form-control" id="depreciation_rate" value="{{ $item->depreciation_rate }}">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" id="status" required>
                            <option value="available" {{ $item->status === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="in_use" {{ $item->status === 'in_use' ? 'selected' : '' }}>In Use</option>
                            <option value="maintenance" {{ $item->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="not_traceable" {{ $item->status === 'not_traceable' ? 'selected' : '' }}>Not Traceable</option>
                            <option value="disposed" {{ $item->status === 'disposed' ? 'selected' : '' }}>Disposed</option>
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
                                <option value="{{ $user->id }}" {{ $item->purchased_by === $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select" id="supplier_id" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $supplier->id == ($item->supplier_id ?? $defaultSupplier->id) ? 'selected' : '' }}>{{ $supplier->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" id="purchase_date" value="{{ $item->purchase_date ? $item->purchase_date->format('Y-m-d') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="received_by" class="form-label">Received By</label>
                        <select name="received_by" class="form-select" id="received_by">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $item->received_by === $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <h3>Location Information</h3>
                    <div class="mb-3">
                        <label for="floor_level" class="form-label">Floor Level <span class="text-danger">*</span></label>
                        <input type="text" name="floor_level" class="form-control" id="floor_level" value="{{ $item->floor_level }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                        <input type="text" name="room_number" class="form-control" id="room_number" value="{{ $item->room_number }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Additional Location Details</label>
                        <input type="text" name="location" class="form-control" id="location" value="{{ $item->location }}">
                    </div>
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select" id="assigned_to">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $item->assigned_to === $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h3>Specifications</h3>
                    <div class="mb-3">
                        <label for="specifications" class="form-label">Specifications</label>
                        <textarea name="specifications" class="form-control" id="specifications" rows="3">{{ $item->specifications }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" id="remarks" rows="3">{{ $item->remarks }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
</div>


@endsection
