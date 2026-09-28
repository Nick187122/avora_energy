@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')
    <div class="admin-head">
        <div>
            <h2>{{ isset($category) ? 'Edit Category' : 'Add Category' }}</h2>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">← Back</a>
    </div>

    <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        @isset($category) @method('PUT') @endisset

        <div class="form-grid">
            <div class="form-group full {{ $errors->has('name') ? 'field-error' : '' }}">
                <label for="name">Category Name <span class="req">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $category->slug ?? '') }}" placeholder="Auto-generates">
                @error('slug') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="parent_id">Parent Category</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">— None (top level) —</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? '') == $parent->id)>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="image">Category Image</label>
                <input type="file" name="image" id="image" accept="image/*">
                @isset($category)
                    @if($category->image)
                        <img src="{{ asset($category->image) }}" style="width:120px;border-radius:8px;margin-top:.4rem;" alt="">
                    @endif
                @endisset
            </div>

            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}" placeholder="0">
            </div>

            <div class="form-group">
                <label>Status</label>
                <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))> Active
                </label>
            </div>

            <div class="form-group full">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
                <span class="hint">Shown on the category page and used in SEO.</span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save Category</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection