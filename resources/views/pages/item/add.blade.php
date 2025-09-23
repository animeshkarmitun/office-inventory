@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Add New Item</h1>
                    <p class="text-muted mb-0">Create a new inventory item with detailed information</p>
                </div>
                <a href="{{ route('item') }}" class="btn btn-outline-secondary d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Items
                </a>
            </div>
        </div>
    </div>

    {{-- Error summary --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-3 text-danger"></i>
                <div>
            <strong>There were some problems with your submission:</strong>
                    <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
        <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 bg-light">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="fw-bold text-primary mb-0 d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                Basic Information
                            </h4>
                        </div>
                        <div class="card-body pt-3">
                    <div class="mb-3">
                        <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" required value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">Item Name is required. Please enter a value.</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" id="serial_number" value="{{ old('serial_number') }}" readonly placeholder="Will be auto-generated">
                        <small class="form-text text-muted">Serial number will be automatically generated when the item is created.</small>
                        @error('serial_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="asset_tag" class="form-label">Asset Tag</label>
                        <input type="text" name="asset_tag" class="form-control @error('asset_tag') is-invalid @enderror" id="asset_tag" value="{{ old('asset_tag') }}" readonly placeholder="Will be auto-generated">
                        <small class="form-text text-muted">Asset tag will be automatically generated when the item is created.</small>
                        @error('asset_tag')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" id="barcode" value="{{ old('barcode') }}">
                        @error('barcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="rfid_tag" class="form-label">RFID Tag</label>
                        <input type="text" name="rfid_tag" class="form-control @error('rfid_tag') is-invalid @enderror" id="rfid_tag" value="{{ old('rfid_tag') }}">
                        @error('rfid_tag')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3" placeholder="Enter item description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 bg-light">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="fw-bold text-success mb-0 d-flex align-items-center">
                                <i class="fas fa-cube me-2"></i>
                                Asset Details
                            </h4>
                        </div>
                        <div class="card-body pt-3">
                    <div class="mb-3">
                        <label for="asset_type" class="form-label">Asset Type <span class="text-danger">*</span></label>
                        <select name="asset_type" class="form-select @error('asset_type') is-invalid @enderror" id="asset_type" required>
                            <option value="fixed" {{ old('asset_type') == 'fixed' ? 'selected' : '' }}>Fixed Asset</option>
                            <option value="current" {{ old('asset_type') == 'current' ? 'selected' : '' }}>Current Asset</option>
                        </select>
                        @error('asset_type')
                            <div class="invalid-feedback">Asset Type is required. Please select a value.</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" step="0.01" name="value" class="form-control @error('value') is-invalid @enderror" id="value" value="{{ old('value') }}">
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="depreciation_method" class="form-label">Depreciation Method</label>
                        <select name="depreciation_method" class="form-select @error('depreciation_method') is-invalid @enderror" id="depreciation_method">
                            <option value="straight_line" {{ old('depreciation_method', 'straight_line') == 'straight_line' ? 'selected' : '' }}>Straight Line</option>
                            <option value="reducing_balance" {{ old('depreciation_method') == 'reducing_balance' ? 'selected' : '' }}>Reducing Balance</option>
                        </select>
                        @error('depreciation_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="depreciation_rate" class="form-label">Depreciation Rate (%)</label>
                        <input type="number" step="0.01" name="depreciation_rate" class="form-control @error('depreciation_rate') is-invalid @enderror" id="depreciation_rate" value="{{ old('depreciation_rate') }}">
                        <small class="form-text text-muted" id="depreciation-note" style="display: none;"></small>
                        @error('depreciation_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" id="status" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="not_traceable" {{ old('status') == 'not_traceable' ? 'selected' : '' }}>Not Traceable</option>
                            <option value="disposed" {{ old('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">Status is required. Please select a value.</div>
                        @enderror
                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-lg-6">
                    <div class="card border-0 bg-light">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="fw-bold text-warning mb-0 d-flex align-items-center">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Purchase Information
                            </h4>
                        </div>
                        <div class="card-body pt-3">
                    <div class="mb-3">
                        <label for="invoice_number" class="form-label">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" value="{{ old('invoice_number') }}" placeholder="Enter invoice number if this is a new purchase">
                        <small class="form-text text-muted">Leave blank if this item was purchased previously</small>
                        @error('invoice_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="purchased_by" class="form-label">Purchased By</label>
                        <select name="purchased_by" class="form-select @error('purchased_by') is-invalid @enderror" id="purchased_by">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('purchased_by') == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                        @error('purchased_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <div class="input-group">
                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $defaultSupplier->id ?? '') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                            <button type="button" class="btn btn-outline-primary" id="addSupplierBtn" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                        <small class="form-text text-muted">Default Supplier is pre-selected. You can change it to the actual supplier if needed.</small>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" id="purchase_date" value="{{ old('purchase_date') }}">
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="received_by" class="form-label">Received By</label>
                        <select name="received_by" class="form-select @error('received_by') is-invalid @enderror" id="received_by">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('received_by') == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                        @error('received_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 bg-light">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="fw-bold text-info mb-0 d-flex align-items-center">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Location Information
                            </h4>
                        </div>
                        <div class="card-body pt-3">
                    <div class="mb-3">
                        <label for="floor_level" class="form-label">Floor Level <span class="text-danger">*</span></label>
                        <div class="input-group">
                        <select name="floor_level" class="form-select @error('floor_level') is-invalid @enderror" id="floor_level" required>
                            <option value="">-- Select Floor --</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->name }}" {{ old('floor_level') == $floor->name ? 'selected' : '' }}>
                                    {{ $floor->name }} ({{ $floor->serial_number }})
                                </option>
                            @endforeach
                        </select>
                            <button type="button" class="btn btn-outline-primary" id="addFloorBtn" data-bs-toggle="modal" data-bs-target="#addFloorModal">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                        @error('floor_level')
                            <div class="invalid-feedback">Floor Level is required. Please select a value.</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                        <select name="room_number" class="form-select @error('room_number') is-invalid @enderror" id="room_number" required>
                                <option value="">-- Select Floor First --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->name }}" {{ old('room_number') == $room->name ? 'selected' : '' }}>
                                    {{ $room->name }} ({{ $room->room_number }}) - {{ $room->floor->name }}
                                </option>
                            @endforeach
                        </select>
                            <button type="button" class="btn btn-outline-primary" id="addRoomBtn" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Rooms will be filtered based on the selected floor level
                        </small>
                        @error('room_number')
                            <div class="invalid-feedback">Room Number is required. Please select a value.</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Additional Location Details</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" id="location" value="{{ old('location') }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="fw-bold text-secondary mb-0 d-flex align-items-center">
                                <i class="fas fa-cogs me-2"></i>
                                Specifications & Additional Details
                            </h4>
                        </div>
                        <div class="card-body pt-3">
                    <div class="mb-3">
                        <label for="specifications" class="form-label">Specifications</label>
                        <textarea name="specifications" class="form-control @error('specifications') is-invalid @enderror" id="specifications" rows="3">{{ old('specifications') }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

                    <div class="mb-3">
                                <label for="remarks" class="form-label fw-semibold">Remarks</label>
                                <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" id="remarks" rows="3" placeholder="Enter any additional remarks...">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-lg-6">
                    <div class="card border-0 bg-light">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center">
                                <i class="fas fa-tags me-2"></i>
                                Tracking & Quantity
                            </h4>
                        </div>
                        <div class="card-body pt-3">
            <div class="mb-3">
                                <label for="tracking_mode" class="form-label fw-semibold">Tracking Mode <span class="text-danger">*</span></label>
                <select name="tracking_mode" id="tracking_mode" class="form-select @error('tracking_mode') is-invalid @enderror" required>
                    <option value="individual" {{ old('tracking_mode') == 'individual' ? 'selected' : '' }}>Individual</option>
                    <option value="bulk" {{ old('tracking_mode') == 'bulk' ? 'selected' : '' }}>Bulk</option>
                </select>
                @error('tracking_mode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                                <label for="quantity" class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" value="{{ old('quantity', 1) }}">
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3" id="individual-count-group" style="display: none;">
                                <label for="individual_count" class="form-label fw-semibold">How many items to create? <span class="text-danger">*</span></label>
                <input type="number" name="individual_count" id="individual_count" class="form-control @error('individual_count') is-invalid @enderror" min="1" value="{{ old('individual_count', 1) }}">
                @error('individual_count')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                            </div>
                        </div>
                    </div>
            </div>

                <div class="col-lg-6">
                    <div class="card border-0 bg-light">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center">
                                <i class="fas fa-image me-2"></i>
                                Item Image
                            </h4>
                        </div>
                        <div class="card-body pt-3">
            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">Upload Image</label>
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                <small class="form-text text-muted">Supported formats: JPG, PNG, GIF (Max 5MB)</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('item') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-5 d-flex align-items-center">
                            <i class="fas fa-save me-2"></i>
                            Create Item
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold text-white" id="addSupplierModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add New Supplier
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSupplierForm" onsubmit="return false;">
                <div class="modal-body">
                    <div id="supplierModalAlert" class="alert" style="display: none;"></div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_supplier_name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_supplier_name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_supplier_incharge_name" class="form-label">Person in Charge <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_supplier_incharge_name" name="incharge_name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_supplier_contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_supplier_contact_number" name="contact_number" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_supplier_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="modal_supplier_email" name="email">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_supplier_address" class="form-label">Address</label>
                        <textarea class="form-control" id="modal_supplier_address" name="address" rows="2"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_supplier_tax_number" class="form-label">Tax Number</label>
                                <input type="text" class="form-control" id="modal_supplier_tax_number" name="tax_number">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_supplier_payment_terms" class="form-label">Payment Terms</label>
                                <input type="text" class="form-control" id="modal_supplier_payment_terms" name="payment_terms">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_supplier_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="modal_supplier_notes" name="notes" rows="2"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSupplierBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Add Supplier</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Floor Modal -->
<div class="modal fade" id="addFloorModal" tabindex="-1" aria-labelledby="addFloorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold text-white" id="addFloorModalLabel">
                    <i class="fas fa-building me-2"></i>
                    Add New Floor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addFloorForm" onsubmit="return false;">
                <div class="modal-body">
                    <div id="floorModalAlert" class="alert" style="display: none;"></div>
                    
                    <div class="mb-3">
                        <label for="modal_floor_name" class="form-label">Floor Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_floor_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_floor_serial_number" class="form-label">Serial Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_floor_serial_number" name="serial_number" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_floor_description" class="form-label">Description</label>
                        <textarea class="form-control" id="modal_floor_description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveFloorBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Add Floor</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold text-white" id="addRoomModalLabel">
                    <i class="fas fa-door-open me-2"></i>
                    Add New Room
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addRoomForm" onsubmit="return false;">
                <div class="modal-body">
                    <div id="roomModalAlert" class="alert" style="display: none;"></div>
                    
                    <div class="mb-3">
                        <label for="modal_room_floor_id" class="form-label">Floor <span class="text-danger">*</span></label>
                        <select class="form-select" id="modal_room_floor_id" name="floor_id" required>
                            <option value="">-- Select Floor --</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->name }} ({{ $floor->serial_number }})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_room_name" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_room_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_room_number" name="room_number" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_room_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="modal_room_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_room_description" class="form-label">Description</label>
                        <textarea class="form-control" id="modal_room_description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveRoomBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Add Room</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        transform: translateY(-1px);
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .input-group .btn {
        border-radius: 0 8px 8px 0;
    }
    
    .modal-content {
        border-radius: 16px;
    }
    
    .modal-header {
        border-radius: 16px 16px 0 0;
    }
    
    .form-label {
        color: #495057;
        margin-bottom: 8px;
    }
    
    .text-muted {
        font-size: 0.875rem;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .fw-semibold {
        font-weight: 600 !important;
    }
    
    .h2 {
        font-size: 2rem;
    }
    
    .card-header h4 {
        font-size: 1.1rem;
    }
    
    .btn-outline-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
    }
    
    .alert {
        border-radius: 8px;
        border: none;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trackingMode = document.getElementById('tracking_mode');
        const quantityField = document.getElementById('quantity');
        const individualCountGroup = document.getElementById('individual-count-group');
        
        function toggleTrackingFields() {
            if (trackingMode.value === 'bulk') {
                quantityField.disabled = false;
                quantityField.required = true;
                individualCountGroup.style.display = 'none';
            } else {
                quantityField.disabled = true;
                quantityField.required = false;
                individualCountGroup.style.display = '';
            }
        }
        trackingMode.addEventListener('change', toggleTrackingFields);
        toggleTrackingFields();

        // Auto-calculate depreciation cost
        const valueField = document.getElementById('value');
        const depreciationRateField = document.getElementById('depreciation_rate');
        
        function calculateDepreciationCost() {
            const value = parseFloat(valueField.value) || 0;
            const rate = parseFloat(depreciationRateField.value) || 0;
            const depreciationCost = (value * rate) / 100;
            
            const depreciationNote = document.getElementById('depreciation-note');
            if (depreciationNote) {
                if (value > 0 && rate > 0) {
                    depreciationNote.textContent = `Depreciation cost will be automatically calculated: $${depreciationCost.toFixed(2)}`;
                    depreciationNote.style.display = 'block';
                } else {
                    depreciationNote.style.display = 'none';
                }
            }
        }
        
        valueField.addEventListener('input', calculateDepreciationCost);
        depreciationRateField.addEventListener('input', calculateDepreciationCost);

        // Make purchase date conditional based on invoice number
        const invoiceNumberField = document.getElementById('invoice_number');
        const purchaseDateField = document.getElementById('purchase_date');
        
        function togglePurchaseFields() {
            const hasInvoice = invoiceNumberField.value.trim() !== '';
            
            if (hasInvoice) {
                purchaseDateField.required = true;
                purchaseDateField.parentElement.querySelector('.form-label').innerHTML = 'Purchase Date <span class="text-danger">*</span>';
            } else {
                purchaseDateField.required = false;
                purchaseDateField.parentElement.querySelector('.form-label').innerHTML = 'Purchase Date';
            }
        }
        
        invoiceNumberField.addEventListener('input', togglePurchaseFields);
        togglePurchaseFields();

        // Room filtering functionality
        const floorSelect = document.getElementById('floor_level');
        const roomSelect = document.getElementById('room_number');
        
        // Store all rooms data for filtering
        const allRooms = [
            @foreach($rooms as $room)
            {
                id: {{ $room->id }},
                name: "{{ $room->name }}",
                room_number: "{{ $room->room_number }}",
                floor_name: "{{ $room->floor->name }}",
                floor_id: {{ $room->floor_id }}
            }@if(!$loop->last),@endif
            @endforeach
        ];

        // Function to filter rooms based on selected floor
        function filterRoomsByFloor(selectedFloorName) {
            // Clear current room options except the first one
            roomSelect.innerHTML = '<option value="">-- Select Room --</option>';
            
            if (!selectedFloorName) {
                // Show all rooms if no floor is selected
                allRooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.name;
                    option.textContent = `${room.name} (${room.room_number}) - ${room.floor_name}`;
                    roomSelect.appendChild(option);
                });
                return;
            }
            
            // Filter rooms that belong to the selected floor
            const filteredRooms = allRooms.filter(room => room.floor_name === selectedFloorName);
            
            // Add filtered rooms to dropdown
            filteredRooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.name;
                option.textContent = `${room.name} (${room.room_number}) - ${room.floor_name}`;
                roomSelect.appendChild(option);
            });
        }

        // Add event listener for floor level changes
        floorSelect.addEventListener('change', function() {
            const selectedFloor = this.value;
            filterRoomsByFloor(selectedFloor);
        });

        // Initialize room dropdown (start with no rooms until floor is selected)
        roomSelect.innerHTML = '<option value="">-- Select Floor First --</option>';

        // Supplier Modal functionality
        const addSupplierForm = document.getElementById('addSupplierForm');
        const supplierModal = document.getElementById('addSupplierModal');
        const supplierSelect = document.getElementById('supplier_id');
        const saveSupplierBtn = document.getElementById('saveSupplierBtn');
        const supplierModalAlert = document.getElementById('supplierModalAlert');

        // Reset modal form when modal is closed
        supplierModal.addEventListener('hidden.bs.modal', function() {
            addSupplierForm.reset();
            addSupplierForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addSupplierForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            supplierModalAlert.style.display = 'none';
        });

        // Handle supplier form submission
        function handleSupplierSubmit() {
            // Show loading state
            const spinner = saveSupplierBtn.querySelector('.spinner-border');
            const btnText = saveSupplierBtn.querySelector('.btn-text');
            spinner.classList.remove('d-none');
            btnText.textContent = 'Adding...';
            saveSupplierBtn.disabled = true;

            // Clear previous errors
            addSupplierForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addSupplierForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            supplierModalAlert.style.display = 'none';

            // Prepare form data
            const formData = new FormData(addSupplierForm);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Submit via AJAX
            fetch('{{ route("supplier.storeAjax") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new supplier to dropdown
                    const newOption = document.createElement('option');
                    newOption.value = data.supplier.id;
                    newOption.textContent = data.supplier.name;
                    supplierSelect.appendChild(newOption);
                    
                    // Select the new supplier
                    supplierSelect.value = data.supplier.id;
                    
                    // Show success message
                    supplierModalAlert.className = 'alert alert-success';
                    supplierModalAlert.textContent = 'Supplier added successfully!';
                    supplierModalAlert.style.display = 'block';
                    
                    // Close modal after a short delay
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(supplierModal);
                        modal.hide();
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = addSupplierForm.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.parentElement.querySelector('.invalid-feedback');
                                if (feedback) {
                                    feedback.textContent = data.errors[field][0];
                                }
                            }
                        });
                    } else {
                        // Show general error
                        supplierModalAlert.className = 'alert alert-danger';
                        supplierModalAlert.textContent = data.message || 'An error occurred while adding the supplier.';
                        supplierModalAlert.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                supplierModalAlert.className = 'alert alert-danger';
                supplierModalAlert.textContent = 'An error occurred while adding the supplier: ' + error.message;
                supplierModalAlert.style.display = 'block';
            })
            .finally(() => {
                // Reset loading state
                spinner.classList.add('d-none');
                btnText.textContent = 'Add Supplier';
                saveSupplierBtn.disabled = false;
            });
        }

        // Handle form submission
        addSupplierForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleSupplierSubmit();
        });

        // Handle button click
        saveSupplierBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleSupplierSubmit();
        });

        // Floor Modal functionality
        const addFloorForm = document.getElementById('addFloorForm');
        const floorModal = document.getElementById('addFloorModal');
        const saveFloorBtn = document.getElementById('saveFloorBtn');
        const floorModalAlert = document.getElementById('floorModalAlert');
        
        // Also get the room modal's floor dropdown
        const roomModalFloorSelect = document.getElementById('modal_room_floor_id');

        // Reset floor modal form when modal is closed
        floorModal.addEventListener('hidden.bs.modal', function() {
            addFloorForm.reset();
            addFloorForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addFloorForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            floorModalAlert.style.display = 'none';
        });

        // Handle floor form submission
        function handleFloorSubmit() {
            // Show loading state
            const spinner = saveFloorBtn.querySelector('.spinner-border');
            const btnText = saveFloorBtn.querySelector('.btn-text');
            spinner.classList.remove('d-none');
            btnText.textContent = 'Adding...';
            saveFloorBtn.disabled = true;

            // Clear previous errors
            addFloorForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addFloorForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            floorModalAlert.style.display = 'none';

            // Prepare form data
            const formData = new FormData(addFloorForm);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Submit via AJAX
            fetch('{{ route("floor.storeAjax") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new floor to main floor dropdown
                    const newOption = document.createElement('option');
                    newOption.value = data.floor.name;
                    newOption.textContent = data.floor.name + ' (' + data.floor.serial_number + ')';
                    floorSelect.appendChild(newOption);
                    
                    // Add new floor to room modal's floor dropdown
                    const newRoomOption = document.createElement('option');
                    newRoomOption.value = data.floor.id;
                    newRoomOption.textContent = data.floor.name + ' (' + data.floor.serial_number + ')';
                    roomModalFloorSelect.appendChild(newRoomOption);
                    
                    // Select the new floor in main dropdown
                    floorSelect.value = data.floor.name;
                    
                    // Show success message
                    floorModalAlert.className = 'alert alert-success';
                    floorModalAlert.textContent = 'Floor added successfully!';
                    floorModalAlert.style.display = 'block';
                    
                    // Close modal after a short delay
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(floorModal);
                        modal.hide();
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = addFloorForm.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.parentElement.querySelector('.invalid-feedback');
                                if (feedback) {
                                    feedback.textContent = data.errors[field][0];
                                }
                            }
                        });
                    } else {
                        // Show general error
                        floorModalAlert.className = 'alert alert-danger';
                        floorModalAlert.textContent = data.message || 'An error occurred while adding the floor.';
                        floorModalAlert.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                floorModalAlert.className = 'alert alert-danger';
                floorModalAlert.textContent = 'An error occurred while adding the floor: ' + error.message;
                floorModalAlert.style.display = 'block';
            })
            .finally(() => {
                // Reset loading state
                spinner.classList.add('d-none');
                btnText.textContent = 'Add Floor';
                saveFloorBtn.disabled = false;
            });
        }

        // Handle floor form submission
        addFloorForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleFloorSubmit();
        });

        // Handle floor button click
        saveFloorBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleFloorSubmit();
        });

        // Room Modal functionality
        const addRoomForm = document.getElementById('addRoomForm');
        const roomModal = document.getElementById('addRoomModal');
        const saveRoomBtn = document.getElementById('saveRoomBtn');
        const roomModalAlert = document.getElementById('roomModalAlert');

        // Reset room modal form when modal is closed
        roomModal.addEventListener('hidden.bs.modal', function() {
            addRoomForm.reset();
            addRoomForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addRoomForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            roomModalAlert.style.display = 'none';
        });

        // Handle room form submission
        function handleRoomSubmit() {
            // Show loading state
            const spinner = saveRoomBtn.querySelector('.spinner-border');
            const btnText = saveRoomBtn.querySelector('.btn-text');
            spinner.classList.remove('d-none');
            btnText.textContent = 'Adding...';
            saveRoomBtn.disabled = true;

            // Clear previous errors
            addRoomForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addRoomForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            roomModalAlert.style.display = 'none';

            // Prepare form data
            const formData = new FormData(addRoomForm);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Submit via AJAX
            fetch('{{ route("room.storeAjax") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new room to the allRooms array
                    allRooms.push({
                        id: data.room.id,
                        name: data.room.name,
                        room_number: data.room.room_number,
                        floor_name: data.room.floor_name,
                        floor_id: data.room.floor_id
                    });
                    
                    // Refresh room filtering based on current floor selection
                    const currentFloor = floorSelect.value;
                    filterRoomsByFloor(currentFloor);
                    
                    // Select the new room
                    roomSelect.value = data.room.name;
                    
                    // Show success message
                    roomModalAlert.className = 'alert alert-success';
                    roomModalAlert.textContent = 'Room added successfully!';
                    roomModalAlert.style.display = 'block';
                    
                    // Close modal after a short delay
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(roomModal);
                        modal.hide();
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = addRoomForm.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.parentElement.querySelector('.invalid-feedback');
                                if (feedback) {
                                    feedback.textContent = data.errors[field][0];
                                }
                            }
                        });
                    } else {
                        // Show general error
                        roomModalAlert.className = 'alert alert-danger';
                        roomModalAlert.textContent = data.message || 'An error occurred while adding the room.';
                        roomModalAlert.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                roomModalAlert.className = 'alert alert-danger';
                roomModalAlert.textContent = 'An error occurred while adding the room: ' + error.message;
                roomModalAlert.style.display = 'block';
            })
            .finally(() => {
                // Reset loading state
                spinner.classList.add('d-none');
                btnText.textContent = 'Add Room';
                saveRoomBtn.disabled = false;
            });
        }

        // Handle room form submission
        addRoomForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleRoomSubmit();
        });

        // Handle room button click
        saveRoomBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleRoomSubmit();
        });
    });
</script>
@endpush
@endsection
