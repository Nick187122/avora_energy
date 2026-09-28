<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerController extends Controller
{
    use UploadsFiles;

    public function index(): View
    {
        $partners = Partner::orderBy('sort_order')->orderBy('name')->paginate(30);

        return view('admin.partners.index', compact('partners'));
    }

    public function create(): View
    {
        return view('admin.partners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg,gif', 'max:1024'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->storeUpload($request->file('logo'), 'partners');
        }

        $data['is_active'] = $request->boolean('is_active');
        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner added successfully.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg,gif', 'max:1024'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->storeUpload($request->file('logo'), 'partners');
        }

        $data['is_active'] = $request->boolean('is_active');
        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner removed successfully.');
    }
}