<?php

namespace App\Services;

use App\Models\product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'cart';

    public function add(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        $product = product::findOrFail($productId);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
            ];
        }

        Session::put($this->sessionKey, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        Session::put($this->sessionKey, $cart);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, $quantity);
        }

        Session::put($this->sessionKey, $cart);
    }

    public function getItems(): Collection
    {
        return collect($this->getCart());
    }

    public function getTotal(): float
    {
        return collect($this->getCart())->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    public function count(): int
    {
        return collect($this->getCart())->sum('quantity');
    }

    protected function getCart(): array
    {
        return Session::get($this->sessionKey, []);
    }
}
