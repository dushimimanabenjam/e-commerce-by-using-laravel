<x-layouts::app title="My Orders">
    <div class="space-y-6">
        <flux:heading size="xl">My Orders</flux:heading>

        @if ($orders->isEmpty())
            <flux:card>
                <div class="py-8 text-center">
                    <flux:text>You have no orders yet.</flux:text>
                    <div class="mt-4">
                        <flux:button variant="primary" href="{{ route('products.index') }}" wire:navigate>Start Shopping</flux:button>
                    </div>
                </div>
            </flux:card>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <flux:card>
                        <div class="flex items-center justify-between">
                            <div>
                                <flux:heading size="lg">Order</flux:heading>
                                <flux:text class="mt-1">{{ $order->created_at->format('F j, Y g:i A') }}</flux:text>
                            </div>
                            <div class="text-right">
                                <flux:badge>{{ str_replace('_', ' ', ucfirst($order->status)) }}</flux:badge>
                                <div class="mt-1 font-bold">{{ number_format($order->total, 2) }} frw</div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <flux:button href="{{ route('orders.show', $order) }}" wire:navigate size="sm">View Details</flux:button>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>
