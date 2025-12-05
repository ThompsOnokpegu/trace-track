<?php

use App\Models\Project;
use function Livewire\Volt\{state, layout, with, usesPagination};

layout('components.layouts.app');

usesPagination();
state(['search' => '']);

with(fn () => [
    'projects' => Project::query()
        ->with('client') // Eager load the client (User)
        ->when($this->search, function($query) {
            $query->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('tracking_code', 'like', '%'.$this->search.'%');
        })
        ->latest()
        ->paginate(10)
]);

?>

<section class="w-full">
    <flux:main>
        <!-- Header -->
        <div class="flex max-md:flex-col items-center justify-between gap-4 mb-6">
            <div>
                <flux:heading size="xl" level="1">Project Tracker</flux:heading>
                <flux:subheading class="mt-2">Monitor ongoing installations.</flux:subheading>
            </div>
            
            <div class="flex gap-2 w-full md:w-auto">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search projects..." class="w-full md:w-64" />
                <flux:button href="{{ route('admin.projects.create') }}" variant="primary" icon="plus">New Project</flux:button>
            </div>
        </div>

        <!-- Tailwind Table (Replaces Flux Table) -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Project Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Last Update</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($projects as $project)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $project->client->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $project->client->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-zinc-900 dark:text-zinc-100">{{ $project->title }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">Code: <span class="font-mono bg-zinc-100 dark:bg-zinc-800 px-1 rounded">{{ $project->tracking_code }}</span></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $project->current_status->color() }}-100 text-{{ $project->current_status->color() }}-800 dark:bg-{{ $project->current_status->color() }}-500/20 dark:text-{{ $project->current_status->color() }}-300">
                                        {{ $project->current_status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $project->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <flux:button size="sm" icon="pencil-square" variant="ghost" href="{{ route('admin.projects.edit', $project) }}" title="Edit Details"></flux:button>
                                        <flux:button size="sm" icon="arrow-path" variant="subtle" href="{{ route('admin.projects.manage', $project) }}">Manage</flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                    No projects found. <a href="{{ route('admin.projects.create') }}" class="underline hover:text-blue-500">Create one?</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </flux:main>
</section>