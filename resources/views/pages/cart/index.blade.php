<x-layouts::app title="Shopping Cart">
    <div class="space-y-6">
        <flux:heading size="xl">Shopping Cart</flux:heading>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        @if ($items->isEmpty())
            <flux:card>
                <div class="py-8 text-center">
                    <flux:text>Your cart is empty.</flux:text>
                    <div class="mt-4">
                        <flux:button variant="primary" href="{{ route('products.index') }}" wire:navigate>Continue Shopping</flux:button>
                    </div>
                </div>
            </flux:card>
        @else
            <flux:card class="overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="px-4 py-3 text-left font-medium">Product</th>
                            <th class="px-4 py-3 text-left font-medium">Price</th>
                            <th class="px-4 py-3 text-center font-medium">Quantity</th>
                            <th class="px-4 py-3 text-right font-medium">Subtotal</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($item['image'])
                                            <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}" class="size-12 rounded object-cover">
                                        @else
                                            <div class="flex size-12 items-center justify-center rounded bg-zinc-100 dark:bg-zinc-800">
                                                <flux:icon name="photo" class="size-6 text-zinc-400" />
                                            </div>
                                        @endif
                                        <span class="font-medium">{{ $item['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ number_format($item['price'], 2) }} frw</td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('cart.update', $item['product_id']) }}" method="POST" class="flex items-center justify-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="flex size-8 items-center justify-center rounded border border-zinc-300 dark:border-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 disabled:opacity-50 disabled:cursor-not-allowed" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 rounded border border-zinc-300 px-2 py-1 text-center text-sm dark:border-zinc-600 dark:bg-zinc-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" onchange="this.form.submit()">
                                        <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="flex size-8 items-center justify-center rounded border border-zinc-300 dark:border-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-800">+</button>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-right">{{ number_format($item['price'] * $item['quantity'], 2) }} frw</td>
                                <td class="px-4 py-3 text-right">
                                    <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button variant="danger" size="sm" type="submit">Remove</flux:button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </flux:card>

            <div class="flex items-center justify-between">
                <flux:text class="text-lg font-bold">Total: {{ number_format($total, 2) }} frw</flux:text>
                <div class="flex gap-3">
                    <flux:button href="{{ route('products.index') }}" wire:navigate>Continue Shopping</flux:button>
                    <flux:button variant="primary" href="{{ route('checkout.index') }}" wire:navigate>Proceed to Checkout</flux:button>
                </div>
            </div>
        @endif
    </div>
</x-layouts::app>
