<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Elite Elevators') }} - Project Tracker</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxStyles
    </head>
    <!-- 
      FIXES:
      1. Changed 'h-screen' to 'min-h-screen' (mobile) and 'lg:h-screen' (desktop)
      2. Changed 'w-screen' to 'w-full' to prevent horizontal scrolling issues
      3. Removed global 'overflow-hidden' on mobile, enabled only on desktop ('lg:overflow-hidden')
    -->
    <body class="min-h-screen lg:h-screen w-full lg:overflow-hidden flex flex-col font-sans antialiased bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 relative"
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
        
        <!-- Background Elements (Fixed relative to viewport on desktop, absolute on mobile) -->
        <div class="fixed inset-0 z-0 bg-[#041E42]">
            <div class="absolute inset-0 bg-gradient-to-b from-[#041E42] via-[#041E42] to-[#020F21]" aria-hidden="true"></div>
            <!-- Subtle grid pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.05%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-20"></div>
        </div>

        <!-- Navbar -->
        <nav class="relative z-10 w-full px-6 py-6 flex items-center justify-between max-w-7xl mx-auto shrink-0">
            <div class="flex items-center gap-3">
                {{-- Brand header (logo + name) --}}
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-md">
                        <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="500" zoomAndPan="magnify" viewBox="0 0 375 374.999991" height="500" preserveAspectRatio="xMidYMid meet" version="1.0"><defs><clipPath id="f71811bcb6"><path d="M 86 197 L 290 197 L 290 337.5 L 86 337.5 Z M 86 197 " clip-rule="nonzero"/></clipPath></defs><path fill="#f65275" d="M 269.621094 178.898438 C 268.53125 178.898438 267.488281 178.855469 266.515625 178.820312 C 265.742188 178.789062 264.992188 178.757812 264.296875 178.757812 L 106.46875 178.765625 C 105.757812 178.769531 105.054688 178.777344 104.332031 178.753906 C 98.359375 178.59375 93.097656 175.773438 89.578125 170.816406 C 85.027344 164.394531 85.144531 155.238281 89.851562 149.035156 C 90.089844 148.714844 90.371094 148.375 90.671875 148.035156 C 90.71875 147.976562 90.761719 147.921875 90.808594 147.863281 C 91.15625 147.449219 91.402344 147.148438 91.644531 146.847656 C 91.9375 146.492188 92.230469 146.136719 92.53125 145.78125 C 96.269531 141.167969 100.132812 136.449219 103.996094 131.730469 C 108.277344 126.457031 112.550781 121.222656 116.828125 115.984375 L 173.074219 47.09375 C 174.90625 44.828125 177.308594 42.277344 181.109375 40.664062 C 188.515625 37.5625 197.011719 39.824219 202.277344 46.269531 C 205.453125 50.132812 208.644531 54.046875 211.835938 57.964844 C 214.769531 61.546875 217.714844 65.152344 220.65625 68.757812 C 223.601562 72.359375 226.539062 75.957031 229.480469 79.550781 C 233.074219 83.964844 236.667969 88.363281 240.261719 92.761719 C 243.640625 96.898438 247.019531 101.035156 250.390625 105.171875 L 259.816406 116.714844 C 263.140625 120.789062 266.464844 124.863281 269.789062 128.925781 C 272.558594 132.296875 275.328125 135.699219 278.101562 139.101562 L 282.699219 144.734375 C 283.539062 145.746094 284.378906 146.761719 285.1875 147.773438 C 288.125 151.425781 289.636719 155.289062 289.785156 159.566406 C 289.785156 159.570312 289.785156 159.574219 289.785156 159.578125 C 290.054688 167.570312 284.949219 175.054688 277.367188 177.773438 C 274.78125 178.6875 272.089844 178.898438 269.621094 178.898438 Z M 107.285156 163.320312 L 264.296875 163.320312 C 265.167969 163.320312 266.105469 163.355469 267.070312 163.394531 C 268.910156 163.460938 271.429688 163.5625 272.515625 163.179688 C 273.894531 162.679688 274.925781 161.363281 274.886719 160.121094 C 274.871094 159.738281 274.84375 159.03125 273.714844 157.632812 C 272.949219 156.667969 272.171875 155.734375 271.390625 154.796875 L 266.707031 149.054688 C 263.945312 145.664062 261.191406 142.28125 258.421875 138.914062 C 255.082031 134.828125 251.753906 130.753906 248.425781 126.671875 L 239.003906 115.132812 C 235.625 110.992188 232.253906 106.859375 228.875 102.726562 C 225.277344 98.324219 221.679688 93.921875 218.09375 89.515625 C 215.160156 85.933594 212.214844 82.328125 209.273438 78.722656 C 206.332031 75.125 203.390625 71.523438 200.453125 67.929688 C 197.261719 64.019531 194.085938 60.121094 190.90625 56.257812 C 189.976562 55.117188 188.335938 54.292969 186.730469 54.964844 C 186.164062 55.203125 185.644531 55.597656 184.480469 57.035156 L 128.214844 125.957031 C 123.945312 131.179688 119.679688 136.398438 115.429688 141.636719 C 111.523438 146.410156 107.675781 151.113281 103.859375 155.816406 C 103.519531 156.21875 103.273438 156.519531 103.027344 156.820312 C 102.773438 157.132812 102.515625 157.445312 102.257812 157.757812 C 102.0625 158.011719 101.792969 158.335938 101.589844 158.546875 C 101.003906 159.320312 101.011719 160.859375 101.589844 161.679688 C 102.359375 162.757812 103.375 163.285156 104.777344 163.320312 C 105.332031 163.34375 105.808594 163.332031 106.292969 163.328125 Z M 107.285156 163.320312 " fill-opacity="1" fill-rule="nonzero"/><g clip-path="url(#f71811bcb6)"><path fill="#f65275" d="M 187.914062 337.5 C 186.199219 337.5 184.476562 337.246094 182.796875 336.722656 C 176.867188 334.855469 173.160156 330.023438 170.457031 326.492188 C 169.984375 325.882812 169.523438 325.277344 169.066406 324.710938 C 164.335938 318.894531 159.601562 313.101562 154.867188 307.308594 L 153.867188 306.082031 C 149.519531 300.785156 145.175781 295.460938 140.828125 290.136719 L 133.613281 281.300781 C 130.195312 277.109375 126.785156 272.933594 123.371094 268.757812 C 119.957031 264.578125 116.542969 260.402344 113.128906 256.210938 C 110.511719 253 107.886719 249.792969 105.261719 246.582031 C 102.570312 243.292969 99.882812 240 97.199219 236.710938 C 95.074219 234.144531 92.949219 231.539062 90.824219 228.929688 C 90.609375 228.664062 90.410156 228.394531 90.21875 228.125 C 90.117188 228 90.019531 227.875 89.925781 227.75 C 85.273438 221.679688 85.058594 212.613281 89.425781 206.207031 C 92.855469 201.148438 98.3125 198.144531 104.398438 197.988281 C 106.5 197.914062 108.609375 197.9375 110.648438 197.960938 C 111.617188 197.972656 112.589844 197.984375 113.558594 197.984375 L 270.101562 197.984375 C 271.6875 197.980469 273.679688 197.976562 275.902344 198.511719 C 283.753906 200.453125 289.441406 207.640625 289.785156 216.003906 C 289.984375 220.558594 288.445312 224.914062 285.214844 228.945312 C 283.628906 230.945312 281.992188 232.933594 280.355469 234.921875 C 279.429688 236.046875 278.5 237.175781 277.574219 238.316406 C 272.488281 244.5625 267.402344 250.777344 262.320312 256.996094 L 260.886719 258.75 C 257.363281 263.0625 253.839844 267.382812 250.316406 271.707031 L 240.214844 284.089844 C 236.902344 288.152344 233.578125 292.226562 230.25 296.300781 C 226.929688 300.367188 223.605469 304.433594 220.292969 308.5 L 218.378906 310.84375 C 214.230469 315.910156 210.070312 321 205.929688 326.09375 C 205.328125 326.832031 204.71875 327.578125 204.105469 328.324219 L 202.425781 330.382812 C 198.664062 334.96875 193.324219 337.5 187.914062 337.5 Z M 107.574219 213.378906 C 106.644531 213.378906 105.734375 213.386719 104.828125 213.417969 C 103.371094 213.457031 102.371094 213.988281 101.621094 215.09375 C 101.042969 215.9375 101.050781 217.429688 101.621094 218.175781 C 101.851562 218.441406 102.085938 218.75 102.28125 219.042969 C 104.328125 221.554688 106.429688 224.136719 108.5625 226.714844 C 111.273438 230.039062 113.960938 233.324219 116.644531 236.609375 C 119.273438 239.824219 121.902344 243.039062 124.523438 246.253906 C 127.933594 250.4375 131.347656 254.613281 134.757812 258.789062 C 138.175781 262.96875 141.589844 267.148438 145.003906 271.335938 L 152.21875 280.175781 C 156.558594 285.492188 160.890625 290.804688 165.238281 296.101562 L 166.25 297.339844 C 170.992188 303.140625 175.734375 308.941406 180.476562 314.773438 C 181.027344 315.453125 181.574219 316.167969 182.136719 316.898438 C 183.785156 319.050781 185.652344 321.488281 187.113281 321.945312 C 188.675781 322.433594 190.207031 321.433594 191.03125 320.429688 L 192.738281 318.339844 C 193.328125 317.621094 193.921875 316.898438 194.511719 316.167969 C 198.671875 311.054688 202.84375 305.949219 207 300.871094 L 208.902344 298.542969 C 212.210938 294.480469 215.539062 290.40625 218.863281 286.335938 C 222.1875 282.269531 225.507812 278.199219 228.820312 274.132812 L 238.921875 261.753906 C 242.445312 257.425781 245.976562 253.101562 249.503906 248.78125 L 250.941406 247.023438 C 256.015625 240.820312 261.09375 234.609375 266.171875 228.371094 C 267.113281 227.214844 268.0625 226.0625 269.011719 224.910156 C 270.589844 222.992188 272.167969 221.078125 273.71875 219.125 C 274.53125 218.109375 274.917969 217.304688 274.890625 216.699219 C 274.890625 216.6875 274.890625 216.679688 274.890625 216.667969 C 274.832031 215.210938 273.792969 213.867188 272.480469 213.542969 C 271.984375 213.421875 271.171875 213.421875 270.125 213.425781 L 113.558594 213.425781 C 112.535156 213.425781 111.511719 213.414062 110.488281 213.402344 C 109.492188 213.390625 108.523438 213.378906 107.574219 213.378906 Z M 107.574219 213.378906 " fill-opacity="1" fill-rule="nonzero"/></g></svg>
                    </span>
                </a>
            </div>
            
            <div class="flex items-center gap-4">
                <button @click="toggle()" class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white/70 hover:text-white transition-colors">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <svg x-show="darkMode" style="display: none;" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </button>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-white hover:text-[#F65275] transition-colors">Dashboard &rarr;</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-white hover:text-[#F65275] transition-colors">Staff Login</a>
                @endauth
            </div>
        </nav>

        <!-- Main Content (Flex-1 allows it to expand or shrink) -->
        <main class="relative z-10 flex-1 flex flex-col items-center justify-center px-6 text-center w-full max-w-7xl mx-auto py-12 lg:py-0">
            
            <!-- Trust Badge -->
            <div class="mb-8 animate-fade-in-up">
                <div class="inline-flex items-center rounded-full px-3 py-1 text-sm leading-6 text-zinc-300 ring-1 ring-white/10 hover:ring-white/20 backdrop-blur-sm bg-white/5">
                    Trusted by top developers. <a href="https://eliteelevatorsandescalators.com" class="font-semibold text-[#F65275] ml-2"><span class="absolute inset-0" aria-hidden="true"></span>Main Site <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
            
            <!-- Headlines -->
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl max-w-4xl drop-shadow-sm">
                Real-Time Project <span class="text-[#F65275]">Intelligence.</span>
            </h1>
            
            <p class="mt-6 text-lg leading-8 text-zinc-300 max-w-2xl mx-auto">
                Track the manufacturing, shipping, and installation of your elevator systems in real-time. Full transparency from production to handover.
            </p>
            
            <!-- CTA -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('track.search') }}" class="w-full sm:w-auto rounded-xl bg-[#F65275] px-8 py-3.5 text-lg font-semibold text-white shadow-lg hover:bg-[#d14060] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F65275] transition-all duration-200 hover:scale-105">
                    Track Your Project
                </a>
            </div>

            <!-- Condensed Features (Horizontal Strip) -->
            <div class="mt-16 flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-zinc-400">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white/10 rounded-lg"><flux:icon.arrow-path class="size-4 text-[#F65275]" /></div>
                    <span class="text-sm font-medium">Live Status Updates</span>
                </div>
                <div class="hidden sm:block w-1 h-1 bg-zinc-600 rounded-full"></div>
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white/10 rounded-lg"><flux:icon.device-phone-mobile class="size-4 text-[#F65275]" /></div>
                    <span class="text-sm font-medium">WhatsApp Notifications</span>
                </div>
                <div class="hidden sm:block w-1 h-1 bg-zinc-600 rounded-full"></div>
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white/10 rounded-lg"><flux:icon.shield-check class="size-4 text-[#F65275]" /></div>
                    <span class="text-sm font-medium">Full Transparency</span>
                </div>
            </div>

        </main>

        <!-- Footer (Naturally at bottom via flex layout) -->
        <footer class="relative z-10 shrink-0 border-t border-white/5 bg-[#020F21]/50 backdrop-blur-sm mt-auto">
            <div class="mx-auto max-w-7xl px-6 py-4 flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500">
                <p>&copy; {{ date('Y') }} Elite Elevators & Escalators.</p>
                <div class="flex gap-4 mt-2 sm:mt-0">
                    <a href="#" class="hover:text-zinc-300 transition-colors">Privacy</a>
                    <a href="https://eliteelevatorsandescalators.com/contact-us" class="hover:text-zinc-300 transition-colors">Support</a>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>