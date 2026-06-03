<x-layouts::app title="Create Build Order">
    <div class="space-y-6">
        <flux:heading size="xl">Create Build Order</flux:heading>

        <flux:card>
            <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
                @csrf

                <flux:field>
                    <flux:label>Customer</flux:label>
                    <flux:select name="customer_id" placeholder="Select a customer">
                        @foreach ($customers as $customer)
                            <flux:select.option value="{{ $customer->id }}">
                                {{ $customer->name }} ({{ $customer->email }})
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="customer_id" />
                </flux:field>

                <div class="flex items-center justify-between">
                    <flux:button href="{{ route('customers.create') }}" wire:navigate size="sm" variant="ghost">+ Create new customer</flux:button>
                    <div class="flex items-center gap-3">
                        <flux:button href="{{ route('orders.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                        <flux:button variant="primary" type="submit">Create Build Order</flux:button>
                    </div>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
