<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $metaDescription ?? (setting('meta_description', 'Avora Energy Solutions - Solar Panels, Water Heaters, Pumps, Inverters, Batteries, Lights & More')) }}">
    <title>{{ $title ?? setting('site_name', 'Avora Energy Solutions') }}@if(isset($title)) | {{ site_name() }}@endif</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script>document.documentElement.classList.add('js');</script>
    @stack('styles')
</head>
<body>
    @include('partials.flash')

    <header class="site-header">
        @include('partials.topbar')
        <div class="container header-inner">
            <button type="button" class="menu-toggle" aria-label="Open menu" aria-controls="mobile-menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            <a href="{{ route('home') }}" class="logo" title="{{ site_name() }}">
                <img src="{{ asset(setting('logo', 'img/logo-wide.png')) }}" alt="{{ site_name() }} logo">
                <span class="logo-text">
                    <span class="logo-name">{{ site_name() }}</span>
                    @if(setting('site_tagline'))
                        <span class="logo-tagline">{{ setting('site_tagline') }}</span>
                    @endif
                </span>
            </a>

            <div class="header-search">
                <form action="{{ route('search.index') }}" method="GET" role="search">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="I am shopping for ..." aria-label="Search products">
                    <button type="submit" aria-label="Search">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                    </button>
                </form>
            </div>

            <div class="header-icons">
                <a href="{{ route('cart.index') }}" class="header-icon" aria-label="Shopping cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><circle cx="9" cy="20" r="1.6"/><circle cx="18.5" cy="20" r="1.6"/></svg>
                    <span>Cart</span>
                    @if(cart_count() > 0)
                        <span class="cart-count">{{ cart_count() }}</span>
                    @endif
                </a>
                <div class="contact-menu" data-contact-menu>
                    <button type="button" class="header-icon contact-trigger" aria-expanded="false" aria-haspopup="true" aria-controls="contact-menu-list">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/></svg>
                        <span>Contact</span>
                    </button>
                    <div class="contact-dropdown" id="contact-menu-list" role="menu">
                        <a href="{{ tel_link() }}" role="menuitem">
                            <span class="ci ci-call" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/></svg>
                            </span>
                            <span class="ct">
                                <strong>Call us</strong>
                                <small>{{ site_phone() }}</small>
                            </span>
                        </a>
                        <a href="{{ wa_link('Hello ' . site_name() . ', I would like to make an enquiry.') }}" target="_blank" rel="noopener" role="menuitem">
                            <span class="ci ci-wa" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M17.5 14.4c-.3-.2-1.7-.9-2-1-.3-.1-.5-.1-.7.1-.2.3-.7 1-.9 1.2-.2.2-.3.2-.6.1-1.7-.9-2.9-1.6-4-3.5-.3-.5.3-.5.8-1.5.1-.2 0-.4 0-.5 0-.2-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.2.2 2.1 3.3 5.2 4.6.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.2.2-1.4-.1-.1-.3-.2-.6-.3Z"/><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm0 18.2c-1.6 0-3-.4-4.3-1.2l-.3-.2-3.1.8.8-3-.2-.3A8.2 8.2 0 1 1 12 20.2Z"/></svg>
                            </span>
                            <span class="ct">
                                <strong>WhatsApp us</strong>
                                <small>{{ site_phone() }}</small>
                            </span>
                        </a>
                        <a href="{{ route('contact.index') }}" role="menuitem">
                            <span class="ci ci-mail" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/></svg>
                            </span>
                            <span class="ct">
                                <strong>Send an email</strong>
                                <small>{{ site_email() }}</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.nav')
    </header>

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <div class="mobile-menu" id="mobile-menu" aria-hidden="true">
        <div class="m-head">
            <a href="{{ route('home') }}" class="m-logo">
                <img src="{{ asset(setting('logo', 'img/logo-wide.png')) }}" alt="{{ site_name() }}">
                <span class="m-logo-text">{{ site_name() }}</span>
            </a>
            <button type="button" class="m-close" aria-label="Close menu">&times;</button>
        </div>
        <nav aria-label="Mobile navigation">
            <ul class="m-list">
                @php
                    $mCats = \App\Models\Category::whereNull('parent_id')
                        ->where('is_active', true)
                        ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
                        ->orderBy('sort_order')->orderBy('name')
                        ->get();
                @endphp
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('shop.index') }}">Shop All</a></li>
                @foreach($mCats as $cat)
                    @if($cat->children->count())
                        <li>
                            <button type="button" class="m-toggle" aria-expanded="false">{{ $cat->name }}</button>
                            <ul class="m-sub">
                                @foreach($cat->children as $child)
                                    <li><a href="{{ route('category.show', $child->slug) }}">{{ $child->name }}</a></li>
                                @endforeach
                                <li><a href="{{ route('category.show', $cat->slug) }}">View all {{ $cat->name }}</a></li>
                            </ul>
                        </li>
                    @else
                        <li><a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endif
                @endforeach
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('services') }}">Services</a></li>
                <li><a href="{{ route('contact.index') }}">Contact</a></li>
                <li><a href="{{ url('/admin') }}">Admin</a></li>
            </ul>
        </nav>
    </div>
    <div class="menu-overlay" id="menu-overlay"></div>

    <button type="button" class="back-to-top" id="back-to-top" aria-label="Back to top">&#8679;</button>

    <nav class="mobile-bottom" aria-label="Mobile bottom navigation">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('shop.index') }}">Shop</a>
        <a href="{{ route('cart.index') }}">Cart</a>
        <a href="{{ wa_link('Hello ' . site_name() . ', I would like to make an enquiry.') }}" target="_blank" rel="noopener">WhatsApp</a>
        <a href="{{ route('contact.index') }}">Contact</a>
    </nav>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>