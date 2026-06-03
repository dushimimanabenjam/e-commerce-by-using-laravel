<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        @auth
            @if (auth()->user()->is_admin)
                <div>
                    <flux:heading size="xl">Dashboard</flux:heading>
                    <flux:text class="mt-1 text-zinc-500">Overview of your store's performance</flux:text>
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
                            </div>
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
            @else
                <div class="mb-1 flex items-center gap-3">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-xs">
                        <flux:icon name="sparkles" class="size-4" />
                    </span>
                    <flux:heading size="xl">Welcome to BuildPro</flux:heading>
                </div>

                <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div class="group relative overflow-hidden rounded-xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white p-6 text-center shadow-xs transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-indigo-900/50 dark:from-indigo-950/20 dark:to-zinc-900">
                        <div class="absolute right-0 top-0 h-16 w-16 translate-x-4 -translate-y-4 rounded-full bg-indigo-200/40 dark:bg-indigo-800/20"></div>
                        <div class="relative">
                            <div class="mx-auto mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-xs">
                                <flux:icon name="cube" class="size-5" />
                            </div>
                            <flux:heading size="lg" class="text-indigo-700 dark:text-indigo-400">{{ $productCount }}+</flux:heading>
                            <flux:text class="mt-1">Products in Stock</flux:text>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-6 text-center shadow-xs transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-emerald-900/50 dark:from-emerald-950/20 dark:to-zinc-900">
                        <div class="absolute right-0 top-0 h-16 w-16 translate-x-4 -translate-y-4 rounded-full bg-emerald-200/40 dark:bg-emerald-800/20"></div>
                        <div class="relative">
                            <div class="mx-auto mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-xs">
                                <flux:icon name="truck" class="size-5" />
                            </div>
                            <flux:heading size="lg" class="text-emerald-700 dark:text-emerald-400">Free Delivery</flux:heading>
                            <flux:text class="mt-1">On orders over $200</flux:text>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-6 text-center shadow-xs transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-amber-900/50 dark:from-amber-950/20 dark:to-zinc-900">
                        <div class="absolute right-0 top-0 h-16 w-16 translate-x-4 -translate-y-4 rounded-full bg-amber-200/40 dark:bg-amber-800/20"></div>
                        <div class="relative">
                            <div class="mx-auto mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-xs">
                                <flux:icon name="star" class="size-5" />
                            </div>
                            <flux:heading size="lg" class="text-amber-700 dark:text-amber-400">4.9 ★</flux:heading>
                            <flux:text class="mt-1">Customer Satisfaction</flux:text>
                        </div>
                    </div>
                </div>

                <div class="relative flex flex-col items-center justify-center gap-6 overflow-hidden rounded-xl border border-indigo-100 bg-gradient-to-br from-indigo-50 via-white to-emerald-50 p-12 text-center shadow-sm dark:border-indigo-900/30 dark:from-indigo-950/20 dark:via-zinc-900 dark:to-emerald-950/20">
                    <div class="absolute -right-20 -top-20 h-40 w-40 rounded-full bg-indigo-200/20 blur-3xl dark:bg-indigo-600/10"></div>
                    <div class="absolute -bottom-20 -left-20 h-40 w-40 rounded-full bg-emerald-200/20 blur-3xl dark:bg-emerald-600/10"></div>
                    <div class="relative max-w-2xl">
                        <div class="mx-auto mb-4 flex size-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-sm">
                            <flux:icon name="shopping-bag" class="size-6" />
                        </div>
                        <flux:heading size="xl" class="bg-gradient-to-r from-indigo-600 via-emerald-600 to-amber-600 bg-clip-text text-transparent dark:from-indigo-400 dark:via-emerald-400 dark:to-amber-400">
                            Built for the Job. Priced for the Budget.
                        </flux:heading>
                        <flux:text class="mt-4 text-base leading-relaxed">
                            From lumber and hardware to plumbing and electrical — we stock everything you need to bring your next project to life. Skip the supply yard lines and order online for quick pickup or fast delivery straight to your site.
                        </flux:text>
                        <div class="mt-8 flex flex-wrap justify-center gap-3">
                            <flux:button href="{{ route('products.index') }}" variant="primary" wire:navigate>
                                Browse Products
                            </flux:button>
                            <flux:button href="{{ route('categories.index') }}" variant="ghost" wire:navigate>
                                Shop by Category
                            </flux:button>
                        </div>
                    </div>

                    <div class="relative mt-4 grid gap-6 border-t border-indigo-200/30 pt-8 sm:grid-cols-3 dark:border-indigo-800/30">
                        <div class="text-center">
                            <div class="mx-auto mb-2 flex size-8 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">
                                <flux:icon name="rocket-launch" class="size-4" />
                            </div>
                            <flux:heading class="text-sm font-semibold text-indigo-700 dark:text-indigo-400">Fast Shipping</flux:heading>
                            <flux:text class="mt-1 text-sm">Most orders ship within 24 hours</flux:text>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto mb-2 flex size-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400">
                                <flux:icon name="currency-dollar" class="size-4" />
                            </div>
                            <flux:heading class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">Trade Pricing</flux:heading>
                            <flux:text class="mt-1 text-sm">Exclusive discounts for pros</flux:text>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto mb-2 flex size-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400">
                                <flux:icon name="chat-bubble-left-right" class="size-4" />
                            </div>
                            <flux:heading class="text-sm font-semibold text-amber-700 dark:text-amber-400">Expert Support</flux:heading>
                            <flux:text class="mt-1 text-sm">Talk to real builders, not bots</flux:text>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

    </div>
</x-layouts::app>
