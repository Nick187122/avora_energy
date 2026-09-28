@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Contact Messages</h2>
            <p>Messages submitted via the contact form. Unread first by default.</p>
        </div>
        <a href="{{ route('admin.messages.index', ['all' => 1]) }}" class="btn btn-outline">Show All Messages</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Received</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td>
                            <strong>{{ $message->name }}</strong>
                            <div class="text-muted" style="font-size:.78rem;">{{ $message->email }}</div>
                            @if($message->phone)
                                <div class="text-muted" style="font-size:.78rem;">{{ $message->phone }}</div>
                            @endif
                        </td>
                        <td>{{ $message->subject ?? '—' }}</td>
                        <td class="text-muted" style="max-width:320px;">{{ \Illuminate\Support\Str::limit($message->message, 70) }}</td>
                        <td>{{ $message->created_at->format('M d, H:i') }}</td>
                        <td>
                            @if($message->is_read)
                                <span class="badge badge-muted">Read</span>
                            @else
                                <span class="badge badge-orange">New</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-outline">Read</a>
                                <form action="{{ route('admin.messages.unread', $message) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline">Mark unread</button>
                                </form>
                                <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="Delete this message?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--a-muted);">No messages found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $messages->links() }}</div>
    </div>
@endsection