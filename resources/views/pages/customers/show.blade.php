<x-layouts::app title="{{ $customer->name }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ $customer->name }}</flux:heading>
            <div class="flex items-center gap-2">
                <flux:button href="{{ route('customers.edit', $customer) }}" wire:navigate variant="primary">Edit</flux:button>
                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <flux:button type="submit" variant="danger">Delete</flux:button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        <flux:card>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Email</flux:text>
                    <flux:text>{{ $customer->email }}</flux:text>
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Created</flux:text>
                    <flux:text>{{ $customer->created_at->format('F j, Y') }}</flux:text>
                </div>
            </div>
        </flux:card>

        <div>
            <flux:heading size="lg">Orders</flux:heading>

            @if ($customer->orders->isEmpty())
                <flux:card class="mt-4">
                    <div class="py-4 text-center">
                        <flux:text>No orders for this customer.</flux:text>
                    </div>
                </flux:card>
            @else
                <div class="mt-4 space-y-3">
                    @foreach ($customer->orders as $order)
                        <flux:card>
                            <div class="flex items-center justify-between">
                                <div>
                                    <flux:heading size="sm">
                                        <a href="{{ route('orders.show', $order) }}" wire:navigate class="hover:underline">Order #{{ $order->id }}</a>
                                    </flux:heading>
                                    <flux:text size="sm" class="mt-1">{{ $order->created_at->format('F j, Y') }}</flux:text>
                                </div>
                                <flux:badge>{{ str_replace('_', ' ', ucfirst($order->status)) }}</flux:badge>
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            @endif
        </div>

        <flux:button href="{{ route('customers.index') }}" wire:navigate variant="ghost">&larr; Back to Customers</flux:button>
    </div>
</x-layouts::app>
