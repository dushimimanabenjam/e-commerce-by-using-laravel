<x-layouts::app title="Create Product">
    <div class="space-y-6">
        <flux:heading size="xl">Create Product</flux:heading>

        <flux:card>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <flux:field>
                    <flux:label>Name</flux:label>
                    <flux:input name="name" type="text" value="{{ old('name') }}" required />
                    <flux:error name="name" />
                </flux:field>

                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Price</flux:label>
                        <flux:input name="price" type="number" step="0.01" value="{{ old('price') }}" required />
                        <flux:error name="price" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Stock</flux:label>
                        <flux:input name="stock" type="number" value="{{ old('stock') }}" required />
                        <flux:error name="stock" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Category</flux:label>
                    <flux:select name="category_id" placeholder="Select a category">
                        <flux:select.option value="">No Category</flux:select.option>
                        @foreach ($categories as $cat)
                            <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="category_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Image</flux:label>
                    <flux:input name="image" type="file" accept="image/*" />
                    <flux:error name="image" />
                </flux:field>

                <div class="flex items-center justify-end gap-3">
                    <flux:button href="{{ route('products.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">Create Product</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
