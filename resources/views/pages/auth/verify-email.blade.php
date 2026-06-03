<x-layouts::auth :title="__('Email verification')">
    <div class="flex flex-col gap-6">
        <div class="relative overflow-hidden rounded-xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white p-6 text-center shadow-sm dark:border-indigo-900/50 dark:from-indigo-950/20 dark:to-zinc-900">
            <div class="absolute right-0 top-0 h-32 w-32 translate-x-10 -translate-y-10 rounded-full bg-indigo-100/60 dark:bg-indigo-900/20"></div>
            <div class="relative">
                <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 4.2C22 3.5 21.5 3 20.8 3H3.2C2.5 3 2 3.5 2 4.2V19.8C2 20.5 2.5 21 3.2 21H20.8C21.5 21 22 20.5 22 19.8V4.2Z"/><path d="m22 4-10 8L2 4"/></svg>
                </div>
                <flux:heading size="lg" class="text-indigo-900 dark:text-indigo-200">Verify your email</flux:heading>
                <flux:text class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('Thanks for signing up! Please verify your email address by clicking the link we just sent you.') }}
                </flux:text>
            </div>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="relative overflow-hidden rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-4 shadow-sm dark:border-emerald-900/50 dark:from-emerald-950/20 dark:to-zinc-900">
                <div class="flex items-center gap-3">
                    <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-emerald-500 text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <flux:text class="text-sm font-medium text-emerald-700 dark:text-emerald-300">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </flux:text>
                </div>
            </div>
        @endif

        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <flux:button type="submit" variant="primary" class="w-full">
                        {{ __('Resend verification email') }}
                    </flux:button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:button variant="ghost" type="submit" class="w-full cursor-pointer text-sm" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::auth>