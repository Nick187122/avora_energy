<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    use UploadsFiles;

    public function index(Request $request): View
    {
        $query = Product::with('category', 'brand');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->get('brand_id'));
        }

        if ($request->get('status') === 'active') {
            $query->active();
        } elseif ($request->get('status') === 'inactive') {
            $query->where('is_active', false);
        } elseif ($request->get('status') === 'out_of_stock') {
            $query->where('stock', 0);
        } elseif ($request->get('status') === 'sale') {
            $query->onSale();
        }

        $products = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $request->validate(['image' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048']);
            $data['image'] = $this->storeUpload($request->file('image'), 'products');
        }

        $gallery = [];
        if ($request->hasFile('gallery')) {
            $request->validate([
                'gallery.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            ]);
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $this->storeUpload($file, 'products');
            }
        }
        $data['gallery'] = $gallery;

        if ($request->filled('spec_keys')) {
            $data['specifications'] = $this->buildSpecifications($request);
        }

        $product = Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$product->name}\" created successfully.");
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product->id);

        if ($request->hasFile('image')) {
            $request->validate(['image' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048']);
            $data['image'] = $this->storeUpload($request->file('image'), 'products');
        }

        $gallery = $product->gallery ?? [];
        if ($request->hasFile('gallery')) {
            $request->validate([
                'gallery.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            ]);
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $this->storeUpload($file, 'products');
            }
        }

        $data['gallery'] = $gallery;

        if ($request->boolean('remove_gallery_images')) {
            $data['gallery'] = [];
        }

        if ($request->filled('spec_keys')) {
            $data['specifications'] = $this->buildSpecifications($request);
        } elseif ($request->has('clear_specs')) {
            $data['specifications'] = null;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$product->name}\" updated successfully.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$name}\" deleted successfully.");
    }

    public function toggleActive(Product $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', 'Product status updated.');
    }

    public function toggleFeatured(Product $product): RedirectResponse
    {
        $product->update(['is_featured' => ! $product->is_featured]);

        return back()->with('success', 'Featured status updated.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($ignoreId)],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($ignoreId)],
            'category_id' => ['nullable', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:regular_price'],
            'stock' => ['required', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug((string) $request->get('name'));
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        if (isset($data['description'])) {
            $data['description'] = clean_html($data['description']);
        }

        return $data;
    }

    protected function buildSpecifications(Request $request): array
    {
        $keys = $request->input('spec_keys', []);
        $values = $request->input('spec_values', []);
        $specs = [];

        foreach ($keys as $i => $key) {
            $key = trim((string) $key);
            $value = trim((string) ($values[$i] ?? ''));
            if ($key !== '' && $value !== '') {
                $specs[$key] = $value;
            }
        }

        return $specs;
    }
}
