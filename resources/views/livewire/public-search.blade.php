<?php

use function Livewire\Volt\{state, layout, rules};

layout('components.layouts.tracker');

state(['code' => '']);

rules(['code' => 'required|string|min:8']);

$track = function () {
    $this->validate();
    // Redirect to the specific tracker page
    $this->redirect(route('track.public', ['code' => $this->code]), navigate: true);
};

?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 flex flex-col">
    
    <!-- Brand Header Strip -->
    <div class="bg-[#041E42] w-full h-2"></div>

    <div class="flex-1 flex flex-col items-center justify-center p-4 sm:p-8">
        
        <div class="w-full max-w-md space-y-8">
            
            <!-- Logo / Branding -->
            <div class="text-center">
                <!-- add a logo image here -->
                <img src="{{ asset('img/elite-logo.webp') }}" alt="Elite Elevators Logo" class="mx-auto h-12 w-auto my-4">
                <p class="text-sm font-medium text-[#F65275] uppercase tracking-wider mt-1">Project Tracker</p>
            </div>

            <!-- Search Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-700 p-8">
                <div class="mb-6 text-center">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Track Your Installation</h2>
                    <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-2">Enter the tracking code provided by your project manager.</p>
                </div>

                <form wire:submit="track" class="space-y-6">
                    <div>
                        <label for="code" class="sr-only">Tracking Code</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <flux:icon.magnifying-glass class="h-5 w-5 text-zinc-400" />
                            </div>
                            <input wire:model="code" type="text" id="code" 
                                class="block w-full pl-10 pr-3 py-4 border border-zinc-300 dark:border-zinc-600 rounded-xl leading-5 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-[#F65275] focus:border-[#F65275] sm:text-sm uppercase font-mono tracking-widest transition duration-150 ease-in-out" 
                                placeholder="E.g. X8Y2Z9A1">
                        </div>
                        @error('code') <span class="text-[#F65275] text-xs mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#041E42] hover:bg-[#041E42]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#041E42] transition duration-150 ease-in-out uppercase tracking-wide">
                        Track Status
                    </button>
                </form>
            </div>

            <!-- Footer Help -->
            <p class="text-center text-xs text-zinc-400 dark:text-zinc-500">
                Lost your code? <a href="https://eliteelevatorsandescalators.com/contact-us" class="underline hover:text-[#041E42] dark:hover:text-white">Contact Support</a>
            </p>
        </div>
    </div>
</div>