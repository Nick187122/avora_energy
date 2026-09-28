@extends('layouts.admin')

@section('title', 'Edit Page')

@section('content')
    <div class="admin-head">
        <div>
            <h2>{{ isset($page) ? 'Edit Page' : 'Add Page' }}</h2>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline">← Back</a>
    </div>

    <form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
          method="POST" class="form-card">
        @csrf
        @isset($page) @method('PUT') @endisset

        <div class="form-grid">
            <div class="form-group full">
                <label for="title">Page Title <span class="req">*</span></label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $page->title ?? '') }}" required>
            </div>
            <div class="form-group full">
                <label for="slug">Slug (URL path)</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $page->slug ?? '') }}" placeholder="e.g. about-us">
                <span class="hint">Page will be available at <code>/{{ old('slug', $page->slug ?? 'page-slug') }}</code></span>
            </div>
            <div class="form-group full">
                <label for="content">Content (HTML allowed)</label>
                <textarea name="content" id="content" class="form-control" style="min-height:320px;">{{ old('content', $page->content ?? '') }}</textarea>
                <span class="hint">Basic HTML supported. Dangerous tags and scripts are stripped automatically.</span>
            </div>
            <div class="form-group">
                <label for="meta_title">SEO Title</label>
                <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title ?? '') }}">
            </div>
            <div class="form-group">
                <label for="meta_description">SEO Description</label>
                <input type="text" name="meta_description" id="meta_description" class="form-control" value="{{ old('meta_description', $page->meta_description ?? '') }}">
            </div>
            <div class="form-group">
                <label>Status</label>
                <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $page->is_active ?? true))> Published / Live
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save Page</button>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection