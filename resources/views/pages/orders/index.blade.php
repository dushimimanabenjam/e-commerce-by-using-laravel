<x-layouts::app title="Build Orders">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Build Orders</flux:heading>
        </div>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        <form action="{{ route('orders.search') }}" method="GET" class="flex gap-2">
            <flux:input name="query" placeholder="Search orders by ID or customer name..." value="{{ request('query') }}" class="max-w-md" />
            <flux:button variant="primary" type="submit">Search</flux:button>
        </form>

        @if ($orders->isEmpty())
            <flux:card>
                <div class="py-8 text-center">
                    <flux:text>No build orders found.</flux:text>
                </div>
            </flux:card>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <flux:card>
                        <div class="flex items-center justify-between">
                            <div>
                                <flux:heading size="lg">
                                    <a href="{{ route('orders.show', $order) }}" wire:navigate class="hover:underline">Order ID: {{ $order->id }}</a>
                                </flux:heading>
                                <flux:text class="mt-1">{{ $order->customer->name ?? 'N/A' }}</flux:text>
                            </div>
                            <div class="text-right">
                                <flux:badge>{{ str_replace('_', ' ', ucfirst($order->status)) }}</flux:badge>
                                <flux:text size="sm" class="mt-1">Items: {{ $order->orderDetails->count() }}</flux:text>
                            </div>
                        </div>
                        @auth
                            @if (auth()->user()->is_admin)
                                <div class="mt-3 flex items-center gap-2">
                                    <flux:button href="{{ route('orders.show', $order) }}" wire:navigate size="sm">View</flux:button>
                                    <flux:button href="{{ route('orders.edit', $order) }}" wire:navigate size="sm" variant="ghost">Edit</flux:button>
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" size="sm" variant="danger">Delete</flux:button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </flux:card>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>
