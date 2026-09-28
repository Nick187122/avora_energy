<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::active()->with('category', 'brand');

        $category = null;
        $currentBrand = null;
        $categoryTree = [];

        $catSlug = $request->filled('category') ? $request->get('category') : $request->route('category');
        if ($catSlug) {
            $category = Category::with('children')->where('slug', $catSlug)->first();
            if ($category) {
                $query->where(function ($q) use ($category) {
                    $q->where('category_id', $category->id)
                        ->orWhereIn('category_id', $category->children()->pluck('id'));
                });
            }
        }

        $brandSlug = $request->filled('brand') ? $request->get('brand') : $request->route('brand');
        if ($brandSlug && !$category) {
            $currentBrand = Brand::where('slug', $brandSlug)->first();
            if ($currentBrand) {
                $query->where('brand_id', $currentBrand->id);
            }
        }

        if ($request->boolean('in_stock_only')) {
            $query->inStock();
        }

        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '>=', $request->get('min_price'))
                    ->orWhere(function ($q) use ($request) {
                        $q->whereNull('sale_price')->where('regular_price', '>=', $request->get('min_price'));
                    });
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '<=', $request->get('max_price'))
                    ->orWhere(function ($q) use ($request) {
                        $q->whereNull('sale_price')->where('regular_price', '<=', $request->get('max_price'));
                    });
            });
        }

        if ($request->filled('availability') && $request->get('availability') === 'in_stock') {
            $query->inStock();
        }

        if ($request->filled('sale')) {
            $query->onSale();
        }

        if ($request->filled('featured')) {
            $query->featured();
        }

        $sort = $request->get('sort', 'latest');
        $query = match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, regular_price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, regular_price) DESC'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'popularity' => $query->orderBy('views', 'desc'),
            default => $query->orderByRaw('is_featured DESC, created_at DESC, id DESC'),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
            ->orderBy('name')
            ->get();

        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        $categoryTree = $categories;

        $chipCategories = collect();
        if ($category) {
            $chipCategories = $category->children->count()
                ? $category->children
                : optional($category->parent)->children ?? collect();
        }

        return view('shop.index', compact(
            'products', 'categories', 'brands', 'category', 'currentBrand', 'categoryTree', 'chipCategories'
        ));
    }
}