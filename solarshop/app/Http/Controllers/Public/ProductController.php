<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::active()
            ->with('category', 'brand')
            ->where('slug', $slug)
            ->firstOrFail();

        $product->increment('views');

        $related = Product::active()->with('category', 'brand')
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->inStock()
            ->orderBy('views', 'desc')
            ->take(4)
            ->get();

        if ($related->count() === 0) {
            $related = Product::active()->with('category', 'brand')->where('id', '!=', $product->id)->inStock()->orderBy('views', 'desc')->take(4)->get();
        }

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        return view('shop.product', compact('product', 'related', 'categories', 'brands'));
    }
}
