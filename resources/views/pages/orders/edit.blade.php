<x-layouts::app title="Edit Order ID: {{ $order->id }}">
    <div class="space-y-6">
        <flux:heading size="xl">Edit Order ID: {{ $order->id }}</flux:heading>

        <flux:card>
            <form action="{{ route('orders.update', $order) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <flux:field>
                    <flux:label>Customer</flux:label>
                    <flux:select name="customer_id" placeholder="Select a customer">
                        @foreach ($customers as $customer)
                            <flux:select.option value="{{ $customer->id }}" :selected="$order->customer_id === $customer->id">
                                {{ $customer->name }} ({{ $customer->email }})
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="customer_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:select name="status">
                        <flux:select.option value="pending" :selected="$order->status === 'pending'">Pending</flux:select.option>
                        <flux:select.option value="in_production" :selected="$order->status === 'in_production'">In Production</flux:select.option>
                        <flux:select.option value="quality_check" :selected="$order->status === 'quality_check'">Quality Check</flux:select.option>
                        <flux:select.option value="completed" :selected="$order->status === 'completed'">Completed</flux:select.option>
                        <flux:select.option value="shipped" :selected="$order->status === 'shipped'">Shipped</flux:select.option>
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>

                <div class="flex items-center justify-end gap-3">
                    <flux:button href="{{ route('orders.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">Update Order</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
