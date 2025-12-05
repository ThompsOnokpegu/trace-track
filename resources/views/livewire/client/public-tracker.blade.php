<?php

use function Livewire\Volt\{state, layout, mount};
use App\Models\Project;

// Use the dedicated tracker layout (no sidebar/nav)
layout('components.layouts.tracker'); 

state(['project']);

mount(function ($code) {
    // Find project by the unique tracking code
    $this->project = Project::where('tracking_code', $code)
        ->with(['updates' => fn($q) => $q->latest()])
        ->firstOrFail();
});

?>

<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 relative">
    
    <!-- Brand Header Strip -->
    <div class="bg-[#041E42] w-full h-2"></div>

    <!-- Dark Mode Toggle -->
    <div class="absolute top-6 right-6 z-50" x-data="{ 
        darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggle() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }" x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');">
        <button @click="toggle()" class="p-2.5 rounded-full bg-white dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#041E42] shadow-sm border border-zinc-200 dark:border-zinc-700 transition-all duration-200">
            <!-- Sun Icon -->
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <!-- Moon Icon -->
            <svg x-show="darkMode" style="display: none;" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            
            <!-- Brand & Title -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-[#041E42] dark:text-white tracking-tight uppercase">Elite Elevators</h1>
                <p class="text-sm font-medium text-[#F65275] uppercase tracking-wider mt-1">Project Tracker</p>
                
                <div class="mt-4 inline-block bg-white dark:bg-zinc-800 px-4 py-1.5 rounded-full border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        Tracking ID: <span class="font-mono font-bold text-[#041E42] dark:text-zinc-200 text-base ml-1">{{ $project->tracking_code }}</span>
                    </p>
                </div>
            </div>

            <!-- Project Summary Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border-t-4 border-t-[#041E42] border-x border-b border-zinc-200 dark:border-zinc-700 overflow-hidden mb-8">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-[#041E42] dark:text-white">{{ $project->title }}</h2>
                            <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-1 flex items-center gap-1">
                                <flux:icon.map-pin class="size-4 text-[#F65275]" />
                                {{ $project->location }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-[#041E42]/10 text-[#041E42] dark:bg-blue-500/20 dark:text-blue-300">
                            {{ $project->current_status->label() }}
                        </span>
                    </div>
                    
                    @if($project->installation_date)
                        <div class="mt-6 flex items-center gap-3 text-sm text-[#041E42] dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-700/50 p-4 rounded-xl border border-zinc-100 dark:border-zinc-700">
                            <div class="bg-[#041E42] text-white p-2 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-xs text-zinc-500 uppercase tracking-wide">Scheduled Installation</span>
                                <span class="font-bold text-lg">{{ $project->installation_date->format('F d, Y') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Progress Bar -->
                <div class="bg-zinc-100 dark:bg-zinc-900/50 px-6 py-6 border-t border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">
                        <span>Initiated</span>
                        <span>Production</span>
                        <span>Shipping</span>
                        <span>Installation</span>
                    </div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                        {{-- Calculate width based on stages roughly --}}
                        @php
                            $stageMap = [
                                'scheduled_visit' => 5,
                                'drawing_completed' => 10,
                                'production' => 20,
                                'production_completed' => 30,
                                'shipped' => 40,
                                'arrived_nigeria' => 50,
                                'customs_clearance' => 55,
                                'warehouse_lagos' => 60,
                                'in_transit_site' => 65,
                                'installation_scheduled' => 70,
                                'installation_in_progress' => 80,
                                'installation_completed' => 90,
                                'commissioning' => 95,
                                'handover' => 100,
                            ];
                            $percent = $stageMap[$project->current_status->value] ?? 5;
                        @endphp
                        <div class="h-full bg-[#F65275] rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(246,82,117,0.5)]" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Detailed Timeline -->
            <div class="relative">
                <h3 class="text-lg font-bold text-[#041E42] dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-[#F65275] rounded-full"></span>
                    History Log
                </h3>
                
                <div class="absolute top-12 bottom-0 left-[19px] w-0.5 bg-zinc-200 dark:bg-zinc-700"></div>

                <div class="space-y-8">
                    @foreach($project->updates as $update)
                        <div class="relative flex gap-6 group">
                            <!-- Icon/Dot -->
                            <div class="absolute left-0 mt-1 size-10 rounded-full border-4 border-zinc-50 dark:border-zinc-900 bg-white dark:bg-zinc-800 flex items-center justify-center z-10 shadow-sm group-first:border-[#F65275]/20">
                                <div class="size-3 rounded-full {{ $loop->first ? 'bg-[#F65275]' : 'bg-zinc-300 dark:bg-zinc-600' }}"></div>
                            </div>

                            <!-- Content -->
                            <div class="ml-14 w-full bg-white dark:bg-zinc-800 p-5 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm group-hover:border-[#041E42]/20 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-2">
                                    <h4 class="font-bold text-[#041E42] dark:text-white">
                                        {{ $update->status_key->label() }}
                                    </h4>
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400 font-mono">
                                        {{ $update->created_at->format('M d, h:i A') }}
                                    </span>
                                </div>
                                <p class="text-zinc-600 dark:text-zinc-300 text-sm leading-relaxed">
                                    {{ $update->description }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>