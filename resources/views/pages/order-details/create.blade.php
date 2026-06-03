<x-layouts::app title="Add Product to Build">
    <div class="space-y-6">
        <flux:heading size="xl">Add Product to Build Order</flux:heading>

        <flux:card>
            <form action="{{ route('order-details.store') }}" method="POST" class="space-y-4">
                @csrf

                <flux:field>
                    <flux:label>Build Order</flux:label>
                    <flux:select name="order_id" placeholder="Select an order">
                        @foreach ($orders as $order)
                            <flux:select.option value="{{ $order->id }}" :selected="request('order_id') == $order->id">
                                Order #{{ $order->id }} - {{ $order->customer->name ?? 'N/A' }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="order_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Product</flux:label>
                    <flux:select name="product_id" placeholder="Select a product">
                        @foreach ($products as $product)
                            <flux:select.option value="{{ $product->id }}">
                                {{ $product->name }} (Stock: {{ $product->stock }})
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="product_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Quantity</flux:label>
                    <flux:input name="quantity" type="number" value="{{ old('quantity', 1) }}" min="1" required />
                    <flux:error name="quantity" />
                </flux:field>

                <div class="flex items-center justify-end gap-3">
                    <flux:button href="{{ route('order-details.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">Add to Build</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
