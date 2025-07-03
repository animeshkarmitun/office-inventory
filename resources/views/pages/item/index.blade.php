@extends('layouts.app')

@section('content')

@include('inc.alert')
<h1 class="mb-4" style="font-size:2.1rem;font-weight:600;">List of Items</h1>
<div class="item-controls mb-4 d-flex flex-column flex-md-row align-items-stretch gap-3">
    <form method="GET" action="{{ route('item') }}" class="flex-grow-1">
        <div class="input-group input-group-lg search-group">
            <input type="text" name="search" class="form-control search-input" placeholder="Search items..." value="{{ request('search') }}" autocomplete="off">
            <button class="btn btn-search" type="submit"><i class="fas fa-search"></i> Search</button>
        </div>
    </form>
    <a href="{{ route('item.showAdd') }}" class="btn btn-add align-self-md-start align-self-stretch">
        <span class="btn-add-icon me-2">
            <i class="fas fa-plus"></i>
            <svg class="fallback-plus" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:none;vertical-align:middle;">
                <rect x="7.5" width="3" height="18" rx="1.5" fill="currentColor"/>
                <rect y="10.5" width="3" height="18" rx="1.5" transform="rotate(-90 0 10.5)" fill="currentColor"/>
            </svg>
        </span>
        Add new item
    </a>
</div>
<div id="items-table-wrapper">
    @include('pages.item.partials.table')
</div>

@endsection

@push('styles')
<style>
    .item-controls {
        margin-bottom: 2.5rem;
        gap: 1.2rem;
    }
    .search-group {
        box-shadow: 0 2px 8px rgba(25, 118, 210, 0.07);
        border-radius: 2rem;
        overflow: hidden;
        background: #fff;
    }
    .search-input {
        border: none;
        border-radius: 2rem 0 0 2rem;
        font-size: 1.1rem;
        padding-left: 1.2rem;
        background: #f8fafc;
    }
    .search-input:focus {
        box-shadow: none;
        background: #fff;
    }
    .btn-search {
        background: #1976d2;
        color: #fff;
        border-radius: 0 2rem 2rem 0;
        font-weight: 500;
        padding: 0 2rem;
        border: none;
        transition: background 0.2s, box-shadow 0.2s;
    }
    .btn-search:hover, .btn-search:focus {
        background: #1256a3;
        color: #fff;
        box-shadow: 0 4px 16px rgba(25, 118, 210, 0.13);
    }
    .btn-add {
        background: #2196f3;
        color: #fff;
        border-radius: 2rem;
        font-weight: 700;
        font-size: 1.13rem;
        padding: 0.8em 2.2em;
        box-shadow: 0 4px 16px rgba(33, 150, 243, 0.13);
        border: none;
        transition: background 0.18s, box-shadow 0.18s, transform 0.13s;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 180px;
        cursor: pointer;
        outline: none;
    }
    .btn-add:hover, .btn-add:focus {
        background: #1769aa;
        color: #fff;
        box-shadow: 0 8px 24px rgba(33, 150, 243, 0.18);
        text-decoration: none;
        transform: scale(1.045);
    }
    .btn-add-icon {
        display: flex;
        align-items: center;
        font-size: 1.2em;
    }
    /* Fallback for plus icon if Font Awesome is missing */
    .btn-add .fa-plus { display: inline-block; }
    .btn-add .fallback-plus { display: none; }
    .btn-add .fa-plus:empty + .fallback-plus { display: inline-block; }
    @media (max-width: 767.98px) {
        .item-controls {
            flex-direction: column;
        }
        .btn-add {
            min-width: 100%;
        }
    }
</style>
@endpush
