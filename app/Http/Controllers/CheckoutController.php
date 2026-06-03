<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\order;
use App\Models\order_detail;
use App\Models\product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(CartService $cart)
    {
        $items = $cart->getItems();
        $total = $cart->getTotal();
        $customers = customer::all();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return view('pages.checkout.index', compact('items', 'total', 'customers'));
    }

    public function store(Request $request, CartService $cart)
    {
        $items = $cart->getItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'name' => 'required_without:customer_id|string|max:255',
            'email' => 'required_without:customer_id|email|max:255',
        ]);

        DB::beginTransaction();

        try {
            if (! empty($validated['customer_id'])) {
                $customer = customer::findOrFail($validated['customer_id']);
            } else {
                $customer = customer::firstOrCreate(
                    ['email' => $validated['email']],
                    ['name' => $validated['name']],
                );
            }

            $total = $items->sum(fn ($item) => $item['price'] * $item['quantity']);

            foreach ($items as $item) {
                $product = product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();

                    return back()->withErrors([
                        'stock' => "Insufficient stock for {$product->name}. Available: {$product->stock}, requested: {$item['quantity']}.",
                    ]);
                }
            }

            $order = order::create([
                'customer_id' => $customer->id,
                'status' => 'pending',
                'total' => $total,
            ]);

            foreach ($items as $item) {
                order_detail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'status' => 'pending',
                    'stock_deducted' => true,
                ]);

                product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }

            DB::commit();

            $cart->clear();

            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to place order: '.$e->getMessage()]);
        }
    }
}
