@extends('layouts.admin')

@section('title', 'Add Product')

@section('styles')
    <style>
        #imagePreview { width: 140px; height: 100px; object-fit: cover; border: 1px solid var(--a-border); border-radius: 8px; margin-top: .5rem; display: {{ old('image') ? 'block' : 'none' }}; }
    </style>
@endsection

@section('content')
    <div class="admin-head">
        <div>
            <h2>Add New Product</h2>
            <p>Create a new product in the catalog.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline">← Back to Products</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf

        <div class="form-grid">
            <div class="form-group full {{ $errors->has('name') ? 'field-error' : '' }}">
                <label for="name">Product Name <span class="req">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" placeholder="Auto-generates from name">
                <span class="hint">Leave blank to auto-generate.</span>
                @error('slug') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku') }}">
                @error('sku') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">— None —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="brand_id">Brand</label>
                <select name="brand_id" id="brand_id" class="form-control">
                    <option value="">— None —</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" @selected(old('brand_id') == $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group {{ $errors->has('regular_price') ? 'field-error' : '' }}">
                <label for="regular_price">Regular Price (KSh) <span class="req">*</span></label>
                <input type="number" step="0.01" min="0" name="regular_price" id="regular_price" class="form-control" value="{{ old('regular_price') }}" required>
                @error('regular_price') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group {{ $errors->has('sale_price') ? 'field-error' : '' }}">
                <label for="sale_price">Sale Price (KSh)</label>
                <input type="number" step="0.01" min="0" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price') }}">
                <span class="hint">Leave blank for no discount. Must be less than regular price.</span>
                @error('sale_price') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity <span class="req">*</span></label>
                <input type="number" min="0" name="stock" id="stock" class="form-control" value="{{ old('stock', 0) }}" required>
            </div>

            <div class="form-group">
                <label>Flags</label>
                <div style="display:flex;gap:1.2rem;">
                    <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured'))> Featured
                    </label>
                    <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Active
                    </label>
                </div>
            </div>

            <div class="form-group full">
                <label for="short_description">Short Description</label>
                <input type="text" name="short_description" id="short_description" class="form-control" value="{{ old('short_description') }}">
            </div>

            <div class="form-group full">
                <label for="description">Full Description (HTML allowed)</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                <span class="hint">Basic HTML is allowed. Scripts &amp; dangerous tags are stripped automatically.</span>
            </div>

            <div class="form-group">
                <label for="image">Main Image</label>
                <input type="file" name="image" id="image" accept="image/*" data-preview="#imagePreview">
                <img id="imagePreview" src="#" alt="Preview">
            </div>

            <div class="form-group">
                <label for="meta_title">SEO Title</label>
                <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title') }}">
            </div>

            <div class="form-group">
                <label for="meta_description">SEO Description</label>
                <input type="text" name="meta_description" id="meta_description" class="form-control" value="{{ old('meta_description') }}">
            </div>
        </div>

        <hr style="border:none;border-top:1px solid var(--a-border);margin:1.2rem 0;">

        <div class="form-group">
            <label>Gallery Images</label>
            <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple>
            <span class="hint">Select multiple images. The main image is used as the cover.</span>
        </div>

        <hr style="border:none;border-top:1px solid var(--a-border);margin:1.2rem 0;">

        <div class="spec-rows">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
                <label style="font-weight:600;font-size:.9rem;color:var(--a-heading);">Specifications</label>
                <button type="button" class="btn btn-sm btn-outline add-spec">＋ Add Specification</button>
            </div>
            <div class="spec-rows-list">
                @if(old('spec_keys'))
                    @foreach(old('spec_keys') as $i => $key)
                        @if(! is_null($key) || ! is_null(old('spec_values')[$i] ?? null))
                            <div class="spec-row">
                                <input type="text" name="spec_keys[]" class="form-control" placeholder="Property" value="{{ $key }}">
                                <input type="text" name="spec_values[]" class="form-control" placeholder="Value" value="{{ old('spec_values')[$i] ?? '' }}">
                                <button type="button" class="btn btn-sm btn-danger remove-spec">&times;</button>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-lg">Save Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
@endsection