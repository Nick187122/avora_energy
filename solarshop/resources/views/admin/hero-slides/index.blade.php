@extends('layouts.admin')

@section('title', 'Hero Slides')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Hero Slides</h2>
            <p>Slides shown on the homepage banner. Order controls display order.</p>
        </div>
        <a href="{{ route('admin.hero-slides.create') }}" class="btn btn-primary">＋ Add Slide</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Button</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($slides as $slide)
                    <tr>
                        <td><img src="{{ asset($slide->image) }}" class="thumb" alt="" style="object-fit:cover;"></td>
                        <td><strong>{{ $slide->title }}</strong></td>
                        <td class="text-muted">{{ $slide->subtitle }}</td>
                        <td>{{ $slide->button_text ? $slide->button_text . ' →' : '—' }}</td>
                        <td>{{ $slide->sort_order }}</td>
                        <td>
                            <form action="{{ route('admin.hero-slides.toggle', $slide) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="badge {{ $slide->is_active ? 'badge-success' : 'badge-muted' }}" style="border:none;cursor:pointer;">
                                    {{ $slide->is_active ? 'Live' : 'Hidden' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" data-confirm="Delete this slide?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--a-muted);">No slides yet. Add one to show content on the homepage.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection