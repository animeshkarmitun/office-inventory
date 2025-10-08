@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Designation Details</h1>
                    <p class="text-muted mb-0">View designation information and details</p>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('department') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="bi bi-building me-2"></i>
                        Back to Departments
                    </a>
                    <a href="{{ route('department.designations') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="bi bi-briefcase me-2"></i>
                        View All Designations
                    </a>
                    <a href="{{ route('department.edit-designation', $designation) }}" class="btn btn-warning d-flex align-items-center">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Designation
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

    <!-- Designation Details -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 d-flex align-items-center text-white">
                        <i class="bi bi-info-circle me-2"></i>
                        Designation Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="bi bi-hash me-2 text-primary"></i>
                                    Designation ID
                                </label>
                                <div class="info-value">
                                    <span class="badge bg-light text-dark fw-semibold fs-6">#{{ $designation->id }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="bi bi-calendar me-2 text-primary"></i>
                                    Created At
                                </label>
                                <div class="info-value">
                                    <span class="text-dark">{{ $designation->created_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="bi bi-briefcase me-2 text-primary"></i>
                                    Designation Name
                                </label>
                                <div class="info-value">
                                    <h6 class="text-dark mb-0">{{ $designation->name }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="bi bi-building me-2 text-primary"></i>
                                    Department
                                </label>
                                <div class="info-value">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-building text-info me-2"></i>
                                        <span class="text-dark">{{ $designation->department->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="bi bi-clock me-2 text-primary"></i>
                                    Last Updated
                                </label>
                                <div class="info-value">
                                    <span class="text-dark">{{ $designation->updated_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-gear me-2 text-primary"></i>
                        Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('department.edit-designation', $designation) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>
                            Edit Designation
                        </a>
                        <form action="{{ route('department.destroy-designation', $designation) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this designation? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-2"></i>
                                Delete Designation
                            </button>
                        </form>
                        <a href="{{ route('department.designations') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Designations
                        </a>
                    </div>
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
    
    .info-item {
        margin-bottom: 1.5rem;
    }
    
    .info-item:last-child {
        margin-bottom: 0;
    }
    
    .info-value {
        margin-top: 0.5rem;
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
    
    .badge {
        border-radius: 6px;
        font-weight: 500;
    }
    
    .fs-6 {
        font-size: 1rem !important;
    }
</style>
@endpush
@endsection
