<x-layouts::app title="Create Customer">
    <div class="space-y-6">
        <flux:heading size="xl">Create Customer</flux:heading>

        <flux:card>
            <form action="{{ route('customers.store') }}" method="POST" class="space-y-4">
                @csrf

                <flux:field>
                    <flux:label>Name</flux:label>
                    <flux:input name="name" type="text" value="{{ old('name') }}" required />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input name="email" type="email" value="{{ old('email') }}" required />
                    <flux:error name="email" />
                </flux:field>

                <div class="flex items-center justify-end gap-3">
                    <flux:button href="{{ route('customers.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">Create Customer</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
