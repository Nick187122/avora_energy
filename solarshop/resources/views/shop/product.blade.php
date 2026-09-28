@extends('layouts.app')

@section('content')
    @include('partials.flash')

    <div class="page-hero">
        <div class="container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">Home</a> &raquo;
                @if($product->category)
                    <a href="{{ route('category.show', $product->category->slug) }}">{{ $product->category->name }}</a> &raquo;
                @endif
                <span>{{ \Illuminate\Support\Str::limit($product->name, 50) }}</span>
            </nav>
            <h1>{{ $product->name }}</h1>
        </div>
    </div>

    <div class="container product-layout">
        <div class="gallery-wrap">
            @php
                $images = collect([$product->image])->merge($product->gallery ?? [])->filter();
                $mainImage = product_image($product->image ?: null) ?: 'img/placeholder.png';
            @endphp
            <div class="gallery-main">
                <img src="{{ asset($mainImage) }}" alt="{{ $product->name }}">
            </div>
            @if($images->count() > 1)
                <div class="gallery-thumbs">
                    @foreach($images as $image)
                        <button type="button" class="{{ $loop->first ? 'active' : '' }}" data-src="{{ asset($image) }}">
                            <img src="{{ asset(product_image($image) ?: 'img/placeholder.png') }}" alt="gallery">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="product-info">
            @if($product->brand)
                <div class="p-brand">
                    Brand: <a href="{{ route('brand.show', $product->brand->slug) }}">{{ $product->brand->name }}</a>
                </div>
            @endif

            <div class="price-block">
                @if($product->has_sale)
                    <span class="price"><del>{{ money((float) $product->regular_price) }}</del> <ins>{{ money($product->display_price) }}</ins></span>
                    <span class="save-tag">Save {{ $product->discount_percent }}%</span>
                @else
                    <span class="price">{{ money($product->display_price) }}</span>
                @endif
            </div>

            <div class="stock-line">
                @if($product->is_in_stock)
                    <span class="in-stock">&#10003; In Stock</span>
                @else
                    <span class="out">&#10007; Out of Stock</span>
                @endif
                @if($product->sku)
                    <span>SKU: {{ $product->sku }}</span>
                @endif
                <span>{{ $product->is_featured ? '| Featured' : '' }}</span>
            </div>

            @if($product->short_description)
                <p style="margin-top:.8rem;">{{ $product->short_description }}</p>
            @endif

            <form action="{{ route('cart.add') }}" method="POST" class="qty-form buy-row" data-auto="0">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="qty-box">
                    <button type="button" data-dir="-">&minus;</button>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock > 0 ? $product->stock : 100 }}">
                    <button type="button" data-dir="+">+</button>
                </div>
                @if($product->is_in_stock)
                    <button type="submit" class="btn btn-orange btn-lg">Add to Cart</button>
                @else
                    <a href="{{ route('contact.index') }}" class="btn btn-primary btn-lg">Contact Us</a>
                @endif
            </form>

            <div class="meta-list">
                @if($product->category)
                    <span><strong>Category:</strong> {{ $product->category->name }}</span>
                @endif
                @if($product->specifications && count($product->specifications))
                    <span><strong>Highlights:</strong> {{ implode(' • ', array_slice($product->specifications, 0, 3)) }}</span>
                @endif
                <span><strong>Delivery:</strong> Free within Nairobi. Nationwide courier available. <a href="{{ route('page.show', 'delivery-and-return') }}">Delivery info</a></span>
                <span><strong>Warranty:</strong> Genuine warranty honoured in Kenya. <a href="{{ route('page.show', 'warranty-and-support') }}">Warranty support</a></span>
            </div>
        </div>
    </div>

    <div class="container spec-tabs">
        @php
            $hasDesc = !empty($product->description);
            $hasSpecs = !empty($product->specifications);
            $showDescFirst = $hasDesc;
        @endphp
        @if($hasDesc || $hasSpecs)
            <div class="tabs">
                @if($hasDesc)
                    <button type="button" class="{{ $showDescFirst ? 'active' : '' }}">Description</button>
                @endif
                @if($hasSpecs)
                    <button type="button" class="{{ $showDescFirst ? '' : 'active' }}">Specifications</button>
                @endif
            </div>
            <div class="tab-content">
                @if($hasDesc)
                    <div class="tab-pane {{ $showDescFirst ? 'active' : '' }}">
                        <div class="wysiwyg">{!! $product->description !!}</div>
                    </div>
                @endif
                @if($hasSpecs)
                    <div class="tab-pane {{ $showDescFirst ? '' : 'active' }}">
                        <table class="spec-table">
                            @foreach($product->specifications as $key => $value)
                                <tr>
                                    <th>{{ $key }}</th>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if($related->count())
        <section class="section section-light">
            <div class="container">
                <div class="section-head">
                    <h2>Related Products</h2>
                </div>
                <ul class="product-grid reveal">
                    @foreach($related as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </ul>
            </div>
        </section>
    @endif
@endsection