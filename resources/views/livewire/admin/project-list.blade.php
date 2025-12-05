<?php

use App\Models\Project;
use function Livewire\Volt\{state, layout, with, usesPagination};

layout('components.layouts.app');

usesPagination();
state(['search' => '']);

with(fn () => [
    'projects' => Project::query()
        ->with('client')
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

        <flux:separator class="mb-6" />

        <!-- Tailwind Table (Replaces Flux Table) -->
        <div class="bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Project Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Last Update</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-zinc-200">
                        @forelse($projects as $project)
                            <tr class="hover:bg-zinc-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-zinc-900">{{ $project->client->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-zinc-500">{{ $project->client->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-zinc-900">{{ $project->title }}</div>
                                    <div class="text-xs text-zinc-500">Code: <span class="font-mono bg-zinc-100 px-1 rounded">{{ $project->tracking_code }}</span></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $project->current_status->color() }}-100 text-{{ $project->current_status->color() }}-800">
                                        {{ $project->current_status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500">
                                    {{ $project->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <flux:button href="{{ route('admin.projects.manage',$project->id) }}" size="sm" icon="pencil-square" variant="ghost">Manage</flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-zinc-500">
                                    No projects found.
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
