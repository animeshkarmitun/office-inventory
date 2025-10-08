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
                <div class="input-group">
                    <select name="supplier_id" id="supplier_id" class="form-select" required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $supplier->id == $purchase->supplier_id ? 'selected' : '' }}>{{ $supplier->name ?? 'No Name' }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary" id="addSupplierBtn" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <label for="invoice_number" class="form-label">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ $purchase->invoice_number }}">
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
                <div class="input-group">
                    <select name="purchased_by" id="purchased_by" class="form-select" required>
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $purchase->purchased_by ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary" id="addUserPurchasedBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label for="received_by" class="form-label">Received By <span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="received_by" id="received_by" class="form-select" required>
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $purchase->received_by ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary" id="addUserReceivedBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="invoice_images" class="form-label">Add More Invoice Images (webp/jpeg/png/pdf) - Multiple files allowed</label>
                <input type="file" name="invoice_images[]" id="invoice_images" class="form-control" accept="image/webp, image/jpeg, image/png, application/pdf" multiple>
                <small class="form-text text-muted">You can select multiple files (max 10 files, 4MB each)</small>
                @error('invoice_images') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                @error('invoice_images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                
                @if($purchase->images->count() > 0)
                    <div class="mt-3">
                        <strong>Current Images:</strong>
                        <div class="row mt-2">
                            @foreach($purchase->images as $image)
                                <div class="col-md-3 mb-2">
                                    <div class="card">
                                        <img src="{{ $image->image_url }}" class="card-img-top" style="height: 100px; object-fit: cover;" alt="Invoice Image">
                                        <div class="card-body p-2">
                                            <small class="text-muted">{{ $image->original_name }}</small>
                                            <br>
                                            <small class="text-muted">{{ $image->file_size_human }}</small>
                                            <br>
                                            <a href="{{ route('purchase.deleteImage', [$purchase->id, $image->id]) }}" 
                                               class="btn btn-sm btn-danger mt-1" 
                                               onclick="return confirm('Are you sure you want to delete this image?')">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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
                                <label for="modal_supplier_contact" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_supplier_contact" name="contact_number" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_supplier_email" class="form-label">Email Address</label>
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
                                <label for="modal_supplier_tax" class="form-label">Tax Number</label>
                                <input type="text" class="form-control" id="modal_supplier_tax" name="tax_number">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_supplier_payment" class="form-label">Payment Terms</label>
                                <input type="text" class="form-control" id="modal_supplier_payment" name="payment_terms">
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
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="saveSupplierBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Add Supplier</span>
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
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveUserBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Add User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
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

    // Supplier Modal Handling
    const supplierModal = document.getElementById('addSupplierModal');
    const addSupplierForm = document.getElementById('addSupplierForm');
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
                const supplierSelect = document.getElementById('supplier_id');
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

    // Handle supplier form submission
    addSupplierForm.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        handleSupplierSubmit();
    });

    // Handle supplier button click
    saveSupplierBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        handleSupplierSubmit();
    });

    // User Modal Handling
    const userModal = document.getElementById('addUserModal');
    const addUserForm = document.getElementById('addUserForm');
    const saveUserBtn = document.getElementById('saveUserBtn');
    const userModalAlert = document.getElementById('userModalAlert');
    
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

    // Reset modal form when modal is closed
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
                newOption.textContent = data.user.name + ' (' + data.user.email + ')';
                
                const purchasedBySelect = document.getElementById('purchased_by');
                const receivedBySelect = document.getElementById('received_by');
                
                purchasedBySelect.appendChild(newOption.cloneNode(true));
                receivedBySelect.appendChild(newOption.cloneNode(true));
                
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
});
</script>
@endpush
@endsection 