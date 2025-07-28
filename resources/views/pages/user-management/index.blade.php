@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">User Management</h4>
                    <a href="{{ route('user-management.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Create New User
                    </a>
                </div>
                <div class="card-body">
                    @if(session('message'))
                        <div class="alert {{ session('alert') }} alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Admin</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : ($user->role === 'asset_manager' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->is_admin)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('user-management.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i> Delete
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 