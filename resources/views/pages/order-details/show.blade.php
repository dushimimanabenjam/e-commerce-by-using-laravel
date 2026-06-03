<x-layouts::app title="Build Detail">
    <div class="space-y-6">
        <flux:heading size="xl">Build Detail</flux:heading>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        <flux:card>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Order</flux:text>
                    <flux:text>
                        <a href="{{ route('orders.show', $orderDetail->order_id) }}" wire:navigate class="hover:underline">Order #{{ $orderDetail->order_id }}</a>
                    </flux:text>
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Customer</flux:text>
                    <flux:text>{{ $orderDetail->order->customer->name ?? 'N/A' }}</flux:text>
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Product</flux:text>
                    <flux:text>{{ $orderDetail->product->name ?? 'N/A' }}</flux:text>
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Quantity</flux:text>
                    <flux:text>{{ $orderDetail->quantity }}</flux:text>
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Build Status</flux:text>
                    <flux:badge variant="{{ $orderDetail->status === 'completed' ? 'success' : ($orderDetail->status === 'in_production' ? 'warning' : '') }}">
                        {{ str_replace('_', ' ', ucfirst($orderDetail->status)) }}
                    </flux:badge>
                </div>
            </div>
        </flux:card>

        <div class="flex items-center gap-2">
            <flux:button href="{{ route('order-details.edit', $orderDetail) }}" wire:navigate variant="primary">Edit</flux:button>
            <form action="{{ route('order-details.destroy', $orderDetail) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <flux:button type="submit" variant="danger">Delete</flux:button>
            </form>
            <flux:button href="{{ route('order-details.index') }}" wire:navigate variant="ghost">&larr; Back to Build Queue</flux:button>
        </div>
    </div>
</x-layouts::app>
