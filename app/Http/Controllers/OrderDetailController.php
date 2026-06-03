<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\order_detail;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDetailController extends Controller
{
    public function index()
    {
        $orderDetails = order_detail::with(['order.customer', 'product'])->get();

        return view('pages.order-details.index', compact('orderDetails'));
    }

    public function create()
    {
        $orders = order::with('customer')->get();
        $products = product::all();

        return view('pages.order-details.create', compact('orders', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $validated['status'] = 'pending';
        $validated['price'] = product::findOrFail($validated['product_id'])->price;

        order_detail::create($validated);

        return redirect()->route('order-details.index')->with('success', 'Order detail created successfully.');
    }

    public function show(order_detail $orderDetail)
    {
        $orderDetail->load('order.customer', 'product');

        return view('pages.order-details.show', compact('orderDetail'));
    }

    public function edit(order_detail $orderDetail)
    {
        $orders = order::with('customer')->get();
        $products = product::all();

        return view('pages.order-details.edit', compact('orderDetail', 'orders', 'products'));
    }

    public function update(Request $request, order_detail $orderDetail)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'nullable|in:pending,in_production,quality_check,completed',
        ]);

        if (($validated['status'] ?? null) === 'completed') {
            $this->deductStock($orderDetail);
        } elseif ($orderDetail->getOriginal('status') === 'completed') {
            $this->restoreStock($orderDetail);
        }

        $orderDetail->update($validated);

        return redirect()->route('order-details.index')->with('success', 'Order detail updated successfully.');
    }

    public function destroy(order_detail $orderDetail)
    {
        $orderDetail->delete();

        return redirect()->route('order-details.index')->with('success', 'Order detail deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $orderDetails = order_detail::whereHas('product', fn ($q) => $q->where('name', 'like', "%{$query}%"))
            ->orWhereHas('order.customer', fn ($q) => $q->where('name', 'like', "%{$query}%"))
            ->orWhere('order_id', $query)
            ->with(['order.customer', 'product'])
            ->get();

        return view('pages.order-details.index', compact('orderDetails'));
    }

    public function updateStatus(Request $request, order_detail $orderDetail)
    {
        $request->validate([
            'status' => 'required|in:pending,in_production,quality_check,completed',
        ]);

        DB::transaction(function () use ($orderDetail, $request) {
            if ($request->status === 'completed') {
                $this->deductStock($orderDetail);
            } elseif ($orderDetail->getOriginal('status') === 'completed') {
                $this->restoreStock($orderDetail);
            }

            $orderDetail->update(['status' => $request->status]);
        });

        return redirect()->back()->with('success', 'Build status updated successfully.');
    }

    private function deductStock(order_detail $detail): void
    {
        if ($detail->stock_deducted) {
            return;
        }

        product::where('id', $detail->product_id)
            ->where('stock', '>=', $detail->quantity)
            ->decrement('stock', $detail->quantity);

        $detail->update(['stock_deducted' => true]);
    }

    private function restoreStock(order_detail $detail): void
    {
        if (! $detail->stock_deducted) {
            return;
        }

        product::where('id', $detail->product_id)
            ->increment('stock', $detail->quantity);

        $detail->update(['stock_deducted' => false]);
    }
}
