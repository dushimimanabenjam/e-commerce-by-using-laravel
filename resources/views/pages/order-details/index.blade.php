<x-layouts::app title="Build Queue">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Build Queue</flux:heading>
        </div>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        <form action="{{ route('order-details.search') }}" method="GET" class="flex gap-2">
            <flux:input name="query" placeholder="Search build queue by product, customer, or order ID..." value="{{ request('query') }}" class="max-w-md" />
            <flux:button variant="primary" type="submit">Search</flux:button>
        </form>

        @if ($orderDetails->isEmpty())
            <flux:card>
                <div class="py-8 text-center">
                    <flux:text>No items in the build queue.</flux:text>
                </div>
            </flux:card>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Order</flux:table.column>
                    <flux:table.column>Customer</flux:table.column>
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
                    @foreach ($orderDetails as $detail)
                        <flux:table.row>
                            <flux:table.cell>
                                <a href="{{ route('orders.show', $detail->order_id) }}" wire:navigate class="hover:underline">Order #{{ $detail->order_id }}</a>
                            </flux:table.cell>
                            <flux:table.cell>{{ $detail->order->customer->name ?? 'N/A' }}</flux:table.cell>
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
                                            <a href="{{ route('order-details.edit', $detail) }}" wire:navigate>
                                                <flux:button size="sm" variant="ghost">Edit</flux:button>
                                            </a>
                                            <form action="{{ route('order-details.destroy', $detail) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <flux:button type="submit" size="sm" variant="danger">Remove</flux:button>
                                            </form>
                                        </div>
                                    </flux:table.cell>
                                @endif
                            @endauth
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif
    </div>
</x-layouts::app>
