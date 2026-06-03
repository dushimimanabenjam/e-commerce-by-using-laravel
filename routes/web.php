<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ProductController;
use App\Models\customer;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// All routes require authentication
Route::middleware(['auth'])->group(function () {
    Route::view('/', 'welcome')->name('home');

    Route::middleware(['verified'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    });

    require __DIR__.'/settings.php';

    // product browsing
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // cart routes
    Route::get('/cart', function (CartService $cart) {
        return view('pages.cart.index', [
            'items' => $cart->getItems(),
            'total' => $cart->getTotal(),
        ]);
    })->name('cart.index');

    Route::post('/cart/add/{product}', function (int $product, Request $request, CartService $cart) {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart->add($product, $request->integer('quantity'));

        return redirect()->back()->with('success', 'Product added to cart.');
    })->name('cart.add');

    Route::patch('/cart/{product}', function (int $product, Request $request, CartService $cart) {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart->updateQuantity($product, $request->integer('quantity'));

        return redirect()->route('cart.index');
    })->name('cart.update');

    Route::delete('/cart/{product}', function (int $product, CartService $cart) {
        $cart->remove($product);

        return redirect()->route('cart.index');
    })->name('cart.remove');

    // checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // my orders
    Route::get('/my-orders', function () {
        $customer = customer::where('email', auth()->user()->email)->first();
        $orders = $customer ? $customer->orders()->with('orderDetails.product')->latest()->get() : collect();

        return view('pages.orders.my-orders', compact('orders'));
    })->name('orders.my');

    // order show (for customers to see their order after checkout)
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // category browsing
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    // admin panel
    Route::prefix('admin')->middleware(['verified', 'admin'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/search', [AdminController::class, 'search'])->name('admin.search');

        // product management (create/update/delete only — browsing is public)
        Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::post('/products/{product}/restock', [ProductController::class, 'restock'])->name('products.restock');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // customer routes (admin only — customers cannot see other customers)
        Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        // category management (create/update/delete only — browsing is auth)
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // order routes (created via checkout — admin manages only)
        Route::get('/orders/search', [OrderController::class, 'search'])->name('orders.search');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        // order-detail routes (created via checkout — admin manages only)
        Route::get('/order-details/search', [OrderDetailController::class, 'search'])->name('order-details.search');
        Route::get('/order-details', [OrderDetailController::class, 'index'])->name('order-details.index');
        Route::get('/order-details/{orderDetail}', [OrderDetailController::class, 'show'])->name('order-details.show');
        Route::get('/order-details/{orderDetail}/edit', [OrderDetailController::class, 'edit'])->name('order-details.edit');
        Route::put('/order-details/{orderDetail}', [OrderDetailController::class, 'update'])->name('order-details.update');
        Route::patch('/order-details/{orderDetail}/status', [OrderDetailController::class, 'updateStatus'])->name('order-details.update-status');
        Route::delete('/order-details/{orderDetail}', [OrderDetailController::class, 'destroy'])->name('order-details.destroy');
    });
});
