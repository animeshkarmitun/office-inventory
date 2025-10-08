@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Designations</h1>
                    <p class="text-muted mb-0">Manage employee designations and their departments</p>
                </div>
                <a href="{{ route('designation.create') }}" class="btn btn-primary d-flex align-items-center">
                    <i class="bi bi-plus me-2"></i>
                    Add New Designation
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('designation.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="department" class="form-label fw-semibold">
                                <i class="bi bi-funnel me-2 text-primary"></i>
                                Filter by Department
                            </label>
                            <select name="department" id="department" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                        @if($department->location)
                                            ({{ $department->location }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-search me-1"></i>
                                Filter
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('designation.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x me-1"></i>
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Designations Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 d-flex align-items-center text-white">
                        <i class="bi bi-briefcase me-2"></i>
                        Designations List
                        <span class="badge bg-light text-dark ms-2">{{ $designations->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($designations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">
                                            <i class="bi bi-hash me-2 text-muted"></i>
                                            ID
                                        </th>
                                        <th class="border-0 fw-semibold">
                                            <i class="bi bi-briefcase me-2 text-muted"></i>
                                            Designation Name
                                        </th>
                                        <th class="border-0 fw-semibold">
                                            <i class="bi bi-building me-2 text-muted"></i>
                                            Department
                                        </th>
                                        <th class="border-0 fw-semibold">
                                            <i class="bi bi-geo-alt me-2 text-muted"></i>
                                            Location
                                        </th>
                                        <th class="border-0 fw-semibold text-center">
                                            <i class="bi bi-gear me-2 text-muted"></i>
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($designations as $designation)
                                        <tr class="align-middle">
                                            <td class="py-3">
                                                <span class="badge bg-light text-dark fw-semibold">#{{ $designation->id }}</span>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-briefcase text-primary me-3"></i>
                                                    <div>
                                                        <strong class="text-dark">{{ $designation->name }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-building text-info me-2"></i>
                                                    <span>{{ $designation->department->name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                @if($designation->department->location)
                                                    <span class="badge bg-info text-white">
                                                        <i class="bi bi-geo-alt me-1"></i>
                                                        {{ $designation->department->location }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('designation.show', $designation) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('designation.edit', $designation) }}" 
                                                       class="btn btn-sm btn-outline-warning" 
                                                       title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('designation.destroy', $designation) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this designation?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-briefcase text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Designations Found</h4>
                            <p class="text-muted mb-4">
                                @if(request('department'))
                                    No designations found for the selected department.
                                @else
                                    Get started by creating your first designation.
                                @endif
                            </p>
                            <a href="{{ route('designation.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus me-2"></i>
                                Add New Designation
                            </a>
                        </div>
                    @endif
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
    
    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #e9ecef;
    }
    
    .table td {
        border-bottom: 1px solid #f8f9fa;
        vertical-align: middle;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .badge {
        border-radius: 6px;
        font-weight: 500;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
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
</style>
@endpush
@endsection
