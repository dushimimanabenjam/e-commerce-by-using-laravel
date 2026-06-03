<x-layouts::app title="Checkout">
    <div class="space-y-6">
        <flux:heading size="xl">Checkout</flux:heading>

        @if ($errors->any())
            <flux:callout variant="danger" heading="There were errors with your order.">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </flux:callout>
        @endif

        <flux:card>
            <flux:heading size="lg">Order Summary</flux:heading>
            <table class="mt-4 w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-center">Qty</th>
                        <th class="px-4 py-2 text-right">Price</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="px-4 py-2">{{ $item['name'] }}</td>
                            <td class="px-4 py-2 text-center">{{ $item['quantity'] }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($item['price'], 2) }} frw</td>
                            <td class="px-4 py-2 text-right">{{ number_format($item['price'] * $item['quantity'], 2) }} frw</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold">
                        <td colspan="3" class="px-4 py-2 text-right">Total:</td>
                        <td class="px-4 py-2 text-right">{{ number_format($total, 2) }} frw</td>
                    </tr>
                </tfoot>
            </table>
        </flux:card>

        <flux:card>
            <flux:heading size="lg">Customer Information</flux:heading>

            <form action="{{ route('checkout.store') }}" method="POST" class="mt-4 space-y-4"
                  x-data="{
                    customers: @js($customers->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'email' => $c->email])),
                    selectedCustomer: null,
                    fillCustomer(id) {
                        let customer = this.customers.find(c => c.id == id);
                        if (customer) {
                            this.selectedCustomer = customer;
                        }
                    }
                  }">
                @csrf

                @if ($customers->isNotEmpty())
                    <flux:select label="Select Existing Customer" placeholder="Choose a customer..." name="customer_id"
                                 x-on:change="fillCustomer($event.target.value)">
                        @foreach ($customers as $customer)
                            <flux:select.option value="{{ $customer->id }}">
                                {{ $customer->name }} ({{ $customer->email }})
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:separator text="or fill details below" />
                @endif

                <flux:input label="Name" name="name" type="text" x-bind:value="selectedCustomer?.name ?? ''" />
                <flux:input label="Email" name="email" type="email" x-bind:value="selectedCustomer?.email ?? ''" />

                <div class="flex justify-end gap-3">
                    <flux:button href="{{ route('cart.index') }}" wire:navigate variant="ghost">Back to Cart</flux:button>
                    <flux:button variant="primary" type="submit">Place Order</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
