@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Site Settings</h2>
            <p>Manage global site information. Saved automatically to the settings table.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')

        @foreach($groups as $group => $keys)
            <div class="settings-group">
                <h3>{{ ucfirst($group) }}</h3>
                <div class="form-grid">
                    @foreach($keys as $key)
                        @php
                            $value = setting($key);
                        @endphp
                        @if($key === 'logo')
                            <div class="form-group full">
                                <label for="logo">Site Logo</label>
                                <input type="file" name="logo" id="logo" accept="image/*">
                                <div style="display:flex;align-items:center;gap:.6rem;margin-top:.5rem;">
                                    <img src="{{ $value ? asset($value) : asset('img/logo-wide.png') }}" alt="Logo preview" style="height:52px;background:var(--a-light);padding:0.3rem;border-radius:8px;">
                                    <span class="hint">PNG/JPG/WEBP, max 2MB. Falls back to the default logo.html</span>
                                </div>
                            </div>
                        @elseif(str_ends_with($key, '_url') || $key === 'facebook' || $key === 'instagram' || $key === 'youtube' || $key === 'linkedin' || $key === 'tiktok')
                            <div class="form-group">
                                <label for="{{ $key }}">{{ str_replace('_', ' ', ucfirst($key)) }}</label>
                                <input type="url" name="{{ $key }}" id="{{ $key }}" class="form-control" value="{{ old($key, $value) }}" placeholder="https://...">
                            </div>
                        @elseif(in_array($key, ['meta_description', 'site_tagline', 'address_1', 'address_2', 'footer_copyright', 'delivery_note', 'map_embed'], true))
                            <div class="form-group full">
                                <label for="{{ $key }}">{{ str_replace('_', ' ', ucfirst($key)) }}</label>
                                <textarea name="{{ $key }}" id="{{ $key }}" class="form-control">{{ old($key, $value) }}</textarea>
                            </div>
                        @elseif($key === 'email')
                            <div class="form-group">
                                <label for="email">{{ ucfirst($key) }}</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $value) }}">
                            </div>
                        @else
                            <div class="form-group">
                                <label for="{{ $key }}">{{ str_replace('_', ' ', ucfirst($key)) }}</label>
                                <input type="text" name="{{ $key }}" id="{{ $key }}" class="form-control" value="{{ old($key, $value) }}" placeholder="{{ str_replace('_', ' ', ucfirst($key)) }}">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary btn-lg">Save All Settings</button>
    </form>
@endsection