<x-layouts::app title="Create Category">
    <div class="space-y-6">
        <flux:heading size="xl">Create Category</flux:heading>

        <flux:card>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <flux:field>
                    <flux:label>Name</flux:label>
                    <flux:input name="name" type="text" value="{{ old('name') }}" required />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Slug</flux:label>
                    <flux:input name="slug" type="text" value="{{ old('slug') }}" required />
                    <flux:error name="slug" />
                </flux:field>

                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea name="description" rows="3">{{ old('description') }}</flux:textarea>
                    <flux:error name="description" />
                </flux:field>

                <div class="flex items-center justify-end gap-3">
                    <flux:button href="{{ route('categories.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">Create Category</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
