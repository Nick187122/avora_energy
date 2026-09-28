@extends('layouts.admin')

@section('title', 'Brands')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Brands</h2>
            <p>Manufacturers &amp; brands featured in your catalog.</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">＋ Add Brand</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset($brand->logo) }}" class="thumb" alt="" style="object-fit:contain;background:var(--a-light);">
                            @else
                                <div class="thumb" style="background:var(--a-light);"></div>
                            @endif
                        </td>
                        <td><strong>{{ $brand->name }}</strong></td>
                        <td class="text-muted">{{ $brand->slug }}</td>
                        <td><span class="badge badge-info">{{ $brand->products_count }}</span></td>
                        <td><span class="badge {{ $brand->is_active ? 'badge-success' : 'badge-muted' }}">{{ $brand->is_active ? 'Active' : 'Hidden' }}</span></td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('brand.show', $brand->slug) }}" target="_blank" class="btn btn-sm btn-outline">View</a>
                                <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" data-confirm="Delete this brand?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--a-muted);">No brands yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $brands->links() }}</div>
    </div>
@endsection