<x-layouts::app title="Customers">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Customers</flux:heading>
            <flux:button href="{{ route('customers.create') }}" wire:navigate variant="primary">Create Customer</flux:button>
        </div>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        <form action="{{ route('customers.search') }}" method="GET" class="flex gap-2">
            <flux:input name="query" placeholder="Search customers by name or email..." value="{{ request('query') }}" class="max-w-md" />
            <flux:button variant="primary" type="submit">Search</flux:button>
        </form>

        @if ($customers->isEmpty())
            <flux:card>
                <div class="py-8 text-center">
                    <flux:text>No customers found.</flux:text>
                    <div class="mt-4">
                        <flux:button variant="primary" href="{{ route('customers.create') }}" wire:navigate>Create First Customer</flux:button>
                    </div>
                </div>
            </flux:card>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Orders</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($customers as $customer)
                        <flux:table.row>
                            <flux:table.cell class="font-medium">
                                <a href="{{ route('customers.show', $customer) }}" wire:navigate>{{ $customer->name }}</a>
                            </flux:table.cell>
                            <flux:table.cell>{{ $customer->email }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge>{{ $customer->orders_count }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <flux:button href="{{ route('customers.edit', $customer) }}" wire:navigate size="sm" variant="ghost">Edit</flux:button>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" size="sm" variant="danger">Delete</flux:button>
                                    </form>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif
    </div>
</x-layouts::app>
