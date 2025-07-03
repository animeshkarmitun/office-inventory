@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Depreciation Report</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Purchase Date</th>
                <th>Value</th>
                <th>Depreciation Method</th>
                <th>Depreciation Rate (%)</th>
                <th>Annual Depreciation</th>
                <th>Book Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->purchase_date ? $item->purchase_date->format('Y-m-d') : 'N/A' }}</td>
                <td>{{ $item->value ? number_format($item->value, 2) : 'N/A' }}</td>
                <td>{{ $item->depreciation_method ? ucwords(str_replace('_', ' ', $item->depreciation_method)) : 'N/A' }}</td>
                <td>{{ $item->depreciation_rate !== null ? number_format($item->depreciation_rate, 2) : 'N/A' }}</td>
                <td>{{ $item->annualDepreciation() !== null ? number_format($item->annualDepreciation(), 2) : 'N/A' }}</td>
                <td>{{ $item->currentBookValue() !== null ? number_format($item->currentBookValue(), 2) : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 