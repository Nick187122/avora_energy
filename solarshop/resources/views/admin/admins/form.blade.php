@extends('layouts.admin')

@section('title', 'Admin User')

@section('content')
    <div class="admin-head">
        <div>
            <h2>{{ isset($admin) ? 'Edit '.$admin->name : 'Add Admin User' }}</h2>
        </div>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline">← Back</a>
    </div>

    <form action="{{ isset($admin) ? route('admin.admins.update', $admin) : route('admin.admins.store') }}" method="POST" class="form-card">
        @csrf
        @isset($admin) @method('PUT') @endisset

        <div class="form-grid">
            <div class="form-group full">
                <label for="name">Full Name <span class="req">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $admin->name ?? '') }}" required>
            </div>
            <div class="form-group full">
                <label for="email">Email <span class="req">*</span></label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $admin->email ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Password @isset($admin)<span class="hint">(leave blank to keep current)</span>@endisset<span class="req">@empty($admin) *@endempty</span></label>
                <input type="password" name="password" id="password" class="form-control" minlength="8" @empty($admin) required @endempty>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" minlength="8" @empty($admin) required @endempty>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="admin" @selected(old('role', $admin->role ?? '') === 'admin')>Admin (full access)</option>
                    <option value="manager" @selected(old('role', $admin->role ?? '') === 'manager')>Manager</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <label style="display:flex;align-items:center;gap:.4rem;font-weight:500;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $admin->is_active ?? true))> Active
                </label>
                @isset($admin)
                    @if($admin->id === Auth::guard('admin')->id())
                        <span class="hint">You cannot deactivate your own account.</span>
                    @endif
                @endisset
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save Admin User</button>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline">Cancel</a>
    </form>
@endsection