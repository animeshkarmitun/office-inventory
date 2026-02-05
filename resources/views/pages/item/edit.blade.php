@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Edit Item</h1>
                    <p class="text-muted mb-0">Update inventory item information and details</p>
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
        <form action="{{ route('item.update', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
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
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" required value="{{ old('name', $item->name) }}">
                        @error('name')
                            <div class="invalid-feedback">Item Name is required. Please enter a value.</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company</label>
                        <div class="input-group">
                            <select name="company_id" class="form-select @error('company_id') is-invalid @enderror" id="company_id">
                                <option value="">-- Select Company --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $item->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="addCompanyBtn" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                        @error('company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="rfid_tag" class="form-label">RFID Tag</label>
                        <input type="text" name="rfid_tag" class="form-control @error('rfid_tag') is-invalid @enderror" id="rfid_tag" value="{{ old('rfid_tag', $item->rfid_tag) }}">
                        @error('rfid_tag')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3">{{ old('description', $item->description) }}</textarea>
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
                            <option value="fixed" {{ old('asset_type', $item->asset_type) == 'fixed' ? 'selected' : '' }}>Fixed Asset</option>
                            <option value="current" {{ old('asset_type', $item->asset_type) == 'current' ? 'selected' : '' }}>Current Asset</option>
                        </select>
                        @error('asset_type')
                            <div class="invalid-feedback">Asset Type is required. Please select a value.</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" step="0.01" name="value" class="form-control @error('value') is-invalid @enderror" id="value" value="{{ old('value', $item->value) }}">
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="depreciation_method" class="form-label">Depreciation Method</label>
                        <select name="depreciation_method" class="form-select @error('depreciation_method') is-invalid @enderror" id="depreciation_method">
                            <option value="straight_line" {{ old('depreciation_method', $item->depreciation_method ?: 'straight_line') == 'straight_line' ? 'selected' : '' }}>Straight Line</option>
                            <option value="reducing_balance" {{ old('depreciation_method', $item->depreciation_method) == 'reducing_balance' ? 'selected' : '' }}>Reducing Balance</option>
                        </select>
                        @error('depreciation_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="depreciation_rate" class="form-label">Depreciation Rate (%)</label>
                        <input type="number" step="0.01" name="depreciation_rate" class="form-control @error('depreciation_rate') is-invalid @enderror" id="depreciation_rate" value="{{ old('depreciation_rate', $item->depreciation_rate) }}">
                        <small class="form-text text-muted" id="depreciation-note" style="display: none;"></small>
                        @error('depreciation_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" id="status" required>
                            <option value="available" {{ old('status', $item->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="in_use" {{ old('status', $item->status) == 'in_use' ? 'selected' : '' }}>In Use</option>
                            <option value="maintenance" {{ old('status', $item->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="not_traceable" {{ old('status', $item->status) == 'not_traceable' ? 'selected' : '' }}>Not Traceable</option>
                            <option value="disposed" {{ old('status', $item->status) == 'disposed' ? 'selected' : '' }}>Disposed</option>
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
                                    <option value="{{ $user->id }}" {{ old('purchased_by', $item->purchased_by) == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
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
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $item->supplier_id ?? $defaultSupplier->id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name ?? 'No Name' }}</option>
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
                        <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" id="purchase_date" value="{{ old('purchase_date', $item->purchase_date ? $item->purchase_date->format('Y-m-d') : '') }}">
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
                                    <option value="{{ $user->id }}" {{ old('received_by', $item->received_by) == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
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
                                    <option value="{{ $floor->name }}" {{ old('floor_level', $item->floor_level) == $floor->name ? 'selected' : '' }}>
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
                                    <option value="{{ $room->name }}" {{ old('room_number', $item->room_number) == $room->name ? 'selected' : '' }}>
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
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" id="location" value="{{ old('location', $item->location) }}">
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
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $item->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
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
                        <textarea name="specifications" class="form-control @error('specifications') is-invalid @enderror" id="specifications" rows="3">{{ old('specifications', $item->specifications) }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

                    <div class="mb-3">
                                <label for="remarks" class="form-label fw-semibold">Remarks</label>
                                <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" id="remarks" rows="3" placeholder="Enter any additional remarks...">{{ old('remarks', $item->remarks) }}</textarea>
                        @error('remarks')
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
                            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center">
                                <i class="fas fa-image me-2"></i>
                                Item Images
                            </h4>
                        </div>
                        <div class="card-body pt-3">
                            <!-- Current Images Display -->
                            @if($item->images && $item->images->count() > 0)
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3">Current Images:</h6>
                                    <div class="row">
                                        @foreach($item->images as $image)
                                            <div class="col-md-3 mb-3">
                                                <div class="card position-relative">
                                                    <div class="card-img-top" style="height: 150px; overflow: hidden;">
                                                        @if($image->image_path && file_exists(storage_path('app/public/' . $image->image_path)))
                                                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                                 alt="Item Image" 
                                                                 class="img-fluid" 
                                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                                <i class="fas fa-image fa-2x text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="card-body p-2">
                                                        <div class="d-flex justify-content-center">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-danger remove-image-btn" 
                                                                    data-image-id="{{ $image->id }}"
                                                                    data-image-name="{{ $image->original_name }}"
                                                                    title="Remove this image">
                                                                <i class="fas fa-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($item->image)
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3">Current Image:</h6>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <div class="card position-relative">
                                                <div class="card-img-top" style="height: 150px; overflow: hidden;">
                                                    @if($item->image && file_exists(storage_path('app/public/' . $item->image)))
                                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                                             alt="Item Image" 
                                                             class="img-fluid" 
                                                             style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                            <i class="fas fa-image fa-2x text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-center">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger remove-legacy-image-btn" 
                                                                data-item-id="{{ $item->id }}"
                                                                title="Remove legacy image">
                                                            <i class="fas fa-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- New Images Upload -->
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
                                        <label for="images" class="form-label fw-semibold">Upload New Images (Files)</label>
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
                            Update Item
                        </button>
                    </div>
                </div>
            </div>
        </form>
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
                        <label for="new_designation_department_input" class="form-label">Department <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="new_designation_department_input" 
                               placeholder="Type to search or create new department"
                               autocomplete="off"
                               required>
                        <input type="hidden" id="new_designation_department_id" name="department_id">
                        
                        <!-- Department dropdown -->
                        <div id="departmentDropdown" class="dropdown-menu" style="display: none; width: 100%; max-height: 200px; overflow-y: auto;">
                            @foreach(\App\Models\Department::all() as $dept)
                                <a href="#" class="dropdown-item" data-id="{{ $dept->id }}" data-name="{{ $dept->name }}">
                                    {{ $dept->name }}
                                </a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item text-primary" id="createNewDepartment">
                                <i class="fas fa-plus me-2"></i>Create new department
                            </a>
                        </div>
                        <small class="form-text text-muted">Select from existing or type a new department name</small>
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

<!-- Add Company Modal -->
<div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold text-white" id="addCompanyModalLabel">
                    <i class="fas fa-building me-2"></i>
                    Add New Company
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCompanyForm" onsubmit="return false;">
                <div class="modal-body">
                    <div id="companyModalAlert" class="alert" style="display: none;"></div>
                    
                    <div class="mb-3">
                        <label for="modal_company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_company_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCompanyBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Add Company</span>
                    </button>
                </div>
            </form>
        </div>
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
                                <label for="supplier_name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="supplier_name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_contact_person" class="form-label">Contact Person</label>
                                <input type="text" class="form-control" id="supplier_contact_person" name="contact_person">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="supplier_email" name="email">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="supplier_phone" name="phone">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="supplier_address" class="form-label">Address</label>
                        <textarea class="form-control" id="supplier_address" name="address" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="saveSupplierBtn">
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
            <div class="modal-header bg-info text-white border-0">
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
                        <label for="floor_name" class="form-label">Floor Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="floor_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="floor_serial_number" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="floor_serial_number" name="serial_number">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" id="saveFloorBtn">
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
            <div class="modal-header bg-warning text-white border-0">
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
                        <label for="room_name" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="room_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Room Number</label>
                        <input type="text" class="form-control" id="room_number" name="room_number">
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="room_floor_id" class="form-label">Floor <span class="text-danger">*</span></label>
                        <select class="form-select" id="room_floor_id" name="floor_id" required>
                            <option value="">-- Select Floor --</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->name }} ({{ $floor->serial_number }})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="saveRoomBtn">
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
                        <label for="new_designation_department_input" class="form-label">Department <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="new_designation_department_input" 
                               placeholder="Type to search or create new department"
                               autocomplete="off"
                               required>
                        <input type="hidden" id="new_designation_department_id" name="department_id">
                        
                        <!-- Department dropdown -->
                        <div id="departmentDropdown" class="dropdown-menu" style="display: none; width: 100%; max-height: 200px; overflow-y: auto;">
                            @foreach(\App\Models\Department::all() as $dept)
                                <a href="#" class="dropdown-item" data-id="{{ $dept->id }}" data-name="{{ $dept->name }}">
                                    {{ $dept->name }}
                                </a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item text-primary" id="createNewDepartment">
                                <i class="fas fa-plus me-2"></i>Create new department
                            </a>
                        </div>
                        <small class="form-text text-muted">Select from existing or type a new department name</small>
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                    depreciationNote.textContent = `Depreciation cost will be automatically calculated: à§³${depreciationCost.toFixed(2)}`;
                    depreciationNote.style.display = 'block';
                } else {
                    depreciationNote.style.display = 'none';
                }
            }
        }
        
        if (valueField) {
            valueField.addEventListener('input', calculateDepreciationCost);
        }
        if (depreciationRateField) {
            depreciationRateField.addEventListener('input', calculateDepreciationCost);
        }
        
        // Run calculation on page load
        calculateDepreciationCost();

        // Room filtering functionality
        const floorSelect = document.getElementById('floor_level');
        const roomSelect = document.getElementById('room_number');
        
        // Store the initial room value before any modifications
        const initialRoomValue = roomSelect.value;
        
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
        function filterRoomsByFloor(selectedFloorName, preserveRoomValue = null) {
            // Use the preserved value if provided, otherwise get current selection
            const currentRoomValue = preserveRoomValue !== null ? preserveRoomValue : roomSelect.value;
            
            // Clear current room options except the first one
            roomSelect.innerHTML = '<option value="">-- Select Room --</option>';
            
            if (!selectedFloorName) {
                // Show all rooms if no floor is selected
                allRooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.name;
                    option.textContent = `${room.name} - ${room.floor_name}`;
                    if (room.name === currentRoomValue) {
                        option.selected = true;
                    }
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
                if (room.name === currentRoomValue) {
                    option.selected = true;
                }
                roomSelect.appendChild(option);
            });
        }

        // Add event listener for floor level changes
        if (floorSelect) {
            floorSelect.addEventListener('change', function() {
                const selectedFloor = this.value;
                filterRoomsByFloor(selectedFloor);
            });
        }

        // Initialize room dropdown on page load
        // If there's a current floor selection, filter rooms accordingly and preserve the selected room
        const currentFloor = floorSelect.value;
        if (currentFloor) {
            // Pass the initial room value to preserve the selection
            filterRoomsByFloor(currentFloor, initialRoomValue);
        } else if (initialRoomValue) {
            // If no floor is selected but there's a room value, show all rooms
            filterRoomsByFloor('', initialRoomValue);
        }

        // Image removal functionality
        document.addEventListener('click', function(e) {
            // Handle multiple image removal
            if (e.target.closest('.remove-image-btn')) {
                const button = e.target.closest('.remove-image-btn');
                const imageId = button.getAttribute('data-image-id');
                const imageName = button.getAttribute('data-image-name');
                
                if (confirm(`Are you sure you want to remove "${imageName}"?`)) {
                    removeImage(imageId, button);
                }
            }
            
            // Handle legacy image removal
            if (e.target.closest('.remove-legacy-image-btn')) {
                const button = e.target.closest('.remove-legacy-image-btn');
                const itemId = button.getAttribute('data-item-id');
                
                if (confirm('Are you sure you want to remove the legacy image?')) {
                    removeLegacyImage(itemId, button);
                }
            }
        });

        function removeImage(imageId, button) {
            fetch(`/item/item-image/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image card from the DOM
                    button.closest('.col-md-3').remove();
                    
                    // Show success message
                    showAlert('Image removed successfully', 'success');
                    
                    // Check if no images left
                    const remainingImages = document.querySelectorAll('.remove-image-btn').length;
                    if (remainingImages === 0) {
                        location.reload(); // Reload to show "no images" state
                    }
                } else {
                    showAlert('Failed to remove image: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while removing the image', 'danger');
            });
        }

        function removeLegacyImage(itemId, button) {
            fetch(`/item/${itemId}/remove-legacy-image`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the legacy image card from the DOM
                    button.closest('.col-md-3').remove();
                    
                    // Show success message
                    showAlert('Legacy image removed successfully', 'success');
                    
                    // Reload to show "no images" state
                    location.reload();
                } else {
                    showAlert('Failed to remove legacy image: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while removing the legacy image', 'danger');
            });
        }

        function showAlert(message, type) {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert at the top of the form
            const form = document.querySelector('form');
            form.insertBefore(alertDiv, form.firstChild);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
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

        // Camera capture â†’ merge into existing multi-file input
        if (cameraInput) {
            cameraInput.addEventListener('change', function (e) {
                addFilesToImagesInput(e.target.files);
                // Clear camera input to avoid submitting duplicates
                cameraInput.value = '';
            });
        }

        if (imageInput) {
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
                    removeBtn.innerHTML = 'Ã—';
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
        }

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
    });


    // Company Modal functionality
    const addCompanyForm = document.getElementById('addCompanyForm');
    const companyModal = document.getElementById('addCompanyModal');
    const companySelect = document.getElementById('company_id');
    const saveCompanyBtn = document.getElementById('saveCompanyBtn');
    const companyModalAlert = document.getElementById('companyModalAlert');

    // Reset company modal form when modal is closed
    if(companyModal){
        companyModal.addEventListener('hidden.bs.modal', function() {
            addCompanyForm.reset();
            addCompanyForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addCompanyForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            companyModalAlert.style.display = 'none';
        });
    }

    // Handle company form submission
    function handleCompanySubmit() {
        // Show loading state
        const spinner = saveCompanyBtn.querySelector('.spinner-border');
        const btnText = saveCompanyBtn.querySelector('.btn-text');
        spinner.classList.remove('d-none');
        btnText.textContent = 'Adding...';
        saveCompanyBtn.disabled = true;

        // Clear previous errors
        addCompanyForm.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        addCompanyForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.textContent = '';
        });
        companyModalAlert.style.display = 'none';

        // Prepare form data
        const formData = new FormData(addCompanyForm);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Submit via AJAX
        fetch('{{ route("company.storeAjax") }}', {
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
                // Add new company to dropdown
                const newOption = document.createElement('option');
                newOption.value = data.company.id;
                newOption.textContent = data.company.name;
                companySelect.appendChild(newOption);
                
                // Select the new company
                companySelect.value = data.company.id;
                
                // Show success message
                companyModalAlert.className = 'alert alert-success';
                companyModalAlert.textContent = 'Company added successfully!';
                companyModalAlert.style.display = 'block';
                
                // Close modal after a short delay
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(companyModal);
                    modal.hide();
                }, 1500);
            } else {
                // Handle validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = addCompanyForm.querySelector(`[name="${field}"]`);
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
                    companyModalAlert.className = 'alert alert-danger';
                    companyModalAlert.textContent = data.message || 'An error occurred while adding the company.';
                    companyModalAlert.style.display = 'block';
                }
            }
        })
        .catch(error => {
            companyModalAlert.className = 'alert alert-danger';
            companyModalAlert.textContent = 'An error occurred while adding the company: ' + error.message;
            companyModalAlert.style.display = 'block';
        })
        .finally(() => {
            // Reset loading state
            spinner.classList.add('d-none');
            btnText.textContent = 'Add Company';
            saveCompanyBtn.disabled = false;
        });
    }

    // Handle form submission
    if(addCompanyForm){
        addCompanyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleCompanySubmit();
        });
    }

    // Handle button click
    if(saveCompanyBtn){
        saveCompanyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleCompanySubmit();
        });
    }

    // User Modal functionality
    const addUserForm = document.getElementById('addUserForm');
    const userModal = document.getElementById('addUserModal');
    const saveUserBtn = document.getElementById('saveUserBtn');
    const userModalAlert = document.getElementById('userModalAlert');
    
    console.log('User modal elements:', {
        addUserForm: !!addUserForm,
        userModal: !!userModal,
        saveUserBtn: !!saveUserBtn,
        userModalAlert: !!userModalAlert
    });
    
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
    if (userModal) {
        userModal.addEventListener('hidden.bs.modal', function() {
        if (addUserForm) {
            addUserForm.reset();
            addUserForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addUserForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
        }
        if (userModalAlert) {
            userModalAlert.style.display = 'none';
        }
        });
    }

    // Auto-generate password and set role when modal is shown
    if (userModal) {
        userModal.addEventListener('show.bs.modal', function() {
        // Generate a random password
        const password = generateRandomPassword();
        const passwordField = document.getElementById('modal_user_password');
        if (passwordField) {
            passwordField.value = password;
        }
        
        // Set role to employee
        const roleField = document.getElementById('modal_user_role');
        if (roleField) {
            roleField.value = 'employee';
        }
        });
    }

    // Function to generate random password
    function generateRandomPassword() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return password;
    }

    // Handle user form submission
    function handleUserSubmit() {
        console.log('handleUserSubmit called');
        if (!saveUserBtn) {
            console.error('saveUserBtn not found');
            return;
        }
        console.log('saveUserBtn found, proceeding with submission');
        
        // Show loading state
        const spinner = saveUserBtn.querySelector('.spinner-border');
        const btnText = saveUserBtn.querySelector('.btn-text');
        if (spinner) spinner.classList.remove('d-none');
        if (btnText) btnText.textContent = 'Adding...';
        saveUserBtn.disabled = true;

        // Clear previous errors
        if (addUserForm) {
            addUserForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            addUserForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
        }
        if (userModalAlert) {
            userModalAlert.style.display = 'none';
        }

        // Prepare form data
        if (!addUserForm) {
            console.error('addUserForm not found');
            return;
        }
        console.log('addUserForm found, preparing form data');
        const formData = new FormData(addUserForm);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        console.log('Form data prepared, submitting to:', '{{ route("user-management.storeAjax") }}');

        // Submit via AJAX
        fetch('{{ route("user-management.storeAjax") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response received:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
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
                if (userModalAlert) {
                    userModalAlert.className = 'alert alert-success';
                    userModalAlert.textContent = 'User added successfully!';
                    userModalAlert.style.display = 'block';
                }
                
                // Close modal after a short delay
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(userModal);
                    modal.hide();
                }, 1500);
            } else {
                // Handle validation errors
                if (data.errors && addUserForm) {
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
                    if (userModalAlert) {
                        userModalAlert.className = 'alert alert-danger';
                        userModalAlert.textContent = data.message || 'An error occurred while adding the user.';
                        userModalAlert.style.display = 'block';
                    }
                }
            }
        })
        .catch(error => {
            if (userModalAlert) {
                userModalAlert.className = 'alert alert-danger';
                userModalAlert.textContent = 'An error occurred while adding the user: ' + error.message;
                userModalAlert.style.display = 'block';
            }
        })
        .finally(() => {
            // Reset loading state
            if (spinner) spinner.classList.add('d-none');
            if (btnText) btnText.textContent = 'Add User';
            if (saveUserBtn) saveUserBtn.disabled = false;
        });
    }

    // Handle user form submission
    if (addUserForm) {
        addUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleUserSubmit();
        });
    }

    // Handle user button click
    if (saveUserBtn) {
        saveUserBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            alert('User button clicked!');
            console.log('User button clicked, calling handleUserSubmit');
            handleUserSubmit();
        });
    } else {
        console.error('saveUserBtn not found in DOM');
        alert('saveUserBtn not found in DOM');
    }

    // Designation Modal functionality
    const createDesignationForm = document.getElementById('createDesignationForm');
    const designationModal = document.getElementById('createDesignationModal');
    const saveDesignationBtn = document.getElementById('saveDesignationBtn');
    const designationModalAlert = document.getElementById('designationModalAlert');
    const designationSelect = document.getElementById('modal_user_designation_id');
    const departmentInput = document.getElementById('new_designation_department_input');
    const departmentHidden = document.getElementById('new_designation_department_id');
    const departmentDropdown = document.getElementById('departmentDropdown');
    const createNewDepartmentBtn = document.getElementById('createNewDepartment');
    let isNewDepartment = false;

    // Show dropdown when input is focused
    if (departmentInput) {
        departmentInput.addEventListener('focus', function() {
            if (departmentDropdown) {
                departmentDropdown.style.display = 'block';
            }
            filterDepartments();
        });
    }

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#new_designation_department_input') && !e.target.closest('#departmentDropdown')) {
            if (departmentDropdown) {
                departmentDropdown.style.display = 'none';
            }
        }
    });

    // Filter departments as user types
    if (departmentInput) {
        departmentInput.addEventListener('input', function() {
            filterDepartments();
            isNewDepartment = true; // Assume new department when typing
        });
    }

    // Handle department selection
    if (departmentDropdown) {
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
    }

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
    if (designationModal) {
        designationModal.addEventListener('hidden.bs.modal', function() {
            if (createDesignationForm) {
                createDesignationForm.reset();
            }
            if (departmentInput) {
                departmentInput.value = '';
            }
            if (departmentHidden) {
                departmentHidden.value = '';
            }
            if (departmentDropdown) {
                departmentDropdown.style.display = 'none';
            }
            isNewDepartment = false;
            if (createDesignationForm) {
                createDesignationForm.querySelectorAll('.is-invalid').forEach(field => {
                    field.classList.remove('is-invalid');
                });
                createDesignationForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                    feedback.textContent = '';
                });
            }
            if (designationModalAlert) {
                designationModalAlert.style.display = 'none';
            }
        });
    }

    // Handle designation form submission
    function handleDesignationSubmit() {
        if (!saveDesignationBtn) {
            console.error('saveDesignationBtn not found');
            return;
        }
        
        // Show loading state
        const spinner = saveDesignationBtn.querySelector('.spinner-border');
        const btnText = saveDesignationBtn.querySelector('.btn-text');
        if (spinner) spinner.classList.remove('d-none');
        if (btnText) btnText.textContent = 'Creating...';
        saveDesignationBtn.disabled = true;

        // Clear previous errors
        if (createDesignationForm) {
            createDesignationForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            createDesignationForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
        }
        if (designationModalAlert) {
            designationModalAlert.style.display = 'none';
        }

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
        if (!createDesignationForm) {
            console.error('createDesignationForm not found');
            return;
        }
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
                if (designationModalAlert) {
                    designationModalAlert.style.display = 'block';
                }
                
                // Close modal after a short delay
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(designationModal);
                    modal.hide();
                }, 1500);
            } else {
                // Handle validation errors
                if (data.errors && createDesignationForm) {
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
                    if (designationModalAlert) {
                        designationModalAlert.className = 'alert alert-danger';
                        designationModalAlert.textContent = data.message || 'An error occurred while creating the designation.';
                        designationModalAlert.style.display = 'block';
                    }
                }
            }
        })
        .catch(error => {
            if (designationModalAlert) {
                designationModalAlert.className = 'alert alert-danger';
                designationModalAlert.textContent = 'An error occurred while creating the designation: ' + error.message;
                designationModalAlert.style.display = 'block';
            }
        })
        .finally(() => {
            // Reset loading state
            if (spinner) spinner.classList.add('d-none');
            if (btnText) btnText.textContent = 'Create Designation';
            if (saveDesignationBtn) saveDesignationBtn.disabled = false;
        });
    }

    // Handle designation form submission
    if (createDesignationForm) {
        createDesignationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleDesignationSubmit();
        });
    }

    // Handle designation button click
    if (saveDesignationBtn) {
        saveDesignationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleDesignationSubmit();
        });
    }

</script>

