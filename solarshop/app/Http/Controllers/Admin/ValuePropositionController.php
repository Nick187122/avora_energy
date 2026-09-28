<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ValueProposition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ValuePropositionController extends Controller
{
    use UploadsFiles;

    public function index(): View
    {
        $items = ValueProposition::orderBy('sort_order')->get();

        return view('admin.value-propositions.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.value-propositions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg,gif', 'max:1024'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('icon')) {
            $data['icon'] = $this->storeUpload($request->file('icon'), 'icons');
        }

        $data['is_active'] = $request->boolean('is_active');
        ValueProposition::create($data);

        return redirect()->route('admin.value-propositions.index')->with('success', 'Value proposition created successfully.');
    }

    public function edit(ValueProposition $valueProposition): View
    {
        return view('admin.value-propositions.edit', compact('valueProposition'));
    }

    public function update(Request $request, ValueProposition $valueProposition): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg,gif', 'max:1024'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('icon')) {
            $data['icon'] = $this->storeUpload($request->file('icon'), 'icons');
        }

        $data['is_active'] = $request->boolean('is_active');
        $valueProposition->update($data);

        return redirect()->route('admin.value-propositions.index')->with('success', 'Value proposition updated successfully.');
    }

    public function destroy(ValueProposition $valueProposition): RedirectResponse
    {
        $valueProposition->delete();

        return redirect()->route('admin.value-propositions.index')->with('success', 'Value proposition deleted successfully.');
    }
}