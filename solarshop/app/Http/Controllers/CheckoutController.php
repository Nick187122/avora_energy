<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty. Add products before checkout.');
        }

        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();

        return view('shop.checkout', compact('items', 'subtotal'));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:mpesa,bank_transfer,card,cod'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();

        $order = DB::transaction(function () use ($data, $items, $subtotal) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $data['customer_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'] ?? null,
                'payment_method' => $data['payment_method'],
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $product = $item['product'];
                $price = $product->display_price ?? 0;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['line_total'],
                    'image' => $product->image,
                ]);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.success', $order->order_number)
            ->with('success', 'Your order has been placed successfully!');
    }

    public function success(string $orderNumber): View
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        return view('shop.order-success', compact('order'));
    }
}
