@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Pages</h2>
            <p>Static content pages (About, Services, Policies...).</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">＋ Add Page</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td><strong>{{ $page->title }}</strong></td>
                        <td class="text-muted">/{{ $page->slug }}</td>
                        <td><span class="badge {{ $page->is_active ? 'badge-success' : 'badge-muted' }}">{{ $page->is_active ? 'Live' : 'Draft' }}</span></td>
                        <td>{{ $page->updated_at->format('M d, Y') }}</td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-sm btn-outline">View</a>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" data-confirm="Delete this page?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--a-muted);">No pages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $pages->links() }}</div>
    </div>
@endsection