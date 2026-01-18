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
            @if(isset($prefilledData['purchase_id']))
                <input type="hidden" name="purchase_id" value="{{ $prefilledData['purchase_id'] }}">
            @endif
            @if(isset($prefilledData['purchase_item_id']))
                <input type="hidden" name="purchase_item_id" value="{{ $prefilledData['purchase_item_id'] }}">
            @endif
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
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" required value="{{ old('name', $prefilledData['name'] ?? '') }}">
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
                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" id="barcode" value="{{ old('barcode') }}" readonly placeholder="Will be auto-generated from asset tag">
                        <small class="form-text text-muted">Barcode will be automatically generated from the asset tag when created.</small>
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
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3" placeholder="Enter item description...">{{ old('description', $prefilledData['description'] ?? '') }}</textarea>
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
                        <input type="number" step="0.01" name="value" class="form-control @error('value') is-invalid @enderror" id="value" value="{{ old('value', $prefilledData['value'] ?? '') }}">
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
                        <label for="purchased_by" class="form-label">Purchased By</label>
                        <div class="input-group">
                            <select name="purchased_by" class="form-select @error('purchased_by') is-invalid @enderror" id="purchased_by">
                                <option value="">-- Select User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('purchased_by', $prefilledData['purchased_by'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="addUserPurchasedBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
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
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $prefilledData['supplier_id'] ?? $defaultSupplier->id ?? '') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name ?? 'No Name' }}</option>
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
                        <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" id="purchase_date" value="{{ old('purchase_date', $prefilledData['purchase_date'] ?? '') }}">
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="received_by" class="form-label">Received By</label>
                        <div class="input-group">
                            <select name="received_by" class="form-select @error('received_by') is-invalid @enderror" id="received_by">
                                <option value="">-- Select User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('received_by', $prefilledData['received_by'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="addUserReceivedBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
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
                                    {{ $room->name }} - {{ $room->floor->name }}
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
                        <div class="input-group">
                            <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to">
                                <option value="">-- Select User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="addUserAssignedBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
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
                        <textarea name="specifications" class="form-control @error('specifications') is-invalid @enderror" id="specifications" rows="3">{{ old('specifications', $prefilledData['specifications'] ?? '') }}</textarea>
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
                <label for="tracking_mode" class="form-label fw-semibold">Item Creation Mode <span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tracking_mode" id="individual_mode" value="individual" {{ old('tracking_mode', 'individual') == 'individual' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="individual_mode">
                                <i class="fas fa-cube me-2 text-primary"></i>Individual Items
                            </label>
                            <small class="form-text text-muted d-block">Create separate items with unique serial numbers</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tracking_mode" id="bulk_mode" value="bulk" {{ old('tracking_mode') == 'bulk' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="bulk_mode">
                                <i class="fas fa-boxes me-2 text-success"></i>Bulk Items
                            </label>
                            <small class="form-text text-muted d-block">Create one item with specified quantity</small>
                        </div>
                    </div>
                </div>
                @error('tracking_mode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3" id="individual-count-group">
                <label for="individual_count" class="form-label fw-semibold">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>How many individual items to create? <span class="text-danger">*</span>
                </label>
                <input type="number" name="individual_count" id="individual_count" class="form-control @error('individual_count') is-invalid @enderror" min="1" value="{{ old('individual_count', $prefilledData['quantity'] ?? 1) }}">
                <small class="form-text text-muted">Each item will have a unique serial number and asset tag</small>
                @error('individual_count')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3" id="bulk-quantity-group" style="display: none;">
                <label for="quantity" class="form-label fw-semibold">
                    <i class="fas fa-hashtag me-2 text-success"></i>Total Quantity <span class="text-danger">*</span>
                </label>
                <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" value="{{ old('quantity', $prefilledData['quantity'] ?? 1) }}">
                <small class="form-text text-muted">This will create one item record with the specified quantity</small>
                @error('quantity')
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
                <div class="row g-2">
                    <div class="col-md-6">
                        <label for="camera_images" class="form-label fw-semibold">Take Photo (Camera)</label>
                        <input
                            type="file"
                            name="camera_image"
                            id="camera_images"
                            class="form-control"
                            accept="image/*"
                            capture="environment"
                        >
                        <small class="form-text text-muted">On mobile this opens the camera. Captured photos will be added to the upload list.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="images" class="form-label fw-semibold">Upload Images (Files)</label>
                        <input type="file" name="images[]" id="images" class="form-control @error('images') is-invalid @enderror" accept="image/*" multiple>
                        <small class="form-text text-muted">Supported formats: JPG, PNG, GIF, WEBP (Max 10MB per image, Multiple images allowed)</small>
                    </div>
                </div>
                @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @error('images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                <!-- Image Preview Container -->
                <div id="imagePreview" class="mt-3" style="display: none;">
                    <h6 class="text-muted mb-2">Selected Images:</h6>
                    <div id="imagePreviewContainer" class="row"></div>
                </div>
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
                                <label for="modal_supplier_incharge_name" class="form-label">Person in Charge</label>
                                <input type="text" class="form-control" id="modal_supplier_incharge_name" name="incharge_name">
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
                        <label for="modal_floor_serial_number" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="modal_floor_serial_number" value="Auto-generated" readonly>
                        <div class="form-text">Serial number will be automatically generated when the floor is created.</div>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold text-white" id="addUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>
                    Add New User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm" onsubmit="return false;">
                <div class="modal-body">
                    <div id="userModalAlert" class="alert" style="display: none;"></div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_user_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_user_name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_user_email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="modal_user_email" name="email" placeholder="Leave empty to auto-generate">
                                <small class="form-text text-muted">If left empty, email will be auto-generated from name</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_user_designation_id" class="form-label">Designation <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" id="modal_user_designation_id" name="designation_id" required>
                                <option value="">-- Select Designation --</option>
                                @foreach(\App\Models\Designation::with('department')->get() as $designation)
                                    <option value="{{ $designation->id }}">{{ $designation->name }} ({{ $designation->department->name }})</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="createDesignationBtn" data-bs-toggle="modal" data-bs-target="#createDesignationModal">
                                <i class="fas fa-plus"></i> Create New
                            </button>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- Hidden fields for auto-generated values -->
                    <input type="hidden" id="modal_user_password" name="password" value="">
                    <input type="hidden" id="modal_user_role" name="role" value="employee">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveUserBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Add User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Designation Modal -->
<div class="modal fade" id="createDesignationModal" tabindex="-1" aria-labelledby="createDesignationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold text-white" id="createDesignationModalLabel">
                    <i class="fas fa-plus me-2"></i>
                    Create New Designation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createDesignationForm" onsubmit="return false;">
                <div class="modal-body">
                    <div id="designationModalAlert" class="alert" style="display: none;"></div>
                    
                    <div class="mb-3">
                        <label for="new_designation_name" class="form-label">Designation Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="new_designation_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_designation_department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="new_designation_department_input" placeholder="Type department name or select from list" autocomplete="off">
                            <input type="hidden" id="new_designation_department_id" name="department_id">
                            <div class="dropdown-menu w-100" id="departmentDropdown" style="display: none; position: absolute; z-index: 1000;">
                                @foreach(\App\Models\Department::all() as $department)
                                    <a class="dropdown-item" href="#" data-id="{{ $department->id }}" data-name="{{ $department->name }}">{{ $department->name }}</a>
                                @endforeach
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-primary" href="#" id="createNewDepartment">
                                    <i class="fas fa-plus me-1"></i>Create new department
                                </a>
                            </div>
                        </div>
                        <small class="form-text text-muted">Type to search existing departments or create a new one</small>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="saveDesignationBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Create Designation</span>
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
    
    /* Radio button styling */
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .form-check-input:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-check {
        padding: 1rem;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: #fff;
    }
    
    .form-check:hover {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }
    
    .form-check-input:checked + .form-check-label {
        color: #0d6efd;
    }
    
    .form-check-input:checked ~ .form-check {
        border-color: #0d6efd;
        background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
    }
    
    /* Quantity group styling */
    #individual-count-group, #bulk-quantity-group {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    #individual-count-group {
        border-color: #0d6efd;
        background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
    }
    
    #bulk-quantity-group {
        border-color: #198754;
        background: linear-gradient(135deg, #f0fff4 0%, #e8f5e8 100%);
    }
    
    /* Icon styling */
    .form-label i {
        font-size: 1.1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const individualMode = document.getElementById('individual_mode');
        const bulkMode = document.getElementById('bulk_mode');
        const quantityField = document.getElementById('quantity');
        const individualCountField = document.getElementById('individual_count');
        const individualCountGroup = document.getElementById('individual-count-group');
        const bulkQuantityGroup = document.getElementById('bulk-quantity-group');
        
        function toggleTrackingFields() {
            if (bulkMode.checked) {
                // Show bulk quantity field, hide individual count
                individualCountGroup.style.display = 'none';
                bulkQuantityGroup.style.display = 'block';
                quantityField.disabled = false;
                quantityField.required = true;
                individualCountField.disabled = true;
                individualCountField.required = false;
            } else {
                // Show individual count field, hide bulk quantity
                individualCountGroup.style.display = 'block';
                bulkQuantityGroup.style.display = 'none';
                individualCountField.disabled = false;
                individualCountField.required = true;
                quantityField.disabled = true;
                quantityField.required = false;
            }
        }
        
        individualMode.addEventListener('change', toggleTrackingFields);
        bulkMode.addEventListener('change', toggleTrackingFields);
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
                    depreciationNote.textContent = `Depreciation cost will be automatically calculated: ৳${depreciationCost.toFixed(2)}`;
                    depreciationNote.style.display = 'block';
                } else {
                    depreciationNote.style.display = 'none';
                }
            }
        }
        
        valueField.addEventListener('input', calculateDepreciationCost);
        depreciationRateField.addEventListener('input', calculateDepreciationCost);

        // Room filtering functionality
        const floorSelect = document.getElementById('floor_level');
        const roomSelect = document.getElementById('room_number');
        
        // Store all rooms data for filtering
        const allRooms = [
            @foreach($rooms as $room)
            {
                id: {{ $room->id }},
                name: {!! json_encode($room->name) !!},
                floor_name: {!! json_encode($room->floor->name) !!},
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
                    option.textContent = `${room.name} - ${room.floor_name}`;
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
                option.textContent = `${room.name} - ${room.floor_name}`;
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

        // User Modal functionality
        const addUserForm = document.getElementById('addUserForm');
        const userModal = document.getElementById('addUserModal');
        const saveUserBtn = document.getElementById('saveUserBtn');
        const userModalAlert = document.getElementById('userModalAlert');
        
        // Get all user select elements
        const purchasedBySelect = document.getElementById('purchased_by');
        const receivedBySelect = document.getElementById('received_by');
        const assignedToSelect = document.getElementById('assigned_to');
        
        // Track which dropdown triggered the modal
        let currentTriggeredSelect = null;

        // Track which dropdown triggered the modal
        document.addEventListener('click', function(e) {
            if (e.target.closest('[data-bs-target="#addUserModal"]')) {
                const button = e.target.closest('[data-bs-target="#addUserModal"]');
                const inputGroup = button.closest('.input-group');
                if (inputGroup) {
                    currentTriggeredSelect = inputGroup.querySelector('select');
                }
            }
        });

        // Reset user modal form when modal is closed
        userModal.addEventListener('hidden.bs.modal', function() {
            addUserForm.reset();
            addUserForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addUserForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            userModalAlert.style.display = 'none';
        });

        // Auto-generate password and set role when modal is shown
        userModal.addEventListener('show.bs.modal', function() {
            // Generate a random password
            const password = generateRandomPassword();
            document.getElementById('modal_user_password').value = password;
            
            // Set role to employee
            document.getElementById('modal_user_role').value = 'employee';
        });

        // Function to generate random password
        function generateRandomPassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return password;
        }

        // Multiple image upload handling
        const imageInput = document.getElementById('images');
        const cameraInput = document.getElementById('camera_images');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const maxFileSize = 10 * 1024 * 1024; // 10MB in bytes
        const maxImages = 10;

        function addFilesToImagesInput(newFiles) {
            if (!imageInput || !newFiles || newFiles.length === 0) return;

            const existingFiles = Array.from(imageInput.files || []);
            const combined = existingFiles.concat(Array.from(newFiles));

            if (combined.length > maxImages) {
                alert(`You can upload up to ${maxImages} images. You already selected ${existingFiles.length}.`);
                return;
            }

            const dt = new DataTransfer();
            combined.forEach((file) => dt.items.add(file));
            imageInput.files = dt.files;

            // Trigger change event to run validation + refresh preview
            imageInput.dispatchEvent(new Event('change'));
        }

        // Camera capture → merge into existing multi-file input
        if (cameraInput) {
            cameraInput.addEventListener('change', function (e) {
                addFilesToImagesInput(e.target.files);
                // Clear camera input to avoid submitting duplicates
                cameraInput.value = '';
            });
        }

        imageInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            imagePreviewContainer.innerHTML = '';
            
            if (files.length === 0) {
                imagePreview.style.display = 'none';
                return;
            }
            if (files.length > maxImages) {
                alert(`You can upload up to ${maxImages} images.`);
                e.target.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            // Validate file sizes
            const oversizedFiles = files.filter(file => file.size > maxFileSize);
            if (oversizedFiles.length > 0) {
                alert(`The following files exceed 10MB limit:\n${oversizedFiles.map(f => f.name).join('\n')}`);
                e.target.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            // Validate file types
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            const invalidFiles = files.filter(file => !allowedTypes.includes(file.type));
            if (invalidFiles.length > 0) {
                alert(`The following files have invalid formats:\n${invalidFiles.map(f => f.name).join('\n')}\n\nSupported formats: JPG, PNG, GIF, WEBP`);
                e.target.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            // Show previews
            imagePreview.style.display = 'block';
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-2';
                    
                    const card = document.createElement('div');
                    card.className = 'card position-relative';
                    card.style.height = '150px';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'card-img-top';
                    img.style.height = '120px';
                    img.style.objectFit = 'cover';
                    
                    const cardBody = document.createElement('div');
                    cardBody.className = 'card-body p-2';
                    
                    const fileName = document.createElement('small');
                    fileName.className = 'text-muted';
                    fileName.textContent = file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name;
                    
                    const fileSize = document.createElement('small');
                    fileSize.className = 'text-muted d-block';
                    fileSize.textContent = formatFileSize(file.size);
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-danger position-absolute';
                    removeBtn.style.top = '5px';
                    removeBtn.style.right = '5px';
                    removeBtn.innerHTML = '×';
                    removeBtn.onclick = function() {
                        removeImageFromPreview(index);
                    };
                    
                    cardBody.appendChild(fileName);
                    cardBody.appendChild(fileSize);
                    card.appendChild(img);
                    card.appendChild(cardBody);
                    card.appendChild(removeBtn);
                    col.appendChild(card);
                    imagePreviewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function removeImageFromPreview(index) {
            const dt = new DataTransfer();
            const input = document.getElementById('images');
            const files = Array.from(input.files);
            
            files.forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            input.files = dt.files;
            
            // Trigger change event to update preview
            input.dispatchEvent(new Event('change'));
        }

        // Handle user form submission
        function handleUserSubmit() {
            // Show loading state
            const spinner = saveUserBtn.querySelector('.spinner-border');
            const btnText = saveUserBtn.querySelector('.btn-text');
            spinner.classList.remove('d-none');
            btnText.textContent = 'Adding...';
            saveUserBtn.disabled = true;

            // Clear previous errors
            addUserForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addUserForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            userModalAlert.style.display = 'none';

            // Prepare form data
            const formData = new FormData(addUserForm);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Submit via AJAX
            fetch('{{ route("user-management.storeAjax") }}', {
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
                    // Add new user to all user dropdowns
                    const newOption = document.createElement('option');
                    newOption.value = data.user.id;
                    newOption.textContent = data.user.name;
                    
                    purchasedBySelect.appendChild(newOption.cloneNode(true));
                    receivedBySelect.appendChild(newOption.cloneNode(true));
                    assignedToSelect.appendChild(newOption.cloneNode(true));
                    
                    // Select the new user in the dropdown that triggered the modal
                    if (currentTriggeredSelect) {
                        currentTriggeredSelect.value = data.user.id;
                        // Trigger change event to update any dependent fields
                        currentTriggeredSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    
                    // Show success message
                    userModalAlert.className = 'alert alert-success';
                    userModalAlert.textContent = 'User added successfully!';
                    userModalAlert.style.display = 'block';
                    
                    // Close modal after a short delay
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(userModal);
                        modal.hide();
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = addUserForm.querySelector(`[name="${field}"]`);
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
                        userModalAlert.className = 'alert alert-danger';
                        userModalAlert.textContent = data.message || 'An error occurred while adding the user.';
                        userModalAlert.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                userModalAlert.className = 'alert alert-danger';
                userModalAlert.textContent = 'An error occurred while adding the user: ' + error.message;
                userModalAlert.style.display = 'block';
            })
            .finally(() => {
                // Reset loading state
                spinner.classList.add('d-none');
                btnText.textContent = 'Add User';
                saveUserBtn.disabled = false;
            });
        }

        // Handle user form submission
        addUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleUserSubmit();
        });

        // Handle user button click
        saveUserBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleUserSubmit();
        });

        // Designation Modal functionality
        const createDesignationForm = document.getElementById('createDesignationForm');
        const designationModal = document.getElementById('createDesignationModal');
        const saveDesignationBtn = document.getElementById('saveDesignationBtn');
        const designationModalAlert = document.getElementById('designationModalAlert');
        const designationSelect = document.getElementById('modal_user_designation_id');
        const departmentSelect = document.getElementById('new_designation_department_id');

        // Custom department selection functionality
        const departmentInput = document.getElementById('new_designation_department_input');
        const departmentHidden = document.getElementById('new_designation_department_id');
        const departmentDropdown = document.getElementById('departmentDropdown');
        const createNewDepartmentBtn = document.getElementById('createNewDepartment');
        let isNewDepartment = false;

        // Show dropdown when input is focused
        departmentInput.addEventListener('focus', function() {
            departmentDropdown.style.display = 'block';
            filterDepartments();
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#new_designation_department_input') && !e.target.closest('#departmentDropdown')) {
                departmentDropdown.style.display = 'none';
            }
        });

        // Filter departments as user types
        departmentInput.addEventListener('input', function() {
            filterDepartments();
            isNewDepartment = true; // Assume new department when typing
        });

        // Handle department selection
        departmentDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            if (e.target.closest('#createNewDepartment')) {
                // User wants to create new department
                isNewDepartment = true;
                departmentHidden.value = '';
                departmentDropdown.style.display = 'none';
            } else if (e.target.classList.contains('dropdown-item') && e.target.dataset.id) {
                // User selected existing department
                const selectedId = e.target.dataset.id;
                const selectedName = e.target.dataset.name;
                departmentInput.value = selectedName;
                departmentHidden.value = selectedId;
                isNewDepartment = false;
                departmentDropdown.style.display = 'none';
            }
        });

        // Filter departments based on input
        function filterDepartments() {
            const inputValue = departmentInput.value.toLowerCase();
            const departmentItems = departmentDropdown.querySelectorAll('.dropdown-item[data-id]');
            
            departmentItems.forEach(item => {
                const departmentName = item.dataset.name.toLowerCase();
                if (departmentName.includes(inputValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Reset designation modal form when modal is closed
        designationModal.addEventListener('hidden.bs.modal', function() {
            createDesignationForm.reset();
            departmentInput.value = '';
            departmentHidden.value = '';
            departmentDropdown.style.display = 'none';
            isNewDepartment = false;
            createDesignationForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            createDesignationForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            designationModalAlert.style.display = 'none';
        });

        // Handle designation form submission
        function handleDesignationSubmit() {
            // Show loading state
            const spinner = saveDesignationBtn.querySelector('.spinner-border');
            const btnText = saveDesignationBtn.querySelector('.btn-text');
            spinner.classList.remove('d-none');
            btnText.textContent = 'Creating...';
            saveDesignationBtn.disabled = true;

            // Clear previous errors
            createDesignationForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            createDesignationForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            designationModalAlert.style.display = 'none';

            // Get department values
            const departmentName = departmentInput.value.trim();
            const departmentId = departmentHidden.value;

            // Validate department input
            if (!departmentName) {
                departmentInput.classList.add('is-invalid');
                const feedback = departmentInput.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = 'Please enter a department name';
                }
                return;
            }

            // Prepare form data
            const formData = new FormData(createDesignationForm);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Set department values
            if (isNewDepartment || !departmentId) {
                formData.set('department_id', '');
                formData.append('department_name', departmentName);
            } else {
                formData.set('department_id', departmentId);
            }

            // Submit via AJAX
            fetch('{{ route("designation.storeAjax") }}', {
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
                    // Add new designation to the select dropdown
                    const newOption = document.createElement('option');
                    newOption.value = data.designation.id;
                    newOption.textContent = data.designation.name + ' (' + data.designation.department_name + ')';
                    designationSelect.appendChild(newOption);
                    
                    // Select the new designation
                    designationSelect.value = data.designation.id;
                    
                    // Show success message
                    designationModalAlert.className = 'alert alert-success';
                    designationModalAlert.textContent = 'Designation created successfully!';
                    designationModalAlert.style.display = 'block';
                    
                    // Close modal after a short delay
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(designationModal);
                        modal.hide();
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = createDesignationForm.querySelector(`[name="${field}"]`);
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
                        designationModalAlert.className = 'alert alert-danger';
                        designationModalAlert.textContent = data.message || 'An error occurred while creating the designation.';
                        designationModalAlert.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                designationModalAlert.className = 'alert alert-danger';
                designationModalAlert.textContent = 'An error occurred while creating the designation: ' + error.message;
                designationModalAlert.style.display = 'block';
            })
            .finally(() => {
                // Reset loading state
                spinner.classList.add('d-none');
                btnText.textContent = 'Create Designation';
                saveDesignationBtn.disabled = false;
            });
        }

        // Handle designation form submission
        createDesignationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleDesignationSubmit();
        });

        // Handle designation button click
        saveDesignationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleDesignationSubmit();
        });

        // Auto-generate email when name changes
        document.getElementById('modal_user_name').addEventListener('input', function() {
            const emailField = document.getElementById('modal_user_email');
            if (!emailField.value) {
                const name = this.value.toLowerCase().replace(/\s+/g, '.');
                const generatedEmail = name + '@company.com';
                emailField.value = generatedEmail;
            }
        });


    });
</script>
@endpush
@endsection
