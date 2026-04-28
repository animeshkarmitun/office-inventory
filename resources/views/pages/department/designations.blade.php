@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Designation Management</h2>
            <p class="page-subtitle">Manage employee job roles and their departments</p>
        </div>
        <a href="{{ route('department.create-designation') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Designation
        </a>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-4">
        <ul class="nav nav-pills custom-tabs" id="departmentTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('department') }}">
                    <i class="fas fa-building me-2"></i> Departments
                </a>
            </li>
            <li class="nav-item">
                <button class="nav-link active" id="designations-tab" data-bs-toggle="tab" data-bs-target="#designations" type="button" role="tab">
                    <i class="fas fa-user-tag me-2"></i> Designations <span class="badge bg-white text-primary ms-2">{{ $designations->total() }}</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form method="GET" action="{{ route('department.designations') }}" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <select name="department" id="department" class="form-select form-select-premium" onchange="this.form.submit()">
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
                <div class="col-md-4">
                    <a href="{{ route('department.designations') }}" class="btn btn-outline-secondary rounded-3 px-3 w-100" style="height: 46px; line-height: 32px;">
                        <i class="fas fa-undo me-1"></i> Clear
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
                        <th style="width: 100px;">#</th>
                        <th class="sortable {{ request('sort') == 'name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Designation Name
                        </th>
                        <th class="sortable {{ request('sort') == 'department_id' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'department_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Department
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($designations as $designation)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($designations->currentPage() - 1) * $designations->perPage() + $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-sm bg-info-soft rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="fas fa-briefcase text-info text-xs"></i>
                                </div>
                                <h6 class="mb-0 text-sm fw-bold">{{ $designation->name }}</h6>
                            </div>
                        </td>
                        <td>
                            <span class="text-sm">
                                <i class="fas fa-building text-muted me-2"></i>
                                {{ $designation->department->name }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('department.show-designation', $designation) }}" class="btn-action btn-action-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('department.edit-designation', $designation) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('department.destroy-designation', $designation) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this designation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <p class="text-muted mb-0">No designations found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (method_exists($designations, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $designations->firstItem() ?? 0 }} to {{ $designations->lastItem() ?? 0 }} of {{ $designations->total() }} results</span>
                    {!! $designations->links() !!}
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
