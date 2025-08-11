@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container">
    <h1 class="text-decoration-underline">Edit Item</h1>
    <a href="{{ route('item') }}" class="btn btn-secondary mt-3">Back to Items</a>

    {{-- Error summary --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your submission:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="border border-dark mt-3 p-3">
        <form action="{{ route('item.update', ['id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <h3>Basic Information</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" required value="{{ old('name', $item->name) }}">
                        @error('name')
                            <div class="invalid-feedback">Item Name is required. Please enter a value.</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" id="serial_number" value="{{ old('serial_number', $item->serial_number) }}" readonly placeholder="Will be auto-generated">
                        <small class="form-text text-muted">Serial number will be automatically generated when the item is created.</small>
                        @error('serial_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="asset_tag" class="form-label">Asset Tag</label>
                        <input type="text" name="asset_tag" class="form-control @error('asset_tag') is-invalid @enderror" id="asset_tag" value="{{ old('asset_tag', $item->asset_tag) }}" readonly placeholder="Will be auto-generated">
                        <small class="form-text text-muted">Asset tag will be automatically generated when the item is created.</small>
                        @error('asset_tag')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" id="barcode" value="{{ old('barcode', $item->barcode) }}">
                        @error('barcode')
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

                <div class="col-md-6">
                    <h3>Asset Details</h3>
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

            <div class="row mt-4">
                <div class="col-md-6">
                    <h3>Purchase Information</h3>
                    <div class="mb-3">
                        <label for="purchased_by" class="form-label">Purchased By</label>
                        <select name="purchased_by" class="form-select @error('purchased_by') is-invalid @enderror" id="purchased_by">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('purchased_by', $item->purchased_by) == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                        @error('purchased_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $item->supplier_id ?? $defaultSupplier->id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
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
                        <select name="received_by" class="form-select @error('received_by') is-invalid @enderror" id="received_by">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('received_by', $item->received_by) == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                        @error('received_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <h3>Location Information</h3>
                    <div class="mb-3">
                        <label for="floor_level" class="form-label">Floor Level <span class="text-danger">*</span></label>
                        <select name="floor_level" class="form-select @error('floor_level') is-invalid @enderror" id="floor_level" required>
                            <option value="">-- Select Floor --</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->name }}" {{ old('floor_level', $item->floor_level) == $floor->name ? 'selected' : '' }}>
                                    {{ $floor->name }} ({{ $floor->serial_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('floor_level')
                            <div class="invalid-feedback">Floor Level is required. Please select a value.</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                        <select name="room_number" class="form-select @error('room_number') is-invalid @enderror" id="room_number" required>
                            <option value="">-- Select Room --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->name }}" {{ old('room_number', $item->room_number) == $room->name ? 'selected' : '' }}>
                                    {{ $room->name }} ({{ $room->room_number }}) - {{ $room->floor->name }}
                                </option>
                            @endforeach
                        </select>
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
                        <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $item->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name ?? 'No Name' }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h3>Specifications</h3>
                    <div class="mb-3">
                        <label for="specifications" class="form-label">Specifications</label>
                        <textarea name="specifications" class="form-control @error('specifications') is-invalid @enderror" id="specifications" rows="3">{{ old('specifications', $item->specifications) }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" id="remarks" rows="3">{{ old('remarks', $item->remarks) }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Item Image</label>
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @if($item->image)
                    <small class="form-text text-muted">Current image: {{ basename($item->image) }}. Upload a new image to replace it.</small>
                @endif
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Item</button>
        </form>
    </div>
</div>

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
            
            // You can display this in a read-only field or as a note
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
        
        // Run calculation on page load
        calculateDepreciationCost();
    });
</script>
@endpush
@endsection

