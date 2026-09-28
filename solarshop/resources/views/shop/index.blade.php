@extends('layouts.app')

@section('content')
    @include('partials.flash')

    <div class="page-hero">
        <div class="container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">Home</a> &raquo;
                <span>{{ isset($category) ? $category->name : (isset($currentBrand) ? $currentBrand->name : 'Shop') }}</span>
            </nav>
            <h1>{{ isset($category) ? $category->name : (isset($currentBrand) ? $currentBrand->name : 'Shop All Solar Products') }}</h1>
@isset($category)
        @if($category->description)
            <p style="max-width:760px;">{{ $category->description }}</p>
        @endif
    @endisset
</div>

@if($chipCategories->count())
    <div class="container cat-chips">
        <span class="cat-chips-label">Browse:</span>
        @if($category && $category->parent)
            <a href="{{ route('category.show', $category->parent->slug) }}" class="facet-chip">All {{ $category->parent->name }}</a>
        @endif
        @foreach($chipCategories as $chip)
            <a href="{{ route('category.show', $chip->slug) }}"
               class="facet-chip {{ request('category') === $chip->slug ? 'active' : '' }}">{{ $chip->name }}</a>
        @endforeach
    </div>
@endif
    </div>

    <div class="container shop-layout">
        <aside class="filter-panel">
            <h4>Filters</h4>
            <form action="{{ route('shop.index') }}" method="GET">
                <div class="filter-group">
                    <label for="f-category">Category</label>
                    <select name="category" id="f-category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categoryTree as $cat)
                            <optgroup label="{{ $cat->name }}">
                                <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>All {{ $cat->name }}</option>
                                @foreach($cat->children as $child)
                                    <option value="{{ $child->slug }}" @selected(request('category') === $child->slug)>{{ $child->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="f-brand">Brand</label>
                    <select name="brand" id="f-brand" onchange="this.form.submit()">
                        <option value="">All Brands</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->slug }}" @selected(request('brand') === $b->slug)>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="f-min-price">Min Price (KSh)</label>
                    <input type="number" id="f-min-price" name="min_price" min="0" step="100" value="{{ request('min_price') }}" placeholder="0">
                </div>
                <div class="filter-group">
                    <label for="f-max-price">Max Price (KSh)</label>
                    <input type="number" id="f-max-price" name="max_price" min="0" step="100" value="{{ request('max_price') }}" placeholder="10,000,000">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <label style="display:flex;gap:.4rem;font-weight:400;margin:.2rem 0;">
                        <input type="checkbox" name="in_stock_only" value="1" @checked(request('in_stock_only'))> In stock only
                    </label>
                    <label style="display:flex;gap:.4rem;font-weight:400;margin:.2rem 0;">
                        <input type="checkbox" name="sale" value="1" @checked(request('sale'))> On sale
                    </label>
                    <label style="display:flex;gap:.4rem;font-weight:400;margin:.2rem 0;">
                        <input type="checkbox" name="featured" value="1" @checked(request('featured'))> Featured
                    </label>
                </div>
                <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">
                <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                <a href="{{ route('shop.index') }}" style="display:block;text-align:center;margin-top:.7rem;font-size:.85rem;">Clear filters</a>
            </form>
        </aside>

        <div>
            <div class="toolbar">
                <span class="count">Showing {{ $products->firstItem() ?? 0 }} – {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</span>
                <form action="{{ route('shop.index') }}" method="GET" style="display:flex;align-items:center;gap:.6rem;">
                    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                    @if(request('brand'))<input type="hidden" name="brand" value="{{ request('brand') }}">@endif
                    @if(request('min_price'))<input type="hidden" name="min_price" value="{{ request('min_price') }}">@endif
                    @if(request('max_price'))<input type="hidden" name="max_price" value="{{ request('max_price') }}">@endif
                    @if(request('sale'))<input type="hidden" name="sale" value="1">@endif
                    @if(request('featured'))<input type="hidden" name="featured" value="1">@endif
                    @if(request('in_stock_only'))<input type="hidden" name="in_stock_only" value="1">@endif
                    <label for="sort" style="font-size:.85rem;color:var(--muted);">Sort by</label>
                    <select name="sort" id="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="latest" @selected(request('sort') === 'latest')>Latest</option>
                        <option value="popularity" @selected(request('sort') === 'popularity')>Popularity</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                        <option value="name_asc" @selected(request('sort') === 'name_asc')>Name: A to Z</option>
                    </select>
                </form>
            </div>

            @if($products->count())
                <ul class="product-grid reveal">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </ul>
                <div class="pagination">{{ $products->links() }}</div>
            @else
                <div class="search-empty">
                    <h2>No products found</h2>
                    <p>Try adjusting your filters or search for something else.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:1rem;">Browse All Products</a>
                </div>
            @endif
        </div>
    </div>
@endsection