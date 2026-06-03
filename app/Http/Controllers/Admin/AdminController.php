<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\customer;
use App\Models\order;
use App\Models\order_detail;
use App\Models\product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalStock = product::sum('stock');
        $reservedStock = order_detail::whereIn('status', ['pending', 'in_production'])->sum('quantity');
        $orderStatuses = order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');

        return view('admin.dashboard', [
            'productCount' => product::count(),
            'customerCount' => customer::count(),
            'orderCount' => order::count(),
            'categoryCount' => category::count(),
            'inStockProducts' => product::where('stock', '>', 0)->count(),
            'outOfStockProducts' => product::where('stock', 0)->count(),
            'lowStockProductsCount' => product::where('stock', '>', 0)->where('stock', '<', 10)->count(),
            'totalStock' => $totalStock,
            'reservedStock' => $reservedStock,
            'availableStock' => max(0, $totalStock - $reservedStock),
            'orderStatuses' => $orderStatuses,
            'recentOrders' => order::with('customer')->latest()->take(5)->get(),
            'lowStockProducts' => product::where('stock', '>', 0)->where('stock', '<', 10)->take(5)->get(),
        ]);
    }

    public function dashboard()
    {
        $productCount = product::count();
        $categoryCount = category::count();
        $customerCount = customer::count();
        $orderCount = order::count();

        if (auth()->user()?->is_admin) {
            $totalStock = product::sum('stock');
            $reservedStock = order_detail::whereIn('status', ['pending', 'in_production'])->sum('quantity');
            $orderStatuses = order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');

            return view('dashboard', [
                'productCount' => $productCount,
                'categoryCount' => $categoryCount,
                'customerCount' => $customerCount,
                'orderCount' => $orderCount,
                'inStockProducts' => product::where('stock', '>', 0)->count(),
                'outOfStockProducts' => product::where('stock', 0)->count(),
                'totalStock' => $totalStock,
                'reservedStock' => $reservedStock,
                'availableStock' => max(0, $totalStock - $reservedStock),
                'orderStatuses' => $orderStatuses,
            ]);
        }

        return view('dashboard', [
            'productCount' => $productCount,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (blank($query)) {
            return redirect()->route('admin.dashboard');
        }

        $products = product::where('name', 'like', "%{$query}%")
            ->orWhere('id', $query)
            ->get();

        $customers = customer::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->get();

        $categories = category::where('name', 'like', "%{$query}%")
            ->get();

        $orders = order::where('id', $query)
            ->orWhereHas('customer', fn ($q) => $q->where('name', 'like', "%{$query}%"))
            ->with('customer')
            ->get();

        return view('admin.search', compact('query', 'products', 'customers', 'categories', 'orders'));
    }
}
