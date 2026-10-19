<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View
    {
        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();

        return view('shop.cart', compact('items', 'subtotal'));
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $added = $this->cart->add(
            (int) $request->get('product_id'),
            (int) ($request->get('quantity') ?? 1)
        );

        if (! $added) {
            return back()->with('error', 'This product is not currently available.');
        }

        Session::flash('cart_added', $request->get('product_id'));

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $this->cart->update((int) $request->get('product_id'), (int) $request->get('quantity'));

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => ['required', 'exists:products,id']]);
        $this->cart->remove((int) $request->get('product_id'));

        return back()->with('success', 'Product removed from cart.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
}
