<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="antialiased">
        {{-- Branded gradient background and centered container --}}
        <div class="min-h-screen flex items-center justify-center py-12 px-4" style="background: linear-gradient(180deg, rgba(4,30,66,0.95) 0%, rgba(246,82,117,0.06) 100%);">
            <div class="w-full max-w-md">
                {{-- Brand header (logo + name) --}}
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 mb-6" wire:navigate>
                    <span class="flex h-12 w-12 items-center justify-center rounded-md bg-[#F65275]">
                        <x-app-logo-icon class="h-8 w-8 fill-current text-white" />
                    </span>
                    <span class="text-center text-white font-extrabold text-lg leading-tight">{{ config('app.name', 'Elite Elevators') }}</span>
                    <p class="text-xs text-zinc-200">Project Tracker</p>
                </a>

                {{-- Slot: auth card (login/register) --}}
                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>

        @fluxScripts
    </body>
</html>
