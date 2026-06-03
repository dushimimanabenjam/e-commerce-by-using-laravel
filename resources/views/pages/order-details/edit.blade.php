<x-layouts::app title="Edit Build Detail">
    <div class="space-y-6">
        <flux:heading size="xl">Edit Build Detail</flux:heading>

        <flux:card>
            <form action="{{ route('order-details.update', $orderDetail) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <flux:field>
                    <flux:label>Build Order</flux:label>
                    <flux:select name="order_id" placeholder="Select an order">
                        @foreach ($orders as $order)
                            <flux:select.option value="{{ $order->id }}" :selected="$orderDetail->order_id === $order->id">
                                Order #{{ $order->id }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="order_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Product</flux:label>
                    <flux:select name="product_id" placeholder="Select a product">
                        @foreach ($products as $product)
                            <flux:select.option value="{{ $product->id }}" :selected="$orderDetail->product_id === $product->id">
                                {{ $product->name }} (Stock: {{ $product->stock }})
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="product_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Quantity</flux:label>
                    <flux:input name="quantity" type="number" value="{{ old('quantity', $orderDetail->quantity) }}" min="1" required />
                    <flux:error name="quantity" />
                </flux:field>

                <flux:field>
                    <flux:label>Build Status</flux:label>
                    <flux:select name="status">
                        <flux:select.option value="pending" :selected="$orderDetail->status === 'pending'">Pending</flux:select.option>
                        <flux:select.option value="in_production" :selected="$orderDetail->status === 'in_production'">In Production</flux:select.option>
                        <flux:select.option value="quality_check" :selected="$orderDetail->status === 'quality_check'">Quality Check</flux:select.option>
                        <flux:select.option value="completed" :selected="$orderDetail->status === 'completed'">Completed</flux:select.option>
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>

                <div class="flex items-center justify-end gap-3">
                    <flux:button href="{{ route('order-details.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">Update Build Detail</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
