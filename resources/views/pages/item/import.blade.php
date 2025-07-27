@extends('layouts.app')

@section('content')

@include('inc.alert')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-file-import me-2"></i>
                        Import Items from Excel
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Import Instructions:</h6>
                        <ul class="mb-0">
                            <li>Export your current items first to get the correct format</li>
                            <li>Edit the exported file to add new items or modify existing ones</li>
                            <li>For new items, leave the ID column empty</li>
                            <li>For existing items, keep the original ID to update them</li>
                            <li>Required fields: Name, Asset Type, Status, Floor Level, Room Number</li>
                            <li>Supported file formats: .xlsx, .xls, .csv (max 10MB)</li>
                        </ul>
                    </div>

                    <form action="{{ route('item.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Select Excel File</label>
                            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Choose the Excel file you want to import</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>
                                Import Items
                            </button>
                            <a href="{{ route('item') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Items
                            </a>
                            <a href="{{ route('item.template') }}" class="btn btn-success">
                                <i class="fas fa-download me-2"></i>
                                Download Template
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Import Guidelines -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Field Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Required Fields:</h6>
                            <ul>
                                <li><strong>Name:</strong> Item name (required)</li>
                                <li><strong>Asset Type:</strong> Fixed or Current</li>
                                <li><strong>Status:</strong> Available, In Use, Maintenance, Not Traceable, Disposed</li>
                                <li><strong>Floor Level:</strong> Floor number or level</li>
                                <li><strong>Room Number:</strong> Room identifier</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Optional Fields:</h6>
                            <ul>
                                <li><strong>Value:</strong> Numeric value (e.g., 1000 or $1,000)</li>
                                <li><strong>Depreciation Rate:</strong> Percentage (e.g., 10 or 10%)</li>
                                <li><strong>Supplier:</strong> Supplier name (will create if not exists)</li>
                                <li><strong>Purchase Date:</strong> YYYY-MM-DD format</li>
                                <li><strong>Description:</strong> Item description</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-3">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notes:</h6>
                        <ul class="mb-0">
                            <li>Serial numbers and asset tags are auto-generated for new items</li>
                            <li>If a supplier doesn't exist, it will be created automatically</li>
                            <li>User names (Purchased By, Received By, Assigned To) must match existing users</li>
                            <li>Currency values can include $ symbol and commas</li>
                            <li>Percentages can include % symbol</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0 !important;
        border: none;
    }
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
    }
    .alert {
        border-radius: 8px;
        border: none;
    }
    .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>
@endpush 