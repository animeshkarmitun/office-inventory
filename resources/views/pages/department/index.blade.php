@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Department Management</h2>
            <p class="page-subtitle">Manage organizational units and job designations</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('department.create-designation') }}" class="btn btn-outline-primary rounded-3 px-3 shadow-sm">
                <i class="fas fa-briefcase me-2"></i> Add Designation
            </a>
            <a href="{{ route('department.showAdd') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Add Department
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-4">
        <ul class="nav nav-pills custom-tabs" id="departmentTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="departments-tab" data-bs-toggle="tab" data-bs-target="#departments" type="button" role="tab">
                    <i class="fas fa-building me-2"></i> Departments <span class="badge bg-white text-primary ms-2">{{ $departments->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('department.designations') }}">
                    <i class="fas fa-user-tag me-2"></i> Designations
                </a>
            </li>
        </ul>
    </div>

    <div class="card card-table shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th style="width: 100px;">#</th>
                        <th class="sortable {{ request('sort') == 'name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Department Name
                        </th>
                        <th>Designations Count</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="fas fa-building text-primary"></i>
                                </div>
                                <h6 class="mb-0 text-sm fw-bold">{{ $department->name }}</h6>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info-soft text-info px-3">
                                {{ $department->designations->count() }} Position(s)
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('department.showEdit', ['id' => $department->id]) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('department.destroy', ['id' => $department->id]) }}" 
                                   class="btn-action btn-action-delete" 
                                   title="Delete"
                                   onclick="return confirm('Are you sure you want to delete this department?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (method_exists($departments, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $departments->firstItem() ?? 0 }} to {{ $departments->lastItem() ?? 0 }} of {{ $departments->total() }} results</span>
                    {!! $departments->links() !!}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .custom-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .custom-tabs .nav-link.active {
        background-color: var(--btn-primary-bg);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .bg-info-soft { background-color: rgba(17, 201, 240, 0.1); }
</style>
@endsection
