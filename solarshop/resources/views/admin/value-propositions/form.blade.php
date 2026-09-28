@extends('layouts.admin')

@section('title', 'Value Proposition')

@section('content')
    <div class="admin-head">
        <div>
            <h2>{{ isset($valueProposition) ? 'Edit Item' : 'Add Item' }}</h2>
        </div>
        <a href="{{ route('admin.value-propositions.index') }}" class="btn btn-outline">← Back</a>
    </div>

    <form action="{{ isset($valueProposition) ? route('admin.value-propositions.update', $valueProposition) : route('admin.value-propositions.store') }}"
          method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        @isset($valueProposition) @method('PUT') @endisset

        <div class="form-grid">
            <div class="form-group full">
                <label for="title">Title <span class="req">*</span></label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $valueProposition->title ?? '') }}" placeholder="e.g. FREE DELIVERY" required>
            </div>
            <div class="form-group full">
                <label for="subtitle">Subtitle <span class="req">*</span></label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $valueProposition->subtitle ?? '') }}" placeholder="e.g. For orders within Nairobi" required>
            </div>
            <div class="form-group">
                <label for="icon">Icon</label>
                <input type="file" name="icon" id="icon" accept="image/*">
                @isset($valueProposition)
                    @if($valueProposition->icon)
                        <img src="{{ asset($valueProposition->icon) }}" style="width:60px;object-fit:contain;margin-top:.4rem;" alt="">
                    @endif
                @endisset
            </div>
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $valueProposition->sort_order ?? 0) }}">
            </div>
            <div class="form-group">
                <label>Status</label>
                <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $valueProposition->is_active ?? true))> Active
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save</button>
        <a href="{{ route('admin.value-propositions.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection