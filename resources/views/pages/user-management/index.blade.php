@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">User Management</h2>
            <p class="page-subtitle">Control system access and user permissions</p>
        </div>
        <a href="{{ route('user-management.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
            <i class="fas fa-user-plus me-2"></i> Create New User
        </a>
    </div>

    <div class="card card-table shadow-sm border-0 mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable {{ request('sort') == 'name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            User Name
                        </th>
                        <th class="sortable {{ request('sort') == 'email' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'email', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Email Address
                        </th>
                        <th class="sortable {{ request('sort') == 'role' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'role', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            System Role
                        </th>
                        <th>Admin Status</th>
                        <th class="sortable {{ request('sort') == 'created_at' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Joined Date
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="fas fa-user text-primary text-xs"></i>
                                </div>
                                <h6 class="mb-0 text-sm fw-bold">{{ $user->name }}</h6>
                            </div>
                        </td>
                        <td><span class="text-sm">{{ $user->email }}</span></td>
                        <td>
                            <span class="badge bg-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : ($user->role === 'asset_manager' ? 'info' : 'secondary')) }}-soft text-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : ($user->role === 'asset_manager' ? 'info' : 'secondary')) }} px-3">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_admin)
                                <span class="badge bg-success-soft text-success px-3"><i class="fas fa-check me-1"></i> Admin</span>
                            @else
                                <span class="badge bg-light text-muted px-3 border">Staff</span>
                            @endif
                        </td>
                        <td><span class="text-sm">{{ $user->created_at->format('M d, Y') }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('user-management.edit', $user->id) }}" class="btn-action btn-action-edit" title="Edit User">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (method_exists($users, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results</span>
                    {!! $users->links() !!}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(94, 114, 228, 0.1); }
    .bg-danger-soft { background-color: rgba(245, 54, 92, 0.1); }
    .bg-warning-soft { background-color: rgba(fb, 64, 64, 0.1); }
    .bg-info-soft { background-color: rgba(17, 201, 240, 0.1); }
    .bg-secondary-soft { background-color: rgba(136, 152, 170, 0.1); }
    .bg-success-soft { background-color: rgba(45, 206, 137, 0.1); }
</style>
@endsection