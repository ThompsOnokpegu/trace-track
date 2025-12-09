<x-layouts.auth.simple title="Register - Elite Elevators">
    
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-[#041E42] dark:text-white">Create Account</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">Get started with your project tracking</p>
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
            placeholder="you@company.com" 
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
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-[#F65275] hover:bg-[#F65275]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
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