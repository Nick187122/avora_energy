<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    private const KEY = 'cart';

    protected function cart(): array
    {
        return session()->get(self::KEY, []);
    }

    public function items(): array
    {
        $cart = $this->cart();
        $items = [];

        foreach ($cart as $productId => $qty) {
            $product = Product::active()->find($productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'line_total' => $product->display_price * $qty,
                ];
            }
        }

        return $items;
    }

    public function add(int $productId, int $qty = 1, int $max = 10): bool
    {
        $product = Product::active()->inStock()->find($productId);
        if (! $product) {
            return false;
        }

        $cart = $this->cart();
        $newQty = min(($cart[$productId] ?? 0) + max(1, $qty), $max);
        $cart[$productId] = max(1, min($newQty, $product->stock > 0 ? $product->stock : $max));
        session([self::KEY => $cart]);

        return true;
    }

    public function update(int $productId, int $qty): void
    {
        $cart = $this->cart();
        $product = Product::find($productId);

        if ($qty <= 0 || ! $product) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = min($qty, $product->stock > 0 ? $product->stock : 100);
        }

        session([self::KEY => $cart]);
    }

    public function remove(int $productId): void
    {
        $cart = $this->cart();
        unset($cart[$productId]);
        session([self::KEY => $cart]);
    }

    public function count(): int
    {
        return array_sum($this->cart());
    }

    public function subtotal(): float
    {
        return array_sum(array_column($this->items(), 'line_total'));
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }
}