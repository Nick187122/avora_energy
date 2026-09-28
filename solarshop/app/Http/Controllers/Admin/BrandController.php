<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BrandController extends Controller
{
    use UploadsFiles;

    public function index(): View
    {
        $brands = Brand::withCount('products')->orderBy('name')->paginate(20);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('brands', 'slug')],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($request->get('name'));
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:jpeg,png,webp,svg,gif|max:1024']);
            $data['logo'] = $this->storeUpload($request->file('logo'), 'brands');
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($brand->id)],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($request->get('name'));
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:jpeg,png,webp,svg,gif|max:1024']);
            $data['logo'] = $this->storeUpload($request->file('logo'), 'brands');
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Cannot delete a brand that has products. Remove products first.');
        }

        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }
}