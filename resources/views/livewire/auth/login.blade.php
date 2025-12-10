<x-layouts.auth.simple title="Staff Login - Elite Elevators">
    
    <div class="mb-8 text-center">
        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Welcome Back</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Sign in to manage projects & clients</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <flux:input 
            name="email" 
            label="Email Address" 
            type="email" 
            required 
            autofocus 
            placeholder="admin@eliteelevators.com" 
            icon="envelope"
        />

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-[#F65275] hover:text-[#d14060] transition-colors">
                        Forgot password?
                    </a>
                @endif
            </div>
            <flux:input 
                name="password" 
                type="password" 
                required 
                autocomplete="current-password" 
                placeholder="••••••••" 
                viewable
            />
        </div>

        <!-- Remember Me -->
        <flux:checkbox name="remember" label="Remember me" />

        <!-- Submit Button -->
        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#041E42] hover:bg-[#041E42]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#041E42] transition-all duration-200 uppercase tracking-wide">
            Sign In
        </button>
    </form>
</x-layouts.auth.simple>