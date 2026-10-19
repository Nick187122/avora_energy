<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::active()->with('category', 'brand');

        $term = trim((string) $request->get('q'));

        if ($term !== '') {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$term}%"))
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$term}%"))
                    ->orWhereJsonContains('specifications', $term);
            });
        }

        $products = $query->orderBy('views', 'desc')->paginate(12)->withQueryString();

        return view('search.index', compact('products', 'term'));
    }
}
