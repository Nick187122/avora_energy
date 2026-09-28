@extends('layouts.admin')

@section('title', 'Hero Slide')

@section('content')
    <div class="admin-head">
        <div>
            <h2>{{ isset($heroSlide) ? 'Edit Slide' : 'Add Slide' }}</h2>
        </div>
        <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-outline">← Back</a>
    </div>

    <form action="{{ isset($heroSlide) ? route('admin.hero-slides.update', $heroSlide) : route('admin.hero-slides.store') }}"
          method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        @isset($heroSlide) @method('PUT') @endisset

        <div class="form-grid">
            <div class="form-group full">
                <label for="title">Title <span class="req">*</span></label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $heroSlide->title ?? '') }}" required>
            </div>
            <div class="form-group full">
                <label for="subtitle">Subtitle / Eyebrow</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $heroSlide->subtitle ?? '') }}">
            </div>
            <div class="form-group full">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $heroSlide->description ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="image">Background Image <span class="req">*</span></label>
                <input type="file" name="image" id="image" accept="image/*" @isset($heroSlide) @else required @endisset>
                @isset($heroSlide)
                    @if($heroSlide->image)
                        <img src="{{ asset($heroSlide->image) }}" alt="" style="width:200px;border-radius:8px;margin-top:.4rem;">
                    @endif
                @endisset
            </div>
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $heroSlide->sort_order ?? 0) }}">
            </div>
            <div class="form-group">
                <label for="button_text">Button Text</label>
                <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text', $heroSlide->button_text ?? '') }}">
            </div>
            <div class="form-group">
                <label for="button_url">Button Link</label>
                <input type="text" name="button_url" id="button_url" class="form-control" value="{{ old('button_url', $heroSlide->button_url ?? '') }}" placeholder="https://... or /shop">
            </div>
            <div class="form-group">
                <label>Status</label>
                <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $heroSlide->is_active ?? true))> Active
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save Slide</button>
        <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection