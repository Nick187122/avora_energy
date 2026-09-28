@extends('layouts.admin')

@section('title', 'Edit Product')

@section('styles')
    <style>
        #imagePreview { width: 140px; height: 100px; object-fit: cover; border: 1px solid var(--a-border); border-radius: 8px; margin-top: .5rem; }
    </style>
@endsection

@section('content')
    <div class="admin-head">
        <div>
            <h2>Edit Product</h2>
            <p>{{ \Illuminate\Support\Str::limit($product->name, 60) }}</p>
        </div>
        <div style="display:flex;gap:.5rem;">
            <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn btn-outline">View on Site</a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf @method('PUT')

        <div class="form-grid">
            <div class="form-group full {{ $errors->has('name') ? 'field-error' : '' }}">
                <label for="name">Product Name <span class="req">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $product->slug) }}">
                @error('slug') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                @error('sku') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">— None —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="brand_id">Brand</label>
                <select name="brand_id" id="brand_id" class="form-control">
                    <option value="">— None —</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" @selected(old('brand_id', $product->brand_id) == $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group {{ $errors->has('regular_price') ? 'field-error' : '' }}">
                <label for="regular_price">Regular Price (KSh) <span class="req">*</span></label>
                <input type="number" step="0.01" min="0" name="regular_price" id="regular_price" class="form-control" value="{{ old('regular_price', $product->regular_price) }}" required>
                @error('regular_price') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group {{ $errors->has('sale_price') ? 'field-error' : '' }}">
                <label for="sale_price">Sale Price (KSh)</label>
                <input type="number" step="0.01" min="0" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
                @error('sale_price') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity <span class="req">*</span></label>
                <input type="number" min="0" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
            </div>

            <div class="form-group">
                <label>Flags</label>
                <div style="display:flex;gap:1.2rem;">
                    <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured
                    </label>
                    <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))> Active
                    </label>
                </div>
            </div>

            <div class="form-group full">
                <label for="short_description">Short Description</label>
                <input type="text" name="short_description" id="short_description" class="form-control" value="{{ old('short_description', $product->short_description) }}">
            </div>

            <div class="form-group full">
                <label for="description">Full Description (HTML allowed)</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Main Image</label>
                <input type="file" name="image" id="image" accept="image/*" data-preview="#imagePreview">
                <img id="imagePreview" src="{{ $product->image ? asset($product->image) : '#' }}" alt="Preview">
            </div>

            <div class="form-group">
                <label for="meta_title">SEO Title</label>
                <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title) }}">
            </div>

            <div class="form-group">
                <label for="meta_description">SEO Description</label>
                <input type="text" name="meta_description" id="meta_description" class="form-control" value="{{ old('meta_description', $product->meta_description) }}">
            </div>
        </div>

        <hr style="border:none;border-top:1px solid var(--a-border);margin:1.2rem 0;">

        <div class="form-group">
            <label>Gallery Images</label>
            <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple>
            <span class="hint">Upload more images to add to the gallery. Use the checkbox below to clear existing gallery images.</span>

            @if(! empty($product->gallery))
                <div class="gallery-grid mt-2">
                    @foreach($product->gallery as $image)
                        <div class="g-item">
                            <img src="{{ asset($image) }}" alt="gallery image">
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-2">
                <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                    <input type="checkbox" name="remove_gallery_images" value="1"> Remove all existing gallery images
                </label>
            </div>
        </div>

        <hr style="border:none;border-top:1px solid var(--a-border);margin:1.2rem 0;">

        <div class="spec-rows">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
                <label style="font-weight:600;font-size:.9rem;color:var(--a-heading);">Specifications</label>
                <div style="display:flex;gap:.6rem;">
                    <button type="button" class="btn btn-sm btn-outline add-spec">＋ Add Specification</button>
                </div>
            </div>
            <div class="spec-rows-list">
                @if($product->specifications)
                    @foreach($product->specifications as $specKey => $specValue)
                        <div class="spec-row">
                            <input type="text" name="spec_keys[]" class="form-control" placeholder="Property" value="{{ $specKey }}">
                            <input type="text" name="spec_values[]" class="form-control" placeholder="Value" value="{{ $specValue }}">
                            <button type="button" class="btn btn-sm btn-danger remove-spec">&times;</button>
                        </div>
                    @endforeach
                @endif
                @if(old('spec_keys'))
                    @foreach(old('spec_keys') as $i => $key)
                        @if(! is_null($key))
                            <div class="spec-row">
                                <input type="text" name="spec_keys[]" class="form-control" placeholder="Property" value="{{ $key }}">
                                <input type="text" name="spec_values[]" class="form-control" placeholder="Value" value="{{ old('spec_values')[$i] ?? '' }}">
                                <button type="button" class="btn btn-sm btn-danger remove-spec">&times;</button>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <label class="mt-2" style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                <input type="checkbox" name="clear_specs" value="1"> Clear all specifications
            </label>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-lg">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
@endsection