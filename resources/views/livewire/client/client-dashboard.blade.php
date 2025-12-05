<?php

use function Livewire\Volt\{state, layout, with};
use Illuminate\Support\Facades\Auth;

layout('components.layouts.app');

with(fn () => [
    'projects' => Auth::user()->projects()->latest()->get()
]);

?>

<section class="w-full">
    <flux:main>
        <div class="mb-8">
            <flux:heading size="xl" level="1">My Projects</flux:heading>
            <flux:subheading class="mt-2">Track the status of your installations.</flux:subheading>
        </div>

        @if($projects->isEmpty())
            <div class="text-center py-12 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700">
                <div class="mx-auto size-12 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                    <flux:icon.cube class="size-6 text-zinc-400" />
                </div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white">No Active Projects</h3>
                <p class="mt-1 text-zinc-500 dark:text-zinc-400 text-sm">Contact support if you believe this is an error.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <a href="{{ route('track.public', $project->tracking_code) }}" class="group block h-full">
                        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 h-full hover:border-blue-500 dark:hover:border-blue-500 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <div class="size-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                    <flux:icon.cube class="size-6" />
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $project->current_status->color() }}-100 text-{{ $project->current_status->color() }}-800 dark:bg-{{ $project->current_status->color() }}-500/20 dark:text-{{ $project->current_status->color() }}-300">
                                    {{ $project->current_status->label() }}
                                </span>
                            </div>
                            
                            <h3 class="font-bold text-zinc-900 dark:text-white text-lg mb-1 group-hover:text-blue-600 transition-colors">{{ $project->title }}</h3>
                            <p class="text-zinc-500 dark:text-zinc-400 text-sm mb-4">{{ $project->location }}</p>
                            
                            <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                <span class="text-xs text-zinc-500 font-mono">{{ $project->tracking_code }}</span>
                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400 flex items-center gap-1">
                                    Track <flux:icon.arrow-right class="size-3" />
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </flux:main>
</section>