@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Products</h2>
            <p>Manage your catalog — {{ $products->total() }} product(s)</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">＋ Add New Product</a>
    </div>

    <div class="filter-bar">
        <form action="{{ route('admin.products.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:.8rem;align-items:flex-end;width:100%;">
            <div class="form-group" style="flex:2;">
                <label for="search">Search</label>
                <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name, SKU or slug...">
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="brand_id">Brand</label>
                <select name="brand_id" id="brand_id" class="form-control">
                    <option value="">All</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" @selected(request('brand_id') == $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    <option value="out_of_stock" @selected(request('status') === 'out_of_stock')>Out of stock</option>
                    <option value="sale" @selected(request('status') === 'sale')>On sale</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Flags</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" class="thumb" alt="">
                            @else
                                <div class="thumb" style="background:var(--a-light);display:flex;align-items:center;justify-content:center;color:var(--a-muted);font-size:.7rem;">N/A</div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" style="font-weight:600;color:var(--a-heading);">{{ \Illuminate\Support\Str::limit($product->name, 42) }}</a>
                            <div class="text-muted" style="font-size:.75rem;">{{ $product->sku }}</div>
                        </td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td>{{ $product->brand?->name ?? '—' }}</td>
                        <td>
                            @if($product->has_price)
                                @if($product->has_sale)
                                    <del class="text-muted" style="font-size:.8rem;">{{ money((float) $product->regular_price) }}</del>
                                    <div style="color:var(--a-btn);font-weight:700;">{{ money($product->display_price) }}</div>
                                @else
                                    {{ money($product->display_price) }}
                                @endif
                            @else
                                <span class="contact-price">{{ $product->price_display_text }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $product->stock === 0 ? 'badge-danger' : ($product->stock <= 5 ? 'badge-warning' : 'badge-success') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;flex-direction:column;gap:.25rem;">
                                <form action="{{ route('admin.products.toggle-active', $product) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="badge {{ $product->is_active ? 'badge-success' : 'badge-muted' }}" style="border:none;cursor:pointer;">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="badge {{ $product->is_featured ? 'badge-orange' : 'badge-muted' }}" style="border:none;cursor:pointer;">
                                        {{ $product->is_featured ? '★ Featured' : 'Not featured' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn btn-sm btn-outline">View</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" data-confirm="Delete this product permanently?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:2.4rem;color:var(--a-muted);">
                            No products found. <a href="{{ route('admin.products.create') }}">Add your first product</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $products->links() }}</div>
    </div>
@endsection