<?php

use App\Models\User;
use function Livewire\Volt\{state, layout, with, usesPagination};

layout('components.layouts.app');

usesPagination();
state(['search' => '']);

with(fn () => [
    'clients' => User::query()
        ->where('is_admin', false)
        ->withCount('projects')
        ->when($this->search, function($q) {
            $q->where('name', 'like', '%'.$this->search.'%')
              ->orWhere('email', 'like', '%'.$this->search.'%')
              ->orWhere('company_name', 'like', '%'.$this->search.'%');
        })
        ->latest()
        ->paginate(10)
]);

?>

<section class="w-full">
    <flux:main>
        <div class="flex max-md:flex-col items-center justify-between gap-4 mb-6">
            <div>
                <flux:heading size="xl" level="1">Client Directory</flux:heading>
                <flux:subheading class="mt-2">Manage your customer database.</flux:subheading>
            </div>
            
            <div class="flex gap-2 w-full md:w-auto">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search clients..." class="w-full md:w-64" />
                <flux:button href="{{ route('admin.clients.create') }}" variant="primary" icon="plus">Onboard Client</flux:button>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Name / Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Contact Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Active Projects</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($clients as $client)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $client->name }}</div>
                                    @if($client->company_name)
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $client->company_name }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-zinc-900 dark:text-zinc-100">{{ $client->email }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $client->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($client->projects_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-400/10 dark:text-blue-400">
                                            {{ $client->projects_count }} Projects
                                        </span>
                                    @else
                                        <span class="text-zinc-400 dark:text-zinc-500 text-xs">No projects</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <flux:button size="sm" icon="pencil-square" variant="ghost" href="{{ route('admin.clients.edit', $client->id) }}">Edit</flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                    No clients found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $clients->links() }}
        </div>
    </flux:main>
</section>