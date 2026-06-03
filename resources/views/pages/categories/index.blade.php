<x-layouts::app title="Categories">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="flex size-9 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-xs">
                    <flux:icon name="tag" class="size-5" />
                </span>
                <flux:heading size="xl">Categories</flux:heading>
            </div>
            @auth
                @if (auth()->user()->is_admin)
                    <flux:button href="{{ route('categories.create') }}" wire:navigate variant="primary">Create Category</flux:button>
                @endif
            @endauth
        </div>

        @if (session('success'))
            <flux:callout variant="success" heading="{{ session('success') }}" />
        @endif

        @if ($categories->isEmpty())
            <flux:card>
                <div class="py-8 text-center">
                    <flux:text>No categories found.</flux:text>
                    @auth
                        @if (auth()->user()->is_admin)
                            <div class="mt-4">
                                <flux:button variant="primary" href="{{ route('categories.create') }}" wire:navigate>Create First Category</flux:button>
                            </div>
                        @endif
                    @endauth
                </div>
            </flux:card>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($categories as $i => $category)
                    @php
                        $scheme = match ($i % 3) {
                            0 => ['from' => 'from-indigo-500', 'to' => 'to-indigo-600', 'light' => 'bg-indigo-50', 'dark' => 'dark:bg-indigo-950/20', 'circle' => 'bg-indigo-200/40', 'circle-dark' => 'dark:bg-indigo-800/20', 'text' => 'text-indigo-700', 'text-dark' => 'dark:text-indigo-400', 'border' => 'border-indigo-200', 'border-dark' => 'dark:border-indigo-900/50', 'badge' => 'bg-indigo-100', 'badge-dark' => 'dark:bg-indigo-900/50', 'badge-text' => 'text-indigo-600', 'badge-text-dark' => 'dark:text-indigo-400', 'icon' => 'cube'],
                            1 => ['from' => 'from-emerald-500', 'to' => 'to-emerald-600', 'light' => 'bg-emerald-50', 'dark' => 'dark:bg-emerald-950/20', 'circle' => 'bg-emerald-200/40', 'circle-dark' => 'dark:bg-emerald-800/20', 'text' => 'text-emerald-700', 'text-dark' => 'dark:text-emerald-400', 'border' => 'border-emerald-200', 'border-dark' => 'dark:border-emerald-900/50', 'badge' => 'bg-emerald-100', 'badge-dark' => 'dark:bg-emerald-900/50', 'badge-text' => 'text-emerald-600', 'badge-text-dark' => 'dark:text-emerald-400', 'icon' => 'squares-2x2'],
                            2 => ['from' => 'from-amber-500', 'to' => 'to-amber-600', 'light' => 'bg-amber-50', 'dark' => 'dark:bg-amber-950/20', 'circle' => 'bg-amber-200/40', 'circle-dark' => 'dark:bg-amber-800/20', 'text' => 'text-amber-700', 'text-dark' => 'dark:text-amber-400', 'border' => 'border-amber-200', 'border-dark' => 'dark:border-amber-900/50', 'badge' => 'bg-amber-100', 'badge-dark' => 'dark:bg-amber-900/50', 'badge-text' => 'text-amber-600', 'badge-text-dark' => 'dark:text-amber-400', 'icon' => 'bookmark'],
                        };
                    @endphp

                    <a href="{{ route('categories.show', $category) }}" wire:navigate class="group relative overflow-hidden rounded-xl border {{ $scheme['border'] }} {{ $scheme['light'] }} p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md {{ $scheme['border-dark'] }} {{ $scheme['dark'] }}">
                        <div class="absolute right-0 top-0 h-20 w-20 translate-x-6 -translate-y-6 rounded-full {{ $scheme['circle'] }} {{ $scheme['circle-dark'] }}"></div>
                        <div class="relative">
                            <div class="mb-3 flex size-10 items-center justify-center rounded-lg bg-gradient-to-br {{ $scheme['from'] }} {{ $scheme['to'] }} text-white shadow-sm">
                                <flux:icon name="{{ $scheme['icon'] }}" class="size-5" />
                            </div>
                            <flux:heading class="text-lg font-semibold group-hover:underline">{{ $category->name }}</flux:heading>
                            @if ($category->description)
                                <flux:text class="mt-1 line-clamp-2 text-sm text-zinc-500">{{ $category->description }}</flux:text>
                            @endif
                            <div class="mt-4 flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 rounded-full {{ $scheme['badge'] }} px-2.5 py-0.5 text-xs font-medium {{ $scheme['badge-text'] }} {{ $scheme['badge-dark'] }} {{ $scheme['badge-text-dark'] }}">
                                    <flux:icon name="cube" class="size-3" />
                                    {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                                </span>
                                @auth
                                    @if (auth()->user()->is_admin)
                                        <div class="flex items-center gap-1" onclick="event.stopPropagation()">
                                            <flux:button href="{{ route('categories.edit', $category) }}" wire:navigate size="xs" variant="ghost" icon="pencil-square"></flux:button>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <flux:button type="submit" size="xs" variant="ghost" icon="trash" class="text-red-500 hover:text-red-700"></flux:button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>
