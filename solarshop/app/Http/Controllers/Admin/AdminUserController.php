<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $admins = Admin::orderBy('name')->paginate(20);

        return view('admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        return view('admin.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,manager'],
        ]);

        $data['password'] = Hash::make($request->get('password'));
        $data['is_active'] = $request->boolean('is_active');

        Admin::create($data);

        return redirect()->route('admin.admins.index')->with('success', 'Admin user created successfully.');
    }

    public function edit(Admin $admin): View
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,manager'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->get('password'));
        } else {
            unset($data['password']);
        }

        if ($admin->id === Auth::guard('admin')->id()) {
            $data['is_active'] = true;
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'Admin user updated successfully.');
    }

    public function destroy(Admin $admin): RedirectResponse
    {
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if (Admin::count() <= 1) {
            return back()->with('error', 'At least one admin account must remain.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin user deleted successfully.');
    }
}