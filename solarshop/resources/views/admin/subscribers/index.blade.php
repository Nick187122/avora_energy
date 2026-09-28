@extends('layouts.admin')

@section('title', 'Subscribers')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Newsletter Subscribers</h2>
            <p>{{ $subscribers->total() }} subscriber(s)</p>
        </div>
        <div style="display:flex;gap:.5rem;">
            <a href="{{ route('admin.subscribers.export') }}" class="btn btn-primary">Export CSV</a>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Subscribed On</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $subscriber)
                    <tr>
                        <td><strong>{{ $subscriber->email }}</strong></td>
                        <td><span class="badge {{ $subscriber->is_active ? 'badge-success' : 'badge-muted' }}">{{ $subscriber->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>{{ $subscriber->created_at->format('M d, Y') }}</td>
                        <td class="text-right">
                            <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" data-confirm="Remove this subscriber?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--a-muted);">No subscribers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $subscribers->links() }}</div>
    </div>
@endsection