@extends('layouts.app')

@section('content')
    @include('partials.flash')

    <div class="page-hero">
        <div class="container">
            <nav class="breadcrumb"><a href="{{ route('home') }}">Home</a> &raquo; <span>Search</span></nav>
            <h1>{{ $term ? 'Results for "' . e($term) . '"' : 'Search Products' }}</h1>
        </div>
    </div>

    <div class="container" style="padding:2.5rem 0;">
        <div style="max-width:620px;margin:0 auto 2rem;">
            <form action="{{ route('search.index') }}" method="GET">
                <div class="header-search" style="max-width:none;margin:0;display:flex;">
                    <input type="search" name="q" value="{{ $term }}" placeholder="I am shopping for ..." style="flex:1;padding:.72rem 1rem;border:1px solid var(--border);border-radius:10px 0 0 10px;font-size:.95rem;background:var(--light);">
                    <button type="submit" style="padding:0 1.4rem;background:#0a0a0a;color:var(--orange);border:none;border-radius:0 10px 10px 0;cursor:pointer;">Search</button>
                </div>
            </form>
        </div>

        @if($products->count())
            <span class="count" style="color:var(--muted);">Showing {{ $products->total() }} result(s)</span>
            <ul class="product-grid reveal" style="margin-top:1.2rem;">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </ul>
            <div class="pagination">{{ $products->links() }}</div>
        @elseif($term)
            <div class="search-empty">
                <h2>No products found for "{{ e($term) }}"</h2>
                <p>Try different keywords like "inverter", "battery", "panel", "Jinko" or "5kWh".</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:1rem;">Browse All Products</a>
            </div>
        @else
            <div class="search-empty">
                <h2>Type something to search</h2>
                <p>Search our catalog of solar panels, batteries, inverters and more.</p>
            </div>
        @endif
    </div>
@endsection