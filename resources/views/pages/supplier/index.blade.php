@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-decoration-underline">List of Suppliers</h1>
        <a href="{{ route('supplier.showAdd') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add new supplier
        </a>
    </div>
    <form method="GET" action="" class="mb-4">
        <div class="input-group input-group-lg">
            <input type="text" name="search" class="form-control" placeholder="Search suppliers..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Brand Name</th>
                    <th scope="col">Person in charge</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Email</th>
                    <th scope="col">Tax Number</th>
                    <th scope="col">Payment Terms</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                <tr>
                    <th scope="row">{{ $supplier->id }}</th>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->incharge_name }}</td>
                    <td>{{ $supplier->contact_number }}</td>
                    <td>{{ $supplier->email ?? 'N/A' }}</td>
                    <td>{{ $supplier->tax_number ?? 'N/A' }}</td>
                    <td>{{ $supplier->payment_terms ?? 'N/A' }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('supplier.showEdit', ['id'=>$supplier->id]) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('supplier.destroy', ['id'=>$supplier->id]) }}" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this supplier?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
