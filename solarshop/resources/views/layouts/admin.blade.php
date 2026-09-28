<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | {{ site_name() }} Admin</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
<div class="admin-wrap">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}" class="sidebar-brand-link" target="_blank">
                <img src="{{ asset(setting('logo', 'img/logo-wide.png')) }}" alt="{{ site_name() }} logo">
                <span class="sb-name">{{ site_name() }}</span>
            </a>
            <small>{{ site_name() }} Admin</small>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>📊</span> <span>Dashboard</span>
            </a>

            <div class="nav-label">Catalog</div>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <span>📦</span> <span>Products</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <span>🗂️</span> <span>Categories</span>
            </a>
            <a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
                <span>🏷️</span> <span>Brands</span>
            </a>

            <div class="nav-label">Storefront</div>
            <a href="{{ route('admin.hero-slides.index') }}" class="{{ request()->routeIs('admin.hero-slides*') ? 'active' : '' }}">
                <span>🎠</span> <span>Hero Slides</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                <span>📄</span> <span>Pages</span>
            </a>
            <a href="{{ route('admin.partners.index') }}" class="{{ request()->routeIs('admin.partners*') ? 'active' : '' }}">
                <span>🤝</span> <span>Partners</span>
            </a>
            <a href="{{ route('admin.value-propositions.index') }}" class="{{ request()->routeIs('admin.value-propositions*') ? 'active' : '' }}">
                <span>💎</span> <span>Value Props</span>
            </a>

            <div class="nav-label">Sales</div>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <span>🧾</span> <span>Orders</span>
            </a>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                <span>✉️</span> <span>Messages</span>
                @if(\App\Models\ContactMessage::where('is_read', false)->count() > 0)
                    <span class="badge">{{ \App\Models\ContactMessage::where('is_read', false)->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.subscribers.index') }}" class="{{ request()->routeIs('admin.subscribers*') ? 'active' : '' }}">
                <span>✉️</span> <span>Subscribers</span>
            </a>

            <div class="nav-label">Configuration</div>
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <span>⚙️</span> <span>Site Settings</span>
            </a>
            <a href="{{ route('admin.admins.index') }}" class="{{ request()->routeIs('admin.admins*') ? 'active' : '' }}">
                <span>👤</span> <span>Admin Users</span>
            </a>
        </nav>
        <div class="sidebar-foot">v1.0.0 &middot; Avora Energy Solutions</div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <h1>@yield('title', 'Dashboard')</h1>
            <div class="topbar-user">
                <a href="{{ wa_link() }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline">WhatsApp</a>
                <a href="{{ tel_link() }}" class="btn btn-sm btn-outline">Call {{ site_phone() }}</a>
                <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline">View Site</a>
                <div class="avatar">{{ \Illuminate\Support\Str::substr(\App\Models\Admin::find(auth('admin')->id())->name ?? 'A', 0, 1) }}</div>
                <span>{{ auth('admin')->user()->name }}</span>
                <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                </form>
            </div>
        </header>

        <main class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">&#10003; {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">&#10007; {{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error) {{ $error }} @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="{{ asset('js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>