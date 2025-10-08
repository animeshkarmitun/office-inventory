@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Add New Designation</h1>
                    <p class="text-muted mb-0">Create a new designation and assign it to a department</p>
                </div>
                <a href="{{ route('designation.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Designations
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-plus-circle me-2"></i>
                        Create New Designation
                    </h5>
                </div>
                <div class="card-body p-4">
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

                    <form action="{{ route('designation.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="fas fa-briefcase me-2 text-primary"></i>
                                        Designation Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required
                                           placeholder="Enter designation name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department_id" class="form-label fw-semibold">
                                        <i class="fas fa-building me-2 text-info"></i>
                                        Department <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('department_id') is-invalid @enderror" 
                                            id="department_id" 
                                            name="department_id" 
                                            required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                                @if($department->location)
                                                    ({{ $department->location }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Department Information Display -->
                        <div class="row g-4 mt-2">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-header bg-transparent border-0 pb-0">
                                        <h6 class="fw-bold text-secondary mb-0 d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Available Departments
                                        </h6>
                                    </div>
                                    <div class="card-body pt-3">
                                        @if($departments->count() > 0)
                                            <div class="row">
                                                @foreach($departments as $department)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="d-flex align-items-center p-2 bg-white rounded border">
                                                            <i class="fas fa-building text-info me-2"></i>
                                                            <div>
                                                                <strong>{{ $department->name }}</strong>
                                                                @if($department->location)
                                                                    <br><small class="text-muted">{{ $department->location }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-3">
                                                <i class="fas fa-exclamation-triangle text-warning mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">No departments available. Please create a department first.</p>
                                                <a href="{{ route('department.showAdd') }}" class="btn btn-sm btn-primary mt-2">
                                                    <i class="fas fa-plus me-1"></i> Create Department
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('designation.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 d-flex align-items-center">
                                        <i class="fas fa-save me-2"></i>
                                        Create Designation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
    
    .alert {
        border-radius: 8px;
        border: none;
    }
</style>
@endpush
@endsection
