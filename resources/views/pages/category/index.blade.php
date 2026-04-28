@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @include('inc.alert')
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">Categories</h2>
            <p class="page-subtitle">Organize your assets into manageable groups</p>
        </div>
        <a href="{{ route('category.showAdd') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Category
        </a>
    </div>

    <div class="card card-table shadow-sm border-0 mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th style="width: 100px;">#</th>
                        <th class="sortable {{ request('sort') == 'name' ? (request('direction') == 'asc' ? 'asc' : 'desc') : '' }}" 
                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}'">
                            Category Name
                        </th>
                        <th class="text-center" style="width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td><span class="text-xs font-weight-bold">{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-sm bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="fas fa-tags text-primary text-xs"></i>
                                </div>
                                <h6 class="mb-0 text-sm fw-bold">{{ $category->name }}</h6>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('category.showEdit', ['id' => $category->id]) }}" class="btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('category.destroy', ['id' => $category->id]) }}" 
                                   class="btn-action btn-action-delete"
                                   title="Delete"
                                   onclick="return confirm('Are you sure you want to delete this category?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (method_exists($categories, 'links'))
            <div class="px-4 py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted text-sm">Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} results</span>
                    {!! $categories->links() !!}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(94, 114, 228, 0.1); }
</style>
@endsection
