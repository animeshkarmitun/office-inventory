@extends('layouts.app')

@section('content')
@include('inc.alert')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-decoration-underline">List of Companies</h1>
        <!-- Optional: Add button could be added here if needed in future -->
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Company Name</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                <tr>
                    <th scope="row">{{ $company->id }}</th>
                    <td>{{ $company->name }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('company.showEdit', ['id'=>$company->id]) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('company.destroy', ['id'=>$company->id]) }}" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this company?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="d-flex justify-content-center">
            {{ $companies->links() }}
        </div>
    </div>
</div>
@endsection
