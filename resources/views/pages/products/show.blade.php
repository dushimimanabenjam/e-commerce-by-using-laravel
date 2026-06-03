<x-layouts::app title="{{ $product->name }}">
    <div class="space-y-6">
        <flux:heading size="xl">{{ $product->name }}</flux:heading>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
                @if ($product->image)
                    <div class="aspect-video w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="size-full object-cover">
                    </div>
                @else
                    <div class="flex aspect-video w-full items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                        <flux:icon name="photo" class="size-16 text-zinc-400" />
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <flux:card>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <flux:text class="text-sm font-medium text-zinc-500">Price</flux:text>
                            <flux:text class="text-2xl font-bold">{{ number_format($product->price, 2) }} frw</flux:text>
                        </div>

                        <flux:separator />

                        <div class="flex items-center justify-between">
                            <flux:text class="text-sm font-medium text-zinc-500">Stock</flux:text>
                            <div class="flex items-center gap-2">
                                <flux:text>{{ $product->stock }}</flux:text>
                                @if ($product->stock <= 0)
                                    <flux:badge variant="danger">Out of Stock</flux:badge>
                                @elseif ($product->stock < 10)
                                    <flux:badge variant="warning">Low Stock</flux:badge>
                                @else
                                    <flux:badge variant="success">In Stock</flux:badge>
                                @endif
                            </div>
                        </div>

                        @if ($product->category)
                            <flux:separator />
                            <div class="flex items-center justify-between">
                                <flux:text class="text-sm font-medium text-zinc-500">Category</flux:text>
                                <flux:badge>{{ $product->category->name }}</flux:badge>
                            </div>
                        @endif

                        @if ($product->description)
                            <flux:separator />
                            <div>
                                <flux:text class="text-sm font-medium text-zinc-500">Description</flux:text>
                                <flux:text class="mt-1">{{ $product->description }}</flux:text>
                            </div>
                        @endif
                    </div>
                </flux:card>

                @if ($product->stock > 0)
                    <flux:card>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <flux:field>
                                <flux:label>Quantity</flux:label>
                                <flux:input name="quantity" type="number" value="1" min="1" max="{{ $product->stock }}" required />
                                <flux:error name="quantity" />
                            </flux:field>
                            <flux:button variant="primary" type="submit" class="w-full">Add to Cart</flux:button>
                        </form>
                    </flux:card>
                @else
                    <flux:button disabled class="w-full">Out of Stock</flux:button>
                @endif

                <div class="flex items-center gap-2">
                    @auth
                        @if (auth()->user()->is_admin)
                            <flux:button href="{{ route('products.edit', $product) }}" wire:navigate variant="primary">Edit</flux:button>
                            <form action="{{ route('products.restock', $product) }}" method="POST" class="inline-flex items-center gap-1">
                                @csrf
                                <flux:input name="quantity" type="number" value="1" min="1" class="!w-16" />
                                <flux:button type="submit" size="sm" variant="primary">Restock</flux:button>
                            </form>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="danger">Delete</flux:button>
                            </form>
                        @endif
                    @endauth
                    <flux:button href="{{ route('products.index') }}" wire:navigate variant="ghost">&larr; Back</flux:button>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
