<div class="topbar">
    <div class="container">
        <div class="topbar-left">
            <a href="mailto:{{ site_email() }}">{{ site_email() }}</a>
            @if(setting('phone_1'))
                <span class="sep">|</span>
                <a href="{{ tel_link() }}">{{ setting('phone_1') }}</a>
            @endif
        </div>
        <div class="topbar-right">
            <a href="{{ route('about') }}">About Us</a>
            <a href="{{ route('services') }}">Services</a>
            <a href="{{ route('shop.index') }}">Shop</a>
            <a href="{{ route('contact.index') }}">Contact Us</a>
            <a href="{{ url('/admin') }}" aria-label="Admin panel">Admin</a>
        </div>
    </div>
</div>