@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Designations</h2>
            <p class="page-subtitle">Manage employee roles and position levels</p>
        </div>
        <a href="{{ route('designation.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Designation
        </a>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('designation.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-dark mb-2">
                        <i class="fas fa-filter text-primary me-2"></i> Filter by Department
                    </label>
                    <select name="department" class="form-select rounded-3 border-light shadow-sm">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }} {{ $department->location ? "($department->location)" : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark rounded-3 px-4 w-100 shadow-sm">
                        <i class="fas fa-search me-2"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('designation.index') }}" class="btn btn-outline-secondary rounded-3 px-4 w-100 shadow-sm">
                        <i class="fas fa-redo me-2"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-table shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Designation Name</th>
                        <th>Department</th>
                        <th>Location</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($designations as $designation)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ $designation->id }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-sm bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="fas fa-briefcase text-primary text-xs"></i>
                                </div>
                                <h6 class="mb-0 text-sm fw-bold">{{ $designation->name }}</h6>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-building text-info me-2 text-xs"></i>
                                <span class="text-sm">{{ $designation->department->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($designation->department->location)
                                <span class="badge bg-info-soft text-info px-3 border-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $designation->department->location }}
                                </span>
                            @else
                                <span class="text-muted text-xs">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('designation.show', $designation) }}" class="btn-action btn-action-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('designation.edit', $designation) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('designation.destroy', $designation) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this designation?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <p class="text-muted mb-0">No designations found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(94, 114, 228, 0.1); }
    .bg-info-soft { background-color: rgba(17, 201, 240, 0.1); }
</style>
@endsection
