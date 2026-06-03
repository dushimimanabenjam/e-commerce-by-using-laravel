<x-layouts::app :title="auth()->user()?->is_admin ? 'Order ID: '.$order->id : 'Order Details'">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">
                    @auth
                        @if (auth()->user()->is_admin)
                            Order ID: {{ $order->id }}
                        @else
                            Order Details
                        @endif
                    @endauth
                </flux:heading>
                <flux:text class="mt-1 text-zinc-500">{{ $order->created_at->format('F j, Y g:i A') }}</flux:text>
            </div>
            @auth
                @if (auth()->user()->is_admin)
                    <div class="flex items-center gap-2">
                        <flux:button href="{{ route('orders.edit', $order) }}" wire:navigate variant="primary">Edit</flux:button>
                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="danger">Delete</flux:button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <flux:card>
                <flux:heading size="lg">Order Details</flux:heading>
                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <flux:text class="text-sm font-medium text-zinc-500">Customer</flux:text>
                        <flux:text>{{ $order->customer->name ?? 'N/A' }}</flux:text>
                    </div>
                    <flux:separator />
                    <div class="flex items-center justify-between">
                        <flux:text class="text-sm font-medium text-zinc-500">Email</flux:text>
                        <flux:text>{{ $order->customer->email ?? 'N/A' }}</flux:text>
                    </div>
                    <flux:separator />
                    <div class="flex items-center justify-between">
                        <flux:text class="text-sm font-medium text-zinc-500">Status</flux:text>
                        <flux:badge>{{ str_replace('_', ' ', ucfirst($order->status)) }}</flux:badge>
                    </div>
                    <flux:separator />
                    <div class="flex items-center justify-between">
                        <flux:text class="text-sm font-medium text-zinc-500">Created</flux:text>
                        <flux:text>{{ $order->created_at->format('F j, Y g:i A') }}</flux:text>
                    </div>
                </div>
            </flux:card>

            @auth
                @if (auth()->user()->is_admin)
                    <flux:card>
                        <flux:heading size="lg">Update Status</flux:heading>
                        <form action="{{ route('orders.update-status', $order) }}" method="POST" class="mt-4 space-y-4">
                            @csrf
                            @method('PATCH')
                            <flux:select name="status">
                                <flux:select.option value="pending" :selected="$order->status === 'pending'">Pending</flux:select.option>
                                <flux:select.option value="in_production" :selected="$order->status === 'in_production'">In Production</flux:select.option>
                                <flux:select.option value="quality_check" :selected="$order->status === 'quality_check'">Quality Check</flux:select.option>
                                <flux:select.option value="completed" :selected="$order->status === 'completed'">Completed</flux:select.option>
                                <flux:select.option value="shipped" :selected="$order->status === 'shipped'">Shipped</flux:select.option>
                            </flux:select>
                            <flux:button variant="primary" type="submit">Update Status</flux:button>
                        </form>
                    </flux:card>
                @endif
            @endauth
        </div>

        <flux:card>
            <flux:heading size="lg" class="mb-4">Products to Build</flux:heading>

            <flux:table class="mt-4">
                <flux:table.columns>
                    <flux:table.column>Product</flux:table.column>
                    <flux:table.column>Quantity</flux:table.column>
                    <flux:table.column>Build Status</flux:table.column>
                    @auth
                        @if (auth()->user()->is_admin)
                            <flux:table.column>Actions</flux:table.column>
                        @endif
                    @endauth
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($order->orderDetails as $detail)
                        <flux:table.row>
                            <flux:table.cell>{{ $detail->product->name ?? 'N/A' }}</flux:table.cell>
                            <flux:table.cell>{{ $detail->quantity }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge variant="{{ $detail->status === 'completed' ? 'success' : ($detail->status === 'in_production' ? 'warning' : '') }}">
                                    {{ str_replace('_', ' ', ucfirst($detail->status)) }}
                                </flux:badge>
                            </flux:table.cell>
                            @auth
                                @if (auth()->user()->is_admin)
                                    <flux:table.cell>
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('order-details.update-status', $detail) }}" method="POST" class="flex items-center gap-1">
                                                @csrf
                                                @method('PATCH')
                                                <flux:select name="status" class="text-sm">
                                                    <flux:select.option value="pending" :selected="$detail->status === 'pending'">Pending</flux:select.option>
                                                    <flux:select.option value="in_production" :selected="$detail->status === 'in_production'">In Production</flux:select.option>
                                                    <flux:select.option value="quality_check" :selected="$detail->status === 'quality_check'">Quality Check</flux:select.option>
                                                    <flux:select.option value="completed" :selected="$detail->status === 'completed'">Completed</flux:select.option>
                                                </flux:select>
                                                <flux:button size="sm" type="submit">Update</flux:button>
                                            </form>
                                            <form action="{{ route('order-details.destroy', $detail) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <flux:button size="sm" variant="danger" type="submit">Remove</flux:button>
                                            </form>
                                        </div>
                                    </flux:table.cell>
                                @endif
                            @endauth
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center text-zinc-500">No products added to this build order yet.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>

        @auth
            @if (auth()->user()->is_admin)
                <flux:button href="{{ route('orders.index') }}" wire:navigate variant="ghost">&larr; Back to Orders</flux:button>
            @else
                <flux:button href="{{ route('products.index') }}" wire:navigate variant="primary">&larr; Continue Shopping</flux:button>
            @endif
        @else
            <flux:button href="{{ route('products.index') }}" wire:navigate variant="primary">&larr; Continue Shopping</flux:button>
        @endauth
    </div>
</x-layouts::app>
