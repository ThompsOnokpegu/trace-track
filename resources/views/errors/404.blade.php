<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Page Not Found - Elite Elevators</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 h-screen flex flex-col">
        
        <!-- Brand Header Strip -->
        <div class="bg-[#041E42] w-full h-2"></div>

        <div class="flex-1 flex flex-col items-center justify-center p-4">
            
            <!-- Brand Logo Text -->
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-[#041E42] dark:text-white uppercase tracking-tight">Elite Elevators</h1>
                <p class="text-xs font-medium text-[#F65275] uppercase tracking-wider mt-1">Project Tracker</p>
            </div>

            <!-- Error Card -->
            <div class="max-w-md w-full bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden text-center p-8 relative">
                
                <!-- Background visual -->
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#041E42] to-[#F65275]"></div>

                <div class="mb-6 relative">
                    <!-- Giant 404 watermark -->
                    <h1 class="text-9xl font-black text-zinc-100 dark:text-zinc-700/50 select-none leading-none">404</h1>
                    
                    <!-- Overlay Message -->
                    <div class="absolute inset-0 flex items-center justify-center pt-6">
                        <h2 class="text-2xl font-bold text-[#041E42] dark:text-white bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm px-4 py-1 rounded-lg">
                            Project Not Found
                        </h2>
                    </div>
                </div>

                <p class="text-zinc-600 dark:text-zinc-300 mb-8 leading-relaxed">
                    We couldn't find the page or tracking ID you were looking for. Please double-check your code or contact support.
                </p>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="{{ route('track.search') }}" class="flex items-center justify-center w-full py-3 px-4 bg-[#041E42] hover:bg-[#041E42]/90 text-white font-bold rounded-xl transition shadow-lg shadow-[#041E42]/20 gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Return Home
                    </a>
                    
                    <a href="https://eliteelevatorsandescalators.com/contact-us" class="flex items-center justify-center w-full py-3 px-4 bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-600 transition">
                        Contact Support
                    </a>
                </div>
            </div>

            <div class="mt-8 text-zinc-400 text-xs">
                &copy; {{ date('Y') }} Elite Elevators. All rights reserved.
            </div>
        </div>
    </body>
</html>