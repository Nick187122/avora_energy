<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ValueProposition;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $slides = HeroSlide::where('is_active', true)->orderBy('sort_order')->get();
        $valueProps = ValueProposition::where('is_active', true)->orderBy('sort_order')->get();

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $topDeals = Product::active()->with('category', 'brand')
            ->onSale()
            ->inStock()
            ->orderByRaw('(1 - sale_price / regular_price) desc')
            ->take(8)
            ->get();

        $bestsellers = Product::active()->with('category', 'brand')
            ->orderByRaw('is_featured DESC, views DESC, id DESC')
            ->take(8)
            ->get();

        $featured = Product::featured()->active()->inStock()->with('category', 'brand')->orderByRaw('views DESC, id DESC')->take(8)->get();

        $inverters = Product::active()->with('category', 'brand')
            ->whereHas('category', fn ($q) => $q->where('slug', 'like', '%inverter%'))
            ->inStock()
            ->orderByRaw('views DESC, id DESC')
            ->take(8)
            ->get();

        $portablePower = Product::active()->with('category', 'brand')
            ->onSale()
            ->inStock()
            ->orderByRaw('views DESC, id DESC')
            ->take(8)
            ->get();

        $recommended = Product::active()->with('category', 'brand')->inStock()->orderByRaw('views DESC, id DESC')->take(8)->get();

        $partners = Partner::where('is_active', true)->orderBy('sort_order')->get();

        return view('home', compact(
            'slides', 'valueProps', 'categories', 'topDeals', 'bestsellers',
            'featured', 'inverters', 'portablePower', 'recommended', 'partners'
        ));
    }
}
