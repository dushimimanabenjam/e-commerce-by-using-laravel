<x-layouts::app title="Admin Dashboard">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Admin Dashboard</flux:heading>
                <flux:text class="mt-1 text-zinc-500">Overview of your store's performance</flux:text>
            </div>
            <form action="{{ route('admin.search') }}" method="GET" class="flex gap-2">
                <flux:input name="q" placeholder="Search across all data..." class="w-64" />
                <flux:button variant="primary" type="submit">Search</flux:button>
            </form>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-neutral-700 dark:bg-zinc-900">
                <div class="absolute right-0 top-0 h-20 w-20 translate-x-6 -translate-y-6 rounded-full bg-indigo-50 dark:bg-indigo-950/30"></div>
                <div class="relative">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-sm">
                        <flux:icon name="cube" class="size-5" />
                    </div>
                    <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Products</flux:text>
                    <div class="mt-1 flex items-baseline gap-2">
                        <flux:heading class="text-2xl">{{ $productCount }}</flux:heading>
                        <span class="text-xs text-zinc-400">total</span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs">
                        <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                            {{ $inStockProducts }} in stock
                        </span>
                        <span class="flex items-center gap-1 text-red-600 dark:text-red-400">
                            <span class="size-1.5 rounded-full bg-red-500"></span>
                            {{ $outOfStockProducts }} out
                        </span>
                        @if ($lowStockProductsCount > 0)
                            <span class="flex items-center gap-1 text-amber-600 dark:text-amber-400">
                                <span class="size-1.5 rounded-full bg-amber-500"></span>
                                {{ $lowStockProductsCount }} low
                            </span>
                        @endif
                    </div>
                    <flux:button href="{{ route('products.index') }}" variant="ghost" size="sm" class="mt-4" wire:navigate>
                        Manage &rarr;
                    </flux:button>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-neutral-700 dark:bg-zinc-900">
                <div class="absolute right-0 top-0 h-20 w-20 translate-x-6 -translate-y-6 rounded-full bg-rose-50 dark:bg-rose-950/30"></div>
                <div class="relative">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-rose-500 to-rose-600 text-white shadow-sm">
                        <flux:icon name="squares-2x2" class="size-5" />
                    </div>
                    <a href="{{ route('categories.index') }}" wire:navigate class="group/card">
                        <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Categories</flux:text>
                        <div class="mt-1 flex items-baseline gap-2">
                            <flux:heading class="text-2xl transition-colors group-hover/card:text-rose-600 dark:group-hover/card:text-rose-400">{{ $categoryCount }}</flux:heading>
                            <span class="text-xs text-zinc-400">total</span>
                        </div>
                    </a>
                    <flux:button href="{{ route('categories.index') }}" variant="ghost" size="sm" class="mt-8" wire:navigate>
                        Manage &rarr;
                    </flux:button>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-neutral-700 dark:bg-zinc-900">
                <div class="absolute right-0 top-0 h-20 w-20 translate-x-6 -translate-y-6 rounded-full bg-emerald-50 dark:bg-emerald-950/30"></div>
                <div class="relative">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-sm">
                        <flux:icon name="users" class="size-5" />
                    </div>
                    <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Customers</flux:text>
                    <div class="mt-1 flex items-baseline gap-2">
                        <flux:heading class="text-2xl">{{ $customerCount }}</flux:heading>
                        <span class="text-xs text-zinc-400">total</span>
                    </div>
                    <flux:button href="{{ route('customers.index') }}" variant="ghost" size="sm" class="mt-8" wire:navigate>
                        Manage &rarr;
                    </flux:button>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-neutral-700 dark:bg-zinc-900">
                <div class="absolute right-0 top-0 h-20 w-20 translate-x-6 -translate-y-6 rounded-full bg-amber-50 dark:bg-amber-950/30"></div>
                <div class="relative">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-sm">
                        <flux:icon name="shopping-cart" class="size-5" />
                    </div>
                    <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Orders</flux:text>
                    <div class="mt-1 flex items-baseline gap-2">
                        <flux:heading class="text-2xl">{{ $orderCount }}</flux:heading>
                        <span class="text-xs text-zinc-400">total</span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-x-3 gap-y-1 text-xs">
                        @foreach (['pending' => 'bg-amber-400', 'in_production' => 'bg-blue-400', 'quality_check' => 'bg-purple-400', 'completed' => 'bg-emerald-400', 'shipped' => 'bg-indigo-400'] as $status => $dot)
                            @php $count = $orderStatuses[$status] ?? 0; @endphp
                            @if ($count > 0)
                                <span class="flex items-center gap-1 capitalize text-zinc-600 dark:text-zinc-400">
                                    <span class="size-1.5 rounded-full {{ $dot }}"></span>
                                    {{ str_replace('_', ' ', $status) }}: {{ $count }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                    <flux:button href="{{ route('orders.index') }}" variant="ghost" size="sm" class="mt-4" wire:navigate>
                        Manage &rarr;
                    </flux:button>
                </div>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-3">
            <div class="group relative overflow-hidden rounded-xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-emerald-800 dark:from-emerald-950/20 dark:to-zinc-900">
                <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-emerald-200/40 dark:bg-emerald-800/20"></div>
                <div class="relative">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-600 to-emerald-700 text-white shadow-sm">
                        <flux:icon name="archive-box" class="size-5" />
                    </div>
                    <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Total Stock</flux:text>
                    <div class="mt-1 flex items-baseline gap-2">
                        <flux:heading class="text-2xl text-emerald-700 dark:text-emerald-400">{{ number_format($totalStock) }}</flux:heading>
                        <span class="text-xs text-zinc-400">units</span>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-amber-800 dark:from-amber-950/20 dark:to-zinc-900">
                <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-amber-200/40 dark:bg-amber-800/20"></div>
                <div class="relative">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-amber-600 to-amber-700 text-white shadow-sm">
                        <flux:icon name="clipboard-document-list" class="size-5" />
                    </div>
                    <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Reserved in Orders</flux:text>
                    <div class="mt-1 flex items-baseline gap-2">
                        <flux:heading class="text-2xl text-amber-700 dark:text-amber-400">{{ number_format($reservedStock) }}</flux:heading>
                        <span class="text-xs text-zinc-400">units</span>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-emerald-800 dark:from-emerald-950/20 dark:to-zinc-900">
                <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-emerald-200/40 dark:bg-emerald-800/20"></div>
                <div class="relative">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-600 to-emerald-700 text-white shadow-sm">
                        <flux:icon name="check-badge" class="size-5" />
                    </div>
                    <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Available Stock</flux:text>
                    <div class="mt-1 flex items-baseline gap-2">
                        <flux:heading class="text-2xl text-emerald-700 dark:text-emerald-400">{{ number_format($availableStock) }}</flux:heading>
                        <span class="text-xs text-zinc-400">units</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <flux:heading size="lg">Recent Orders</flux:heading>
                    <flux:icon name="clock" class="size-4 text-zinc-400" />
                </div>
                @if ($recentOrders->isEmpty())
                    <flux:text>No orders yet.</flux:text>
                @else
                    <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                        @foreach ($recentOrders as $order)
                            <div class="flex items-center justify-between py-3 transition-colors hover:bg-neutral-50 dark:hover:bg-zinc-800/50 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-8 items-center justify-center rounded-full bg-neutral-100 text-xs font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                        #{{ $order->id }}
                                    </div>
                                    <div>
                                        <flux:text class="text-sm font-medium">{{ $order->customer->name ?? 'N/A' }}</flux:text>
                                        <flux:text class="block text-xs text-zinc-500">{{ $order->created_at->diffForHumans() }}</flux:text>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <flux:text class="text-sm font-semibold">{{ number_format($order->total, 2) }} frw</flux:text>
                                    <flux:badge size="sm" :color="match($order->status) {
                                        'completed', 'shipped' => 'emerald',
                                        'pending' => 'amber',
                                        'in_production' => 'sky',
                                        'quality_check' => 'violet',
                                        default => 'zinc',
                                    }">{{ $order->status }}</flux:badge>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <flux:heading size="lg">Low Stock Products</flux:heading>
                    <flux:icon name="exclamation-triangle" class="size-4 text-amber-400" />
                </div>
                @if ($lowStockProducts->isEmpty())
                    <flux:text>All products are well-stocked.</flux:text>
                @else
                    <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                        @foreach ($lowStockProducts as $product)
                            <div class="flex items-center justify-between py-3 transition-colors hover:bg-neutral-50 dark:hover:bg-zinc-800/50 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-8 items-center justify-center rounded-lg bg-red-50 text-xs font-semibold text-red-600 dark:bg-red-950/30 dark:text-red-400">
                                        <flux:icon name="cube" class="size-4" />
                                    </div>
                                    <flux:text class="text-sm font-medium">{{ $product->name }}</flux:text>
                                </div>
                                <flux:badge size="sm" color="red">{{ $product->stock }} left</flux:badge>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
