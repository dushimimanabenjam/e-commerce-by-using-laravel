<?php

use App\Models\category;
use App\Models\customer;
use App\Models\order;
use App\Models\order_detail;
use App\Models\product;
use App\Models\User;
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

    $this->user = User::factory()->create();
});

afterEach(function () {
    $this->cart->clear();
});

it('shows checkout page with cart items', function () {
    $this->cart->add($this->product->id, 2);

    $response = $this->actingAs($this->user)->get(route('checkout.index'));

    $response->assertSuccessful();
    $response->assertSee('Test Product');
    $response->assertSee('Checkout');
});

it('redirects from checkout when cart is empty', function () {
    $response = $this->actingAs($this->user)->get(route('checkout.index'));

    $response->assertRedirect(route('cart.index'));
});

it('can place an order with existing customer', function () {
    $customer = customer::factory()->create();

    $this->cart->add($this->product->id, 3);

    $response = $this->actingAs($this->user)->post(route('checkout.store'), [
        'customer_id' => $customer->id,
    ]);

    $order = order::first();

    $response->assertRedirect(route('orders.show', $order));
    expect(order::count())->toBe(1)
        ->and(order_detail::count())->toBe(1)
        ->and($this->product->fresh()->stock)->toBe(7)
        ->and($this->cart->count())->toBe(0);
});

it('can place an order with new customer', function () {
    $this->cart->add($this->product->id, 1);

    $response = $this->actingAs($this->user)->post(route('checkout.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $order = order::first();

    $response->assertRedirect(route('orders.show', $order));
    expect(customer::where('email', 'jane@example.com')->exists())->toBeTrue()
        ->and($this->product->fresh()->stock)->toBe(9);
});

it('fails with insufficient stock', function () {
    $this->product->update(['stock' => 1]);
    $customer = customer::factory()->create();

    $this->cart->add($this->product->id, 5);

    $response = $this->actingAs($this->user)->post(route('checkout.store'), [
        'customer_id' => $customer->id,
    ]);

    $response->assertSessionHasErrors('stock');
    expect(order::count())->toBe(0)
        ->and($this->product->fresh()->stock)->toBe(1);
});

it('redirects to cart when placing order with empty cart', function () {
    $response = $this->actingAs($this->user)->post(route('checkout.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $response->assertRedirect(route('cart.index'));
});

it('creates order with correct total', function () {
    $this->cart->add($this->product->id, 4);
    $customer = customer::factory()->create();

    $this->actingAs($this->user)->post(route('checkout.store'), [
        'customer_id' => $customer->id,
    ]);

    $order = order::first();
    expect((float) $order->total)->toBe(29.99 * 4);
});

it('creates order details with correct price', function () {
    $this->cart->add($this->product->id, 2);
    $customer = customer::factory()->create();

    $this->actingAs($this->user)->post(route('checkout.store'), [
        'customer_id' => $customer->id,
    ]);

    $detail = order_detail::first();
    expect((float) $detail->price)->toBe(29.99)
        ->and($detail->quantity)->toBe(2);
});
