@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Edit Designation</h1>
                    <p class="text-muted mb-0">Update designation information</p>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('department') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="bi bi-building me-2"></i>
                        Back to Departments
                    </a>
                    <a href="{{ route('department.designations') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="bi bi-briefcase me-2"></i>
                        View Designations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="departmentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('department') }}" role="tab">
                        <i class="bi bi-building me-2"></i>
                        Departments
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('department.designations') }}" role="tab">
                        <i class="bi bi-briefcase me-2"></i>
                        Designations
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Form Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 d-flex align-items-center text-white">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Designation Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('department.update-designation', $designation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="bi bi-briefcase me-2 text-primary"></i>
                                        Designation Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $designation->name) }}" 
                                           placeholder="Enter designation name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department_id" class="form-label fw-semibold">
                                        <i class="bi bi-building me-2 text-primary"></i>
                                        Department <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('department_id') is-invalid @enderror" 
                                            id="department_id" 
                                            name="department_id" 
                                            required>
                                        <option value="">Select a department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" 
                                                    {{ old('department_id', $designation->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                                @if($department->location)
                                                    ({{ $department->location }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('department.designations') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x me-2"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check me-2"></i>
                                        Update Designation
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
        padding: 0.75rem 1rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .form-label {
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.75rem 1.5rem;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .nav-tabs .nav-link {
        border-radius: 8px 8px 0 0;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .fw-semibold {
        font-weight: 600 !important;
    }
    
    .h2 {
        font-size: 2rem;
    }
    
    .text-muted {
        font-size: 0.875rem;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
    
    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>
@endpush
@endsection



