<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = order::with('customer', 'orderDetails.product')->get();

        return view('pages.orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = customer::all();

        return view('pages.orders.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        $validated['status'] = 'pending';
        $validated['total'] = 0;

        order::create($validated);

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show(order $order)
    {
        $order->load('customer', 'orderDetails.product');
        $customers = customer::all();

        return view('pages.orders.show', compact('order', 'customers'));
    }

    public function edit(order $order)
    {
        $order->load('customer', 'orderDetails.product');
        $customers = customer::all();

        return view('pages.orders.edit', compact('order', 'customers'));
    }

    public function update(Request $request, order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'status' => 'nullable|in:pending,in_production,quality_check,completed,shipped',
        ]);

        if ($validated['status'] ?? null) {
            $this->handleStockOnStatusChange($order, $validated['status']);
        }

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function updateStatus(Request $request, order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,in_production,quality_check,completed,shipped',
        ]);

        $this->handleStockOnStatusChange($order, $request->status);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    private function handleStockOnStatusChange(order $order, string $newStatus): void
    {
        DB::transaction(function () use ($order, $newStatus) {
            if ($newStatus === 'shipped') {
                $order->deductStockForShipping();
            } elseif ($order->getOriginal('status') === 'shipped') {
                $order->restoreStockIfNeeded();
            }
        });
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $orders = order::where('id', $query)
            ->orWhereHas('customer', fn ($q) => $q->where('name', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%"))
            ->with('customer', 'orderDetails.product')
            ->get();

        return view('pages.orders.index', compact('orders'));
    }
}
