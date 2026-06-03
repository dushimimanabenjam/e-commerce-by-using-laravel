<x-layouts::app title="Edit Product">
    <div class="space-y-6">
        <flux:heading size="xl">Edit Product</flux:heading>

        <flux:card>
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <flux:field>
                    <flux:label>Name</flux:label>
                    <flux:input name="name" type="text" value="{{ old('name', $product->name) }}" required />
                    <flux:error name="name" />
                </flux:field>

                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Price</flux:label>
                        <flux:input name="price" type="number" step="0.01" value="{{ old('price', $product->price) }}" required />
                        <flux:error name="price" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Stock</flux:label>
                        <flux:input name="stock" type="number" value="{{ old('stock', $product->stock) }}" required />
                        <flux:error name="stock" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Category</flux:label>
                    <flux:select name="category_id" placeholder="Select a category">
                        <flux:select.option value="">No Category</flux:select.option>
                        @foreach ($categories as $cat)
                            <flux:select.option value="{{ $cat->id }}" :selected="$product->category_id === $cat->id">{{ $cat->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="category_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Image</flux:label>
                    @if ($product->image)
                        <div class="mb-2 flex items-center gap-2">
                            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}" alt="" class="h-16 w-16 rounded object-cover">
                            <flux:text size="sm">Current image</flux:text>
                        </div>
                    @endif
                    <flux:input name="image" type="file" accept="image/*" />
                    <flux:error name="image" />
                </flux:field>

                <div class="flex items-center justify-end gap-3">
                    <flux:button href="{{ route('products.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                    <flux:button variant="primary" type="submit">Update Product</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
