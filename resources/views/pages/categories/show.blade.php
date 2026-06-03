<x-layouts::app title="{{ $category->name }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="flex size-9 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-xs">
                    <flux:icon name="tag" class="size-5" />
                </span>
                <flux:heading size="xl">{{ $category->name }}</flux:heading>
            </div>
            @auth
                @if (auth()->user()->is_admin)
                    <div class="flex items-center gap-2">
                        <flux:button href="{{ route('categories.edit', $category) }}" wire:navigate variant="primary">Edit</flux:button>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="danger">Delete</flux:button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        <div class="relative overflow-hidden rounded-xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white p-6 shadow-sm dark:border-indigo-900/30 dark:from-indigo-950/20 dark:to-zinc-900">
            <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-indigo-200/40 dark:bg-indigo-800/20"></div>
            <div class="relative grid gap-6 sm:grid-cols-2">
                <div>
                    <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Slug</flux:text>
                    <flux:text class="mt-1 text-base font-medium">{{ $category->slug }}</flux:text>
                </div>
                <div>
                    <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Products</flux:text>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-400">
                            <flux:icon name="cube" class="size-4" />
                            {{ $category->products_count }}
                        </span>
                    </div>
                </div>
            </div>

            @if ($category->description)
                <flux:separator class="my-4" />
                <flux:text class="text-xs font-medium tracking-wide uppercase text-zinc-500">Description</flux:text>
                <flux:text class="mt-1 leading-relaxed">{{ $category->description }}</flux:text>
            @endif
        </div>

        <flux:button href="{{ route('categories.index') }}" wire:navigate variant="ghost">&larr; Back to Categories</flux:button>
    </div>
</x-layouts::app>
