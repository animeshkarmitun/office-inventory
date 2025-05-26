@extends('layouts.app')

@section('content')

@include('inc.alert')
<div class="container">
    <h1 class="text-decoration-underline">List of Items</h1>
    <div class="mb-4">
        <div class="input-group input-group-lg">
            <input type="text" id="item-search" class="form-control" placeholder="Search items..." value="{{ request('search') }}" autocomplete="off">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
    </div>
    <a href="{{ route('item.showAdd') }}" class="btn btn-primary mt-3">Add new item</a>
    <div id="items-table-wrapper">
        @include('pages.item.partials.table')
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    window.ITEMS_ROUTE_URL = "{{ route('item') }}";
</script>
<script src="/js/item-search.js"></script>
@endpush
