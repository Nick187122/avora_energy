@extends('layouts.admin')

@section('title', 'Value Propositions')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Value Propositions</h2>
            <p>The icon strip on the homepage: free delivery, safe payment etc.</p>
        </div>
        <a href="{{ route('admin.value-propositions.create') }}" class="btn btn-primary">＋ Add Item</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>
                            @if($item->icon)
                                <img src="{{ asset($item->icon) }}" style="width:46px;height:46px;object-fit:contain;" alt="">
                            @else
                                <div style="width:46px;height:46px;border-radius:12px;background:var(--a-blue-soft, #e8f0fb);display:flex;align-items:center;justify-content:center;">★</div>
                            @endif
                        </td>
                        <td><strong>{{ $item->title }}</strong></td>
                        <td class="text-muted">{{ $item->subtitle }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td><span class="badge {{ $item->is_active ? 'badge-success' : 'badge-muted' }}">{{ $item->is_active ? 'Active' : 'Hidden' }}</span></td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('admin.value-propositions.edit', $item) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form action="{{ route('admin.value-propositions.destroy', $item) }}" method="POST" data-confirm="Delete this item?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--a-muted);">No value propositions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection