@extends('layouts.admin')

@section('title', 'Add Brand')

@section('content')
    <div class="admin-head">
        <div>
            <h2>{{ isset($brand) ? 'Edit Brand' : 'Add Brand' }}</h2>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline">← Back</a>
    </div>

    <form action="{{ isset($brand) ? route('admin.brands.update', $brand) : route('admin.brands.store') }}"
          method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        @isset($brand) @method('PUT') @endisset

        <div class="form-grid">
            <div class="form-group full">
                <label for="name">Brand Name <span class="req">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $brand->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $brand->slug ?? '') }}">
            </div>
            <div class="form-group">
                <label for="logo">Logo</label>
                <input type="file" name="logo" id="logo" accept="image/*">
                @isset($brand)
                    @if($brand->logo)
                        <img src="{{ asset($brand->logo) }}" style="width:110px;border-radius:8px;margin-top:.4rem;background:var(--a-light);padding:.4rem;" alt="">
                    @endif
                @endisset
            </div>
            <div class="form-group">
                <label>Status</label>
                <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $brand->is_active ?? true))> Active
                </label>
            </div>
            <div class="form-group full">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $brand->description ?? '') }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save Brand</button>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection