<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-900 antialiased dark:bg-black">
        <div class="relative flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="pointer-events-none fixed inset-0 overflow-hidden">
                <div class="absolute -left-40 -top-40 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl"></div>
                <div class="absolute -bottom-40 -right-40 h-80 w-80 rounded-full bg-emerald-500/10 blur-3xl"></div>
                <div class="absolute left-1/2 top-1/2 h-60 w-60 -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-500/5 blur-3xl"></div>
            </div>
            <div class="relative flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-10 w-10 mb-1 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-indigo-700 shadow-sm">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                    </span>
                    <span class="text-sm font-semibold text-zinc-500 dark:text-zinc-400">e-commerce</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
