@extends('layouts.admin')

@section('title', 'Partner')

@section('content')
    <div class="admin-head">
        <div>
            <h2>{{ isset($partner) ? 'Edit Partner' : 'Add Partner' }}</h2>
        </div>
        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline">← Back</a>
    </div>

    <form action="{{ isset($partner) ? route('admin.partners.update', $partner) : route('admin.partners.store') }}"
          method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        @isset($partner) @method('PUT') @endisset

        <div class="form-grid">
            <div class="form-group full">
                <label for="name">Partner / Brand Name <span class="req">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $partner->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="logo">Logo</label>
                <input type="file" name="logo" id="logo" accept="image/*">
                @isset($partner)
                    @if($partner->logo)
                        <img src="{{ asset($partner->logo) }}" style="width:120px;border-radius:8px;margin-top:.4rem;background:var(--a-light);padding:.4rem;" alt="">
                    @endif
                @endisset
            </div>
            <div class="form-group">
                <label for="website_url">Website URL</label>
                <input type="url" name="website_url" id="website_url" class="form-control" value="{{ old('website_url', $partner->website_url ?? '') }}">
            </div>
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $partner->sort_order ?? 0) }}">
            </div>
            <div class="form-group">
                <label>Status</label>
                <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $partner->is_active ?? true))> Active
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save Partner</button>
        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection