@extends('layouts.app')

@section('content')

@include('inc.alert')
<div class="container-fluid px-4 py-4">
    <!-- Modern Header with Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Department Management</h1>
                    <p class="text-muted mb-0">Manage departments and their designations</p>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('department.showAdd') }}" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-plus me-2"></i>
                        Add Department
                    </a>
                    <a href="{{ route('department.create-designation') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="bi bi-briefcase me-2"></i>
                        Add Designation
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
                    <button class="nav-link active" id="departments-tab" data-bs-toggle="tab" data-bs-target="#departments" type="button" role="tab" aria-controls="departments" aria-selected="true">
                        <i class="bi bi-building me-2"></i>
                        Departments
                        <span class="badge bg-primary ms-2">{{ $departments->count() }}</span>
                    </button>
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

    <!-- Tab Content -->
    <div class="tab-content" id="departmentTabsContent">
        <div class="tab-pane fade show active" id="departments" role="tabpanel" aria-labelledby="departments-tab">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white border-0">
                            <h5 class="mb-0 d-flex align-items-center text-white">
                                <i class="bi bi-building me-2"></i>
                                Departments List
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if($departments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0 fw-semibold">
                                                    <i class="bi bi-hash me-2 text-muted"></i>
                                                    ID
                                                </th>
                                                <th class="border-0 fw-semibold">
                                                    <i class="bi bi-building me-2 text-muted"></i>
                                                    Department Name
                                                </th>
                                                <th class="border-0 fw-semibold">
                                                    <i class="bi bi-briefcase me-2 text-muted"></i>
                                                    Designations
                                                </th>
                                                <th class="border-0 fw-semibold text-center">
                                                    <i class="bi bi-gear me-2 text-muted"></i>
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($departments as $department)
                                                <tr class="align-middle">
                                                    <td class="py-3">
                                                        <span class="badge bg-light text-dark fw-semibold">#{{ $department->id }}</span>
                                                    </td>
                                                    <td class="py-3">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-building text-primary me-3"></i>
                                                            <div>
                                                                <strong class="text-dark">{{ $department->name }}</strong>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3">
                                                        <span class="badge bg-secondary text-white">
                                                            {{ $department->designations->count() }} designation(s)
                                                        </span>
                                                    </td>
                                                    <td class="py-3 text-center">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('department.showEdit', ['id' => $department->id]) }}" 
                                                               class="btn btn-sm btn-outline-warning" 
                                                               title="Edit Department">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <a href="{{ route('department.destroy', ['id' => $department->id]) }}"
                                                               class="btn btn-sm btn-outline-danger" 
                                                               title="Delete Department"
                                                               onclick="return confirm('Are you sure you want to delete this department?')">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
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
                                        <i class="bi bi-building text-muted" style="font-size: 4rem;"></i>
                                    </div>
                                    <h4 class="text-muted mb-3">No Departments Found</h4>
                                    <p class="text-muted mb-4">Get started by creating your first department.</p>
                                    <a href="{{ route('department.showAdd') }}" class="btn btn-primary">
                                        <i class="bi bi-plus me-2"></i>
                                        Add New Department
                                    </a>
                                </div>
                            @endif
                        </div>
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
    
    .nav-tabs .nav-link {
        border-radius: 8px 8px 0 0;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
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
</style>
@endpush
@endsection
