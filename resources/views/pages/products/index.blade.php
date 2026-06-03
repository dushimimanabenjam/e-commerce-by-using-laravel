<x-layouts::app title="Products">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="flex size-9 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-xs">
                    <flux:icon name="shopping-cart" class="size-5" />
                </span>
                <flux:heading size="xl">Products</flux:heading>
            </div>
            @auth
                @if (auth()->user()->is_admin)
                    <flux:button href="{{ route('products.create') }}" wire:navigate variant="primary">Create Product</flux:button>
                @endif
            @endauth
        </div>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        @auth
            @if (auth()->user()->is_admin)
                <form action="{{ route('products.search') }}" method="GET" class="flex gap-2">
                    <flux:input name="query" placeholder="Search products by name..." value="{{ request('query') }}" class="max-w-md" />
                    <flux:button variant="primary" type="submit">Search</flux:button>
                </form>
            @endif
        @endauth

        @if ($products->isEmpty())
            <flux:card>
                <div class="py-8 text-center">
                    <flux:text>No products found.</flux:text>
                    @auth
                        @if (auth()->user()->is_admin)
                            <div class="mt-4">
                                <flux:button variant="primary" href="{{ route('products.create') }}" wire:navigate>Create First Product</flux:button>
                            </div>
                        @endif
                    @endauth
                </div>
            </flux:card>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $i => $product)
                    @php
                        $scheme = match ($i % 3) {
                            0 => ['from' => 'from-indigo-500', 'to' => 'to-indigo-600', 'light' => 'bg-indigo-50', 'dark' => 'dark:bg-indigo-950/20', 'circle' => 'bg-indigo-200/40', 'circle-dark' => 'dark:bg-indigo-800/20', 'top' => 'from-indigo-500'],
                            1 => ['from' => 'from-emerald-500', 'to' => 'to-emerald-600', 'light' => 'bg-emerald-50', 'dark' => 'dark:bg-emerald-950/20', 'circle' => 'bg-emerald-200/40', 'circle-dark' => 'dark:bg-emerald-800/20', 'top' => 'from-emerald-500'],
                            2 => ['from' => 'from-amber-500', 'to' => 'to-amber-600', 'light' => 'bg-amber-50', 'dark' => 'dark:bg-amber-950/20', 'circle' => 'bg-amber-200/40', 'circle-dark' => 'dark:bg-amber-800/20', 'top' => 'from-amber-500'],
                        };
                    @endphp

                    <div class="group relative overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-neutral-700 dark:bg-zinc-900">
                        <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r {{ $scheme['top'] }} to-transparent"></div>

                        @if ($product->image)
                            <div class="aspect-[4/3] w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="size-full object-cover transition-transform duration-300 group-hover:scale-105">
                            </div>
                        @else
                            <div class="flex aspect-[4/3] w-full items-center justify-center bg-zinc-100 dark:bg-zinc-800">
                                <flux:icon name="photo" class="size-8 text-zinc-400" />
                            </div>
                        @endif

                        <div class="p-3">
                            <div class="flex items-start justify-between gap-1">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('products.show', $product) }}" wire:navigate class="text-sm font-semibold leading-tight text-zinc-900 transition-colors hover:text-indigo-600 dark:text-zinc-100 dark:hover:text-indigo-400 line-clamp-1">
                                        {{ $product->name }}
                                    </a>
                                    @if ($product->category)
                                        <span class="mt-0.5 inline-flex items-center rounded-full {{ $scheme['light'] }} px-2 py-0.5 text-[10px] font-medium {{ $scheme['from'] }} {{ $scheme['to'] }} text-white {{ $scheme['dark'] }}">
                                            {{ $product->category->name }}
                                        </span>
                                    @endif
                                </div>
                                <flux:text class="shrink-0 text-sm font-bold text-zinc-800 dark:text-zinc-200">{{ number_format($product->price, 2) }} frw</flux:text>
                            </div>

                            <div class="mt-2 flex items-center gap-1.5">
                                @if ($product->stock <= 0)
                                    <flux:badge variant="danger" size="sm">Out of Stock</flux:badge>
                                @elseif ($product->stock < 10)
                                    <flux:badge variant="warning" size="sm">{{ $product->stock }} left</flux:badge>
                                @else
                                    <flux:badge variant="success" size="sm">{{ $product->stock }} in stock</flux:badge>
                                @endif
                            </div>

                            @if ($product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <flux:button variant="primary" type="submit" class="w-full" size="sm">Add to Cart</flux:button>
                                </form>
                            @else
                                <flux:button disabled class="mt-2 w-full" size="sm">Out of Stock</flux:button>
                            @endif

                            @auth
                                @if (auth()->user()->is_admin)
                                    <div class="mt-2 border-t border-neutral-100 pt-2 dark:border-neutral-800">
                                        <form action="{{ route('products.restock', $product) }}" method="POST" class="mb-1.5 flex items-center gap-1">
                                            @csrf
                                            <flux:input name="quantity" type="number" value="1" min="1" class="!w-14" size="sm" />
                                            <flux:button type="submit" size="xs" variant="primary">Restock</flux:button>
                                        </form>
                                        <div class="flex items-center justify-end gap-1">
                                            <flux:button href="{{ route('products.edit', $product) }}" wire:navigate size="xs" variant="ghost" icon="pencil-square"></flux:button>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <flux:button type="submit" size="xs" variant="ghost" icon="trash" class="text-red-500 hover:text-red-700"></flux:button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>
