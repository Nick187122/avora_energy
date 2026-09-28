@extends('layouts.admin')

@section('title', 'Partners')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Partners</h2>
            <p>Brand logos displayed in the homepage "Our Partners" gallery.</p>
        </div>
        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">＋ Add Partner</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Website</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partners as $partner)
                    <tr>
                        <td>
                            @if($partner->logo)
                                <img src="{{ asset($partner->logo) }}" class="thumb" style="object-fit:contain;background:var(--a-light);" alt="">
                            @else
                                <div class="thumb" style="background:var(--a-light);"></div>
                            @endif
                        </td>
                        <td><strong>{{ $partner->name }}</strong></td>
                        <td class="text-muted">{{ $partner->website_url }}</td>
                        <td>{{ $partner->sort_order }}</td>
                        <td><span class="badge {{ $partner->is_active ? 'badge-success' : 'badge-muted' }}">{{ $partner->is_active ? 'Active' : 'Hidden' }}</span></td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" data-confirm="Remove this partner?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--a-muted);">No partners yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $partners->links() }}</div>
    </div>
@endsection