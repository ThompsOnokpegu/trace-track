<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Elite Elevators') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
  
    </head>
    <body class="font-sans antialiased h-full bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100"
          x-data="{ 
              darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
              toggle() {
                  this.darkMode = !this.darkMode;
                  localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                  if (this.darkMode) document.documentElement.classList.add('dark');
                  else document.documentElement.classList.remove('dark');
              }
          }"
          x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');"
    >
        
        <!-- Brand Header Strip -->
        <div class="bg-[#041E42] w-full h-2 fixed top-0 left-0 z-50"></div>

        <!-- Dark Mode Toggle -->
        <div class="absolute top-6 right-6 z-50">
            <button @click="toggle()" class="p-2.5 rounded-full bg-white dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 focus:outline-none shadow-sm border border-zinc-200 dark:border-zinc-700 transition-all">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <svg x-show="darkMode" style="display: none;" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
            </button>
        </div>

        <!-- Main Content -->
        <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                
                <!-- Logo -->
                <a href="/" class="flex flex-col items-center gap-2 mb-8 group" wire:navigate>
                    <div class="bg-white p-2 rounded-xl shadow-md border border-zinc-100 group-hover:scale-105 transition-transform duration-300">
                         <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 375 375">
                            <path fill="#F65275" d="M269.6 178.9c-1.1 0-2.1 0-3.1 0-0.8 0-1.5 0-2.2 0H106.5c-0.7 0-1.4 0-2.1 0-6-0.2-11.2-3-14.8-8-4.6-6.4-4.5-15.6 0.3-21.8 0.2-0.3 0.5-0.7 0.8-1 0-0.1 0.1-0.1 0.1-0.2 0.3-0.4 0.6-0.7 0.8-1 0.3-0.4 0.6-0.7 0.9-1.1 3.7-4.6 7.6-9.3 11.5-14 4.3-5.3 8.6-10.5 12.8-15.8 56.2-68.9 58.1-71.5 62-73.1 7.4-3.1 15.9-0.8 21.2 5.6 3.2 3.9 6.4 7.8 9.6 11.7 2.9 3.6 5.9 7.2 8.8 10.8 2.9 3.6 5.9 7.2 8.8 10.8 3.6 4.4 7.2 8.8 10.8 13.2 3.4 4.1 6.8 8.3 10.1 12.4l9.4 11.5c3.3 4.1 6.6 8.2 10 12.2 2.8 3.4 5.5 6.8 8.3 10.2l4.6 5.6c0.8 1 1.7 2 2.5 3 2.9 3.7 4.4 7.5 4.6 11.8v0c0.3 8-4.8 15.5-12.4 18.2-2.6 0.9-5.3 1.1-7.8 1.1zM107.3 163.3h157c0.9 0 1.8 0 2.8 0 1.8 0.1 4.3 0.2 5.4-0.1 1.4-0.5 2.4-1.8 2.4-3.1 0-0.4 0-1.1-1.2-2.5-0.8-1-1.5-1.9-2.3-2.9l-4.7-5.7c-2.8-3.4-5.5-6.8-8.3-10.2-3.3-4.1-6.7-8.2-10-12.2l-9.4-11.5c-3.3-4.1-6.7-8.2-10.3-12.3-3.6-4.4-7.2-8.8-10.8-13.2-2.9-3.6-5.9-7.2-8.8-10.8-2.9-3.6-5.9-7.2-8.8-10.8-3.2-3.9-6.1-7.5-9-11.1-3.2-3.9-6.4-7.8-9.6-11.7-0.9-1.1-2.6-2-4.2-1.3-0.6 0.2-1.1 0.6-2.3 2L128.2 126c-4.3 5.2-8.5 10.5-12.8 15.7-3.9 4.8-7.8 9.5-11.6 14.2-0.3 0.4-0.6 0.7-0.8 1-0.3 0.3-0.5 0.6-0.8 0.9-0.2 0.3-0.5 0.6-0.7 0.8-0.6 0.8-0.6 2.3 0 3.1 0.8 1.1 1.8 1.6 3.2 1.6 0.6 0 1 0 1.5 0z" />
                            <path fill="#041E42" d="M187.9 337.5c-1.7 0-3.4-0.3-5.1-0.8-5.9-1.9-9.6-6.7-12.3-10.2-0.5-0.6-0.9-1.2-1.4-1.8-4.7-5.8-9.5-11.6-14.2-17.4l-1-1.2c-4.3-5.3-8.7-10.6-13-16l-7.2-8.8c-3.4-4.2-6.8-8.4-10.2-12.6-3.4-4.2-6.8-8.4-10.2-12.6-2.6-3.2-5.2-6.4-7.9-9.6-2.7-3.3-5.4-6.6-8.1-9.9-2.1-2.6-4.2-5.2-6.4-7.8-0.2-0.3-0.4-0.5-0.6-0.8-0.1-0.1-0.2-0.3-0.3-0.4-4.7-6.1-4.9-15.1-0.5-21.5 3.4-5.1 8.9-8.1 15-8.2 2.1-0.1 4.2 0 6.2 0 1 0 1.9 0 2.9 0h156.5c1.6 0 3.6 0 5.8 0.5 7.9 1.9 13.5 9.1 13.9 17.5 0.2 4.6-1.3 8.9-4.6 12.9-1.6 2-3.2 4-4.9 6-0.9 1.1-1.9 2.2-2.8 3.4-5.1 6.2-10.2 12.5-15.3 18.7l-1.4 1.8c-3.5 4.3-7.1 8.6-10.6 12.9l-10.1 12.4c-3.3 4.1-6.7 8.1-10 12.2-3.3 4.1-6.7 8.1-10 12.2-0.9 2.3-5.2 7.4-9.3 12.5-0.6 0.7-1.2 1.5-1.8 2.2l-1.7 2.1C198.7 335 193.3 337.5 187.9 337.5zM107.6 213.4c-0.9 0-1.8 0-2.7 0-1.5 0-2.5 0.6-3.2 1.7-0.6 0.8-0.6 2.3 0 3.1 0.2 0.3 0.5 0.6 0.7 0.9 2 2.5 4.1 5.1 6.3 7.7 2.7 3.3 5.4 6.6 8.1 9.9 2.6 3.2 5.3 6.4 7.9 9.6 3.4 4.2 6.8 8.4 10.2 12.6 3.4 4.2 6.8 8.4 10.2 12.6l7.2 8.8c4.3 5.3 8.7 10.6 13 15.9l1 1.2c4.7 5.8 9.5 11.6 14.2 17.4 0.6 0.7 1.1 1.4 1.7 2.1 1.6 2.2 3.5 4.6 5 5.1 1.6 0.5 3.1-0.5 3.9-1.5l1.7-2.1c0.6-0.7 1.2-1.5 1.8-2.2 4.2-5.1 8.3-10.2 12.5-15.2l1.9-2.3c3.3-4.1 6.7-8.1 10-12.2 3.3-4.1 6.7-8.1 10-12.2l10.1-12.4c3.5-4.3 7.1-8.6 10.6-12.9 5.1-6.2 10.2-12.5 15.3-18.7l1.4-1.8c5.1-6.2 10.2-12.4 15.3-18.6 0.9-1.2 1.9-2.3 2.8-3.4 1.6-2 3.2-4 4.8-5.9 0.8-1 1.2-1.8 1.2-2.4 0-0.1 0-0.1 0-0.1-0.1-1.5-1.1-2.8-2.4-3.1-0.5-0.1-1.3-0.1-2.4-0.1H113.6c-1 0-2 0-3.1 0-1 0-2 0-2.9 0z" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-[#041E42] dark:text-white uppercase tracking-tight">Elite Elevators</h1>
                        <p class="text-xs font-medium text-[#F65275] uppercase tracking-wider">Project Tracker</p>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="mt-4 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white dark:bg-zinc-800 py-8 px-4 shadow-xl border border-zinc-200 dark:border-zinc-700 sm:rounded-2xl sm:px-10 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#041E42] to-[#F65275]"></div>
                    {{ $slot }}
                </div>
                
                <p class="text-center text-xs text-zinc-400 mt-8">
                    &copy; {{ date('Y') }} Elite Elevators & Escalators
                </p>
            </div>
        </div>

        @fluxScripts
    </body>
</html>