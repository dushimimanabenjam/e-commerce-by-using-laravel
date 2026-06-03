<?php

use App\Models\category;
use App\Models\product;
use App\Services\CartService;

beforeEach(function () {
    $this->cart = app(CartService::class);
    $this->cart->clear();

    $this->category = category::factory()->create();

    $this->product = product::factory()->create([
        'name' => 'Test Product',
        'price' => 29.99,
        'stock' => 10,
        'category_id' => $this->category->id,
    ]);
});

afterEach(function () {
    $this->cart->clear();
});

it('can add items to cart', function () {
    $this->cart->add($this->product->id, 2);

    expect($this->cart->count())->toBe(2)
        ->and($this->cart->getItems())->toHaveCount(1);
});

it('can increment quantity when adding same product', function () {
    $this->cart->add($this->product->id, 2);
    $this->cart->add($this->product->id, 3);

    expect($this->cart->count())->toBe(5)
        ->and($this->cart->getItems())->toHaveCount(1);
});

it('can remove items from cart', function () {
    $this->cart->add($this->product->id, 1);
    $this->cart->remove($this->product->id);

    expect($this->cart->count())->toBe(0)
        ->and($this->cart->getItems())->toBeEmpty();
});

it('can update item quantity', function () {
    $this->cart->add($this->product->id, 1);
    $this->cart->updateQuantity($this->product->id, 5);

    $item = $this->cart->getItems()->first();
    expect($item['quantity'])->toBe(5);
});

it('prevents quantity below 1', function () {
    $this->cart->add($this->product->id, 1);
    $this->cart->updateQuantity($this->product->id, 0);

    $item = $this->cart->getItems()->first();
    expect($item['quantity'])->toBe(1);
});

it('calculates total correctly', function () {
    $this->cart->add($this->product->id, 2);
    $this->cart->add($this->product->id, 1);

    expect($this->cart->getTotal())->toBe(29.99 * 3);
});

it('can clear the cart', function () {
    $this->cart->add($this->product->id, 1);
    $this->cart->clear();

    expect($this->cart->count())->toBe(0)
        ->and($this->cart->getItems())->toBeEmpty();
});

it('contains correct item structure', function () {
    $this->cart->add($this->product->id, 3);

    $item = $this->cart->getItems()->first();

    expect($item)->toHaveKeys(['product_id', 'name', 'price', 'quantity', 'image'])
        ->and($item['product_id'])->toBe($this->product->id)
        ->and($item['name'])->toBe('Test Product')
        ->and($item['price'])->toBe(29.99)
        ->and($item['quantity'])->toBe(3);
});

it('returns zero count for empty cart', function () {
    expect($this->cart->count())->toBe(0)
        ->and($this->cart->getTotal())->toBe(0.0);
});
