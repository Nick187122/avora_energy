@extends('layouts.app')

@section('content')
    @include('partials.flash')

    {{-- Hero slider --}}
    <section class="hero" aria-label="Featured products">
        <div class="hero-slides">
            @foreach($slides as $slide)
                <div class="hero-slide {{ $loop->first ? 'active' : '' }}"
                     style="background-image: url('{{ asset($slide->image) }}');">
                    <div class="container hero-content">
                        <span class="eyebrow">{{ $slide->subtitle ?? site_name() }}</span>
                        <h1>{{ $slide->title }}</h1>
                        @if($slide->description)
                            <p>{{ $slide->description }}</p>
                        @endif
                        @if($slide->button_text)
                            <div class="hero-buttons">
                                <a href="{{ $slide->button_url ?: route('shop.index') }}" class="btn btn-outline btn-lg btn-arrow">{{ $slide->button_text }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="hero-dots"></div>
        </div>
    </section>

    {{-- Value propositions --}}
    @if($valueProps->count())
        <section class="section section-pale">
            <div class="container">
                <div class="value-grid reveal">
                    @foreach($valueProps as $prop)
                        <div class="value-item">
                            @if($prop->icon)
                                <img src="{{ asset($prop->icon) }}" alt="{{ $prop->title }}">
                            @else
                                <div style="width:60px;height:60px;border-radius:12px;background:var(--blue-soft);color:var(--blue);display:flex;align-items:center;justify-content:center;font-size:1.4rem;">â˜…</div>
                            @endif
                            <div>
                                <h4>{{ $prop->title }}</h4>
                                <p>{{ $prop->subtitle }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Category cards --}}
    @if($categories->count())
        <section class="section">
            <div class="container">
                <div class="cat-grid reveal">
                    @foreach($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="cat-card" style="background-image: url('{{ $category->image ? asset($category->image) : '' }}');">
                            <div>
                                <h3>{{ $category->name }}</h3>
                                @if($category->description)
                                    <p>{{ \Illuminate\Support\Str::limit($category->description, 70) }}</p>
                                @endif
                                <span class="btn btn-outline">View More</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Top deals --}}
    @if($topDeals->count())
        <section class="section section-light">
            <div class="container">
                <div class="section-head reveal">
                    <span class="eyebrow">Grab the savings</span>
                    <h2>Top Deals of the Day</h2>
                    <p>Best prices on genuine solar products â€” perfect for new installations &amp; upgrades.</p>
                </div>
                <ul class="product-grid reveal">
                    @foreach($topDeals as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </ul>
                <div class="view-all">
                    <a href="{{ route('shop.index') }}?sale=1" class="btn btn-primary">See All Deals</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Bestsellers --}}
    @if($bestsellers->count())
        <section class="section">
            <div class="container">
                <div class="section-head reveal">
                    <span class="eyebrow">Customer favourites</span>
                    <h2>Bestseller Products</h2>
                    <p>Great deals recommended for you.</p>
                </div>
                <ul class="product-grid grid-3 reveal">
                    @foreach($bestsellers as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </ul>
                <div class="view-all">
                    <a href="{{ route('shop.index') }}" class="btn btn-blue-outline">View More</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Featured products --}}
    @if($featured->count())
        <section class="section section-pale">
            <div class="container">
                <div class="section-head reveal">
                    <span class="eyebrow">Hand-picked</span>
                    <h2>Featured Products</h2>
                    <p>Save Smart with Second Chances.</p>
                </div>
                <ul class="product-grid reveal">
                    @foreach($featured as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </ul>
                <div class="view-all">
                    <a href="{{ route('shop.index') }}?featured=1" class="btn btn-blue-outline">View More</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Promote banners --}}
    <section class="section">
        <div class="container">
            <div class="banner-row reveal">
                <div class="banner-card" style="background-image: url('{{ asset('img/banner-flash.jpg') }}');">
                    <div>
                        <h3>SOLAR FLASH SALES</h3>
                        <p>Blazing flash deals gone before the sun sets. Limited stock at unbeatable prices.</p>
                        <a href="{{ route('shop.index') }}?sale=1" class="btn btn-orange">View More</a>
                    </div>
                </div>
                <div class="banner-card" style="background-image: url('{{ asset('img/banner-friday.jpg') }}');">
                    <div>
                        <h3>BLACK FRIDAY DEALS</h3>
                        <p>Harvest massive savings this Black Friday. Panels, batteries &amp; inverters included.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-orange">View More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Reference images in seed banners use gradient fallback if missing --}}
    <style>
        .banner-card:nth-child(1) { background-color: var(--blue); }
        .banner-card:nth-child(2) { background-color: var(--orange-dark); }
    </style>

    {{-- Solar inverters --}}
    @if($inverters->count())
        <section class="section section-light">
            <div class="container">
                <div class="section-head reveal">
                    <span class="eyebrow">Power that never quits</span>
                    <h2>Solar Inverters &amp; Batteries</h2>
                    <p>Hybrid inverters and LiFePO4 batteries for homes and business.</p>
                </div>
                <ul class="product-grid reveal">
                    @foreach($inverters as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Recommended --}}
    @if($recommended->count())
        <section class="section">
            <div class="container">
                <div class="section-head reveal">
                    <span class="eyebrow">Picked for you</span>
                    <h2>Recommended for You</h2>
                </div>
                <ul class="product-grid reveal">
                    @foreach($recommended as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </ul>
                <div class="view-all">
                    <a href="{{ route('shop.index') }}" class="btn btn-blue-outline">Browse All Products</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Partners --}}
    @if($partners->count())
        <section class="section section-pale">
            <div class="container">
                <div class="section-head reveal">
                    <h2>Our Partners</h2>
                    <p>We partner with the world's leading solar brands.</p>
                </div>
                <div class="partner-grid reveal">
                    @foreach($partners as $partner)
                        @if($partner->logo)
                            <div class="partner-cell" title="{{ $partner->name }}">
                                <img src="{{ asset($partner->logo) }}" alt="{{ $partner->name }}" loading="lazy">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Value pillars --}}
    <section class="section section-light">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow">Why {{ site_name() }}</span>
                <h2>Built on Four Pillars of Trust</h2>
            </div>
            <div class="pillar-grid reveal">
                <div class="pillar-card">
                    <div class="pillar-icon">01</div>
                    <h4>Online + Physical</h4>
                    <p>Browse online or visit our physical presence across the Nairobi Metropolitan region.</p>
                </div>
                <div class="pillar-card">
                    <div class="pillar-icon">02</div>
                    <h4>10+ Years Experience</h4>
                    <p>Over ten years of hands-on experience delivering residential and commercial projects.</p>
                </div>
                <div class="pillar-card">
                    <div class="pillar-icon">03</div>
                    <h4>Genuine Warranty</h4>
                    <p>We sell original products, honor warranty in Kenya and stock local spares.</p>
                </div>
                <div class="pillar-card">
                    <div class="pillar-icon">04</div>
                    <h4>Full Project Service</h4>
                    <p>From design and procurement to installation, commissioning and O&amp;M.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
