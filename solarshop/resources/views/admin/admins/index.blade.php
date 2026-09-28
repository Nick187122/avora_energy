@extends('layouts.admin')

@section('title', 'Admin Users')

@section('content')
    <div class="admin-head">
        <div>
            <h2>Admin Users</h2>
            <p>Accounts that can access the admin panel.</p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">＋ Add Admin</a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td><strong>{{ $admin->name }}</strong> @if($admin->id === Auth::guard('admin')->id())<span class="badge badge-info">You</span>@endif</td>
                        <td>{{ $admin->email }}</td>
                        <td><span class="badge {{ $admin->role === 'admin' ? 'badge-info' : 'badge-muted' }}">{{ ucfirst($admin->role) }}</span></td>
                        <td><span class="badge {{ $admin->is_active ? 'badge-success' : 'badge-muted' }}">{{ $admin->is_active ? 'Active' : 'Disabled' }}</span></td>
                        <td>{{ $admin->created_at->format('M d, Y') }}</td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" data-confirm="Delete this admin user?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--a-muted);">No admin users.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $admins->links() }}</div>
    </div>
@endsection