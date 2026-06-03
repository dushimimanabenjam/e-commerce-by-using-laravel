<x-layouts::app title="Search Results">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Search Results</flux:heading>
            <flux:button href="{{ route('admin.dashboard') }}" wire:navigate variant="ghost">&larr; Back</flux:button>
        </div>

        <form action="{{ route('admin.search') }}" method="GET" class="flex gap-2">
            <flux:input name="q" placeholder="Search products, customers, orders, categories..." value="{{ $query }}" class="flex-1" />
            <flux:button variant="primary" type="submit">Search</flux:button>
        </form>

        @if ($products->isNotEmpty())
            <flux:card>
                <flux:heading size="lg" class="mb-4">Products ({{ $products->count() }})</flux:heading>
                <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach ($products as $product)
                        <div class="flex items-center justify-between py-2">
                            <flux:text class="text-sm font-medium">
                                <a href="{{ route('products.edit', $product) }}" wire:navigate class="hover:underline">#{{ $product->id }} {{ $product->name }}</a>
                            </flux:text>
                            <flux:text class="text-sm text-zinc-500">{{ number_format($product->price, 2) }} frw ({{ $product->stock }} in stock)</flux:text>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        @endif

        @if ($customers->isNotEmpty())
            <flux:card>
                <flux:heading size="lg" class="mb-4">Customers ({{ $customers->count() }})</flux:heading>
                <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach ($customers as $customer)
                        <div class="flex items-center justify-between py-2">
                            <flux:text class="text-sm font-medium">
                                <a href="{{ route('customers.show', $customer) }}" wire:navigate class="hover:underline">{{ $customer->name }}</a>
                            </flux:text>
                            <flux:text class="text-sm text-zinc-500">{{ $customer->email }}</flux:text>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        @endif

        @if ($categories->isNotEmpty())
            <flux:card>
                <flux:heading size="lg" class="mb-4">Categories ({{ $categories->count() }})</flux:heading>
                <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach ($categories as $category)
                        <div class="flex items-center justify-between py-2">
                            <flux:text class="text-sm font-medium">
                                <a href="{{ route('categories.show', $category) }}" wire:navigate class="hover:underline">{{ $category->name }}</a>
                            </flux:text>
                            <flux:text class="text-sm text-zinc-500">{{ $category->slug }}</flux:text>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        @endif

        @if ($orders->isNotEmpty())
            <flux:card>
                <flux:heading size="lg" class="mb-4">Orders ({{ $orders->count() }})</flux:heading>
                <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach ($orders as $order)
                        <div class="flex items-center justify-between py-2">
                            <flux:text class="text-sm font-medium">
                                <a href="{{ route('orders.show', $order) }}" wire:navigate class="hover:underline">Order #{{ $order->id }}</a>
                            </flux:text>
                            <flux:text class="text-sm text-zinc-500">{{ $order->customer->name ?? 'N/A' }} &middot; {{ $order->status }}</flux:text>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        @endif

        @if ($products->isEmpty() && $customers->isEmpty() && $categories->isEmpty() && $orders->isEmpty())
            <flux:card>
                <div class="py-8 text-center">
                    <flux:text>No results found for "{{ $query }}".</flux:text>
                </div>
            </flux:card>
        @endif
    </div>
</x-layouts::app>
