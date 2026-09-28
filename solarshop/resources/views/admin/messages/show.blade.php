@extends('layouts.admin')

@section('title', 'Message')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Message from {{ $message->name }}</h2>
            <p>{{ $message->created_at->format('F j, Y h:i A') }}</p>
        </div>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline">← Inbox</a>
    </div>

    <div class="grid-2">
        <div class="panel">
            <p style="margin-bottom:.8rem;"><strong>Subject:</strong> {{ $message->subject ?? 'No subject' }}</p>
            <div style="background:var(--a-light);border-radius:10px;padding:1.2rem;min-height:120px;white-space:pre-wrap;">{{ $message->message }}</div>
        </div>
        <div>
            <div class="panel">
                <h3>Contact Info</h3>
                <p><strong>Name:</strong> {{ $message->name }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
                @if($message->phone)
                    <p><strong>Phone:</strong> <a href="tel:{{ $message->phone }}">{{ $message->phone }}</a></p>
                @endif
                <div style="display:flex;flex-direction:column;gap:.55rem;margin-top:1rem;">
                    <a href="mailto:{{ $message->email }}" class="btn btn-primary">Reply via Email</a>
                    @if($message->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank" rel="noopener" class="btn btn-outline">WhatsApp Reply</a>
                    @endif
                    <form action="{{ route('admin.messages.unread', $message) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline">Mark as unread</button>
                    </form>
                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="Delete this message?">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection