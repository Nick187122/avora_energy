@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Categories</h2>
            <p>Organize products into categories and subcategories.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">＋ Add Category</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Parent</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" class="thumb" alt="">
                            @else
                                <div class="thumb" style="background:var(--a-light);"></div>
                            @endif
                        </td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td class="text-muted">{{ $category->slug }}</td>
                        <td>{{ $category->parent?->name ?? '—' }}</td>
                        <td><span class="badge badge-info">{{ $category->products_count }}</span></td>
                        <td><span class="badge {{ $category->is_active ? 'badge-success' : 'badge-muted' }}">{{ $category->is_active ? 'Active' : 'Hidden' }}</span></td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('category.show', $category->slug) }}" target="_blank" class="btn btn-sm btn-outline">View</a>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" data-confirm="Delete this category?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--a-muted);">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $categories->links() }}</div>
    </div>
@endsection