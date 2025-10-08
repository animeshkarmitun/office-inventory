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
                <div class="input-group">
                <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name ?? 'No Name' }}</option>
                    @endforeach
                </select>
                    <button type="button" class="btn btn-outline-primary" id="addSupplierBtn" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
                @error('supplier_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label for="invoice_number" class="form-label">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" value="{{ old('invoice_number') }}">
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
                <div class="input-group">
                <select name="purchased_by" id="purchased_by" class="form-select @error('purchased_by') is-invalid @enderror" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('purchased_by') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                    <button type="button" class="btn btn-outline-primary" id="addUserPurchasedBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
                @error('purchased_by') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label for="received_by" class="form-label">Received By <span class="text-danger">*</span></label>
                <div class="input-group">
                <select name="received_by" id="received_by" class="form-select @error('received_by') is-invalid @enderror" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('received_by') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                    <button type="button" class="btn btn-outline-primary" id="addUserReceivedBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
                @error('received_by') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="invoice_images" class="form-label">Invoice Images (webp/jpeg/png/pdf) - Multiple files allowed</label>
                <input type="file" name="invoice_images[]" id="invoice_images" class="form-control" accept="image/webp, image/jpeg, image/png, application/pdf" multiple>
                <small class="form-text text-muted">You can select multiple files (max 10 files, 4MB each)</small>
                @error('invoice_images') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                @error('invoice_images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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