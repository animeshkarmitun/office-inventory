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
                    <p class="text-muted mb-0">View designation information and related details</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('designation.edit', $designation) }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="fas fa-edit me-2"></i>
                        Edit Designation
                    </a>
                    <a href="{{ route('designation.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Designations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Main Information Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-briefcase me-2"></i>
                        {{ $designation->name }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-briefcase text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Designation Name</h6>
                                    <p class="text-muted mb-0 fs-5">{{ $designation->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-building text-info" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Department</h6>
                                    <p class="text-muted mb-0 fs-5">{{ $designation->department->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Information Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white border-0">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-building me-2"></i>
                        Department Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-semibold text-muted mb-2">Department Name</h6>
                                <p class="fs-5 mb-0">{{ $designation->department->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-semibold text-muted mb-2">Location</h6>
                                <p class="fs-5 mb-0">{{ $designation->department->location ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-secondary text-white border-0">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-clock me-2"></i>
                        Timeline
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-plus text-success"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Created</h6>
                                    <p class="text-muted mb-0">{{ $designation->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-edit text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Last Updated</h6>
                                    <p class="text-muted mb-0">{{ $designation->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('designation.edit', $designation) }}" class="btn btn-primary px-4 d-flex align-items-center">
                            <i class="fas fa-edit me-2"></i>
                            Edit Designation
                        </a>
                        <a href="{{ route('designation.index') }}" class="btn btn-outline-secondary px-4 d-flex align-items-center">
                            <i class="fas fa-list me-2"></i>
                            View All Designations
                        </a>
                        <form action="{{ route('designation.destroy', $designation) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this designation? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger px-4 d-flex align-items-center">
                                <i class="fas fa-trash me-2"></i>
                                Delete Designation
                            </button>
                        </form>
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
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .fw-semibold {
        font-weight: 600 !important;
    }
    
    .h2 {
        font-size: 2rem;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
    
    .bg-info.bg-opacity-10 {
        background-color: rgba(var(--bs-info-rgb), 0.1) !important;
    }
    
    .bg-success.bg-opacity-10 {
        background-color: rgba(var(--bs-success-rgb), 0.1) !important;
    }
    
    .bg-warning.bg-opacity-10 {
        background-color: rgba(var(--bs-warning-rgb), 0.1) !important;
    }
    
    .fs-5 {
        font-size: 1.25rem !important;
    }
</style>
@endpush
@endsection













