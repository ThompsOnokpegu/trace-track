<x-layouts.auth.simple title="Register Staff - Elite Elevators">
    
    <div class="mb-8 text-center">
        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Create Account</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Set up a new staff administrator</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <flux:input 
            name="name" 
            label="Full Name" 
            type="text" 
            required 
            autofocus 
            placeholder="John Doe" 
            icon="user"
        />

        <!-- Email -->
        <flux:input 
            name="email" 
            label="Email Address" 
            type="email" 
            required 
            placeholder="staff@eliteelevators.com" 
            icon="envelope"
        />

        <!-- Password -->
        <flux:input 
            name="password" 
            label="Password" 
            type="password" 
            required 
            placeholder="••••••••" 
            viewable
        />

        <!-- Confirm Password -->
        <flux:input 
            name="password_confirmation" 
            label="Confirm Password" 
            type="password" 
            required 
            placeholder="••••••••" 
        />

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#041E42] hover:bg-[#041E42]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#041E42] transition-all duration-200 uppercase tracking-wide">
                Create Account
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-zinc-600 dark:text-zinc-400">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-medium text-[#041E42] dark:text-white hover:text-[#F65275] dark:hover:text-[#F65275] transition-colors">
                Log in here
            </a>
        </p>
    </div>
</x-layouts.auth.simple>