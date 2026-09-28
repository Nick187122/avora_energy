@extends('layouts.app')

@section('content')
    @include('partials.flash')

    <div class="page-hero">
        <div class="container">
            <nav class="breadcrumb"><a href="{{ route('home') }}">Home</a> &raquo; <span>Contact</span></nav>
            <h1>Get in touch with {{ site_name() }}.</h1>
            <p>Questions, quotes, site visits or support — our team is ready to help.</p>
        </div>
    </div>

    <div class="container contact-layout">
        <div>
            <div class="contact-card">
                <div class="icon-chip">&#9993;</div>
                <h4>Email Us</h4>
                <p><a href="mailto:{{ site_email() }}">{{ site_email() }}</a></p>
            </div>

            <div class="contact-card">
                <div class="icon-chip">&#9742;</div>
                <h4>Call Us</h4>
                @if(setting('phone_1'))
                    <p><a href="{{ tel_link() }}">{{ site_phone() }}</a></p>
                @endif
                @if(setting('phone_2'))
                    <p><a href="{{ tel_link(setting('phone_2')) }}">{{ setting('phone_2') }}</a></p>
                @endif
            </div>

            @if(setting('whatsapp_number'))
                <div class="contact-card">
                    <div class="icon-chip">WhatsApp</div>
                    <h4>WhatsApp</h4>
                    <p><a href="{{ wa_link('Hello ' . site_name() . ', I would like to make an enquiry.') }}" target="_blank" rel="noopener">{{ wa_number() }}</a></p>
                </div>
            @endif

            @if(setting('address_1') || setting('address_2'))
                <div class="contact-card">
                    <div class="icon-chip">&#9873;</div>
                    <h4>Visit Our Showrooms</h4>
                    @if(setting('address_1'))
                        <p>{{ setting('address_1') }}</p>
                    @endif
                    @if(setting('address_2'))
                        <p>{{ setting('address_2') }}</p>
                    @endif
                </div>
            @endif

            @if(setting('map_embed'))
                <div class="map-embed">
                    {!! setting('map_embed') !!}
                </div>
            @endif
        </div>

        <div class="contact-card">
            <h4>Send Us a Message</h4>
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group {{ $errors->has('name') ? 'field-error' : '' }} full">
                        <label for="name">Your Name <span class="req">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'field-error' : '' }}">
                        <label for="email">Email <span class="req">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group {{ $errors->has('phone') ? 'field-error' : '' }}">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                        @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group {{ $errors->has('subject') ? 'field-error' : '' }} full">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject') }}">
                        @error('subject') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group {{ $errors->has('message') ? 'field-error' : '' }} full">
                        <label for="message">Message <span class="req">*</span></label>
                        <textarea name="message" id="message" class="form-control" required>{{ old('message') }}</textarea>
                        @error('message') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div style="display:none;">
                        <label>Website</label>
                        <input type="text" name="honeypot" value="" tabindex="-1" autocomplete="off">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send Message</button>
            </form>
        </div>
    </div>
@endsection