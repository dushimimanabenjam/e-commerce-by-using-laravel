<?php

use App\Services\CartService;
use Livewire\Component;

new class extends Component {
    public int $count = 0;

    public function mount(CartService $cart): void
    {
        $this->count = $cart->count();
    }
}; ?>

<div>
    <a href="{{ route('cart.index') }}" wire:navigate class="relative inline-flex items-center">
        <flux:icon name="shopping-cart" class="size-5" />
        @if ($count > 0)
            <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                {{ $count }}
            </span>
        @endif
    </a>
</div>
