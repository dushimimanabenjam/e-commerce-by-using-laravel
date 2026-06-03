<x-layouts::app title="Edit Customer">
    <div class="space-y-6">
        <flux:heading size="xl">Edit Customer</flux:heading>

        <flux:card>
            <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <flux:field>
                    <flux:label>Name</flux:label>
                    <flux:input name="name" type="text" value="{{ old('name', $customer->name) }}" required />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input name="email" type="email" value="{{ old('email', $customer->email) }}" required />
                    <flux:error name="email" />
                </flux:field>

                <div class="flex items-center justify-end gap-3">
                    <flux:button href="{{ route('customers.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">Update Customer</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
