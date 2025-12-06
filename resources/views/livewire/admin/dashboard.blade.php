<?php

use function Livewire\Volt\{state, layout, mount};
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Enums\ProjectStage;

layout('components.layouts.app');

state(['stats', 'recentUpdates', 'upcomingInstallations']);

mount(function () {
    // 1. Calculate Stats
    $this->stats = [
        'total_active' => Project::where('current_status', '!=', ProjectStage::HANDOVER)->count(),
        'in_production' => Project::whereIn('current_status', [ProjectStage::PRODUCTION, ProjectStage::PRODUCTION_COMPLETED])->count(),
        'shipping' => Project::whereIn('current_status', [ProjectStage::SHIPPED, ProjectStage::ARRIVED_NIGERIA, ProjectStage::CUSTOMS_CLEARANCE])->count(),
        'installing' => Project::whereIn('current_status', [ProjectStage::INSTALLATION_IN_PROGRESS, ProjectStage::INSTALLATION_SCHEDULED])->count(),
    ];

    // 2. Get Recent Updates across all projects
    $this->recentUpdates = ProjectUpdate::with('project')
        ->latest()
        ->take(5)
        ->get();

    // 3. Upcoming Installations (Next 14 days)
    $this->upcomingInstallations = Project::whereNotNull('installation_date')
        ->where('installation_date', '>=', now())
        ->where('installation_date', '<=', now()->addDays(14))
        ->orderBy('installation_date')
        ->take(3)
        ->get();
});

?>

<section class="w-full">
    <flux:main>
        <div class="mb-8">
            <flux:heading size="xl" level="1">Dashboard</flux:heading>
            <flux:subheading class="mt-2">Overview of operations and project status.</flux:subheading>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Projects -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 dark:text-blue-400">
                        <flux:icon.cube class="size-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Active Projects</p>
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['total_active'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- In Production -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg text-amber-600 dark:text-amber-400">
                        <flux:icon.cog class="size-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Production</p>
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['in_production'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Logistics/Shipping -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg text-indigo-600 dark:text-indigo-400">
                        <flux:icon.truck class="size-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Logistics & Customs</p>
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['shipping'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Installation -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg text-emerald-600 dark:text-emerald-400">
                        <flux:icon.wrench-screwdriver class="size-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Installation Phase</p>
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['installing'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Recent Activity Feed (2/3) -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
                        <h3 class="font-semibold text-zinc-900 dark:text-white">Recent Updates</h3>
                        <flux:button href="{{ route('admin.projects.index') }}" size="sm" variant="subtle">View All</flux:button>
                    </div>
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($recentUpdates as $update)
                            <div class="p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex gap-4">
                                        <div class="mt-1 size-2 rounded-full bg-{{ $update->status_key->color() }}-500"></div>
                                        <div>
                                            <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                                {{ $update->project->title }}
                                            </p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                                                Moved to <span class="font-semibold">{{ $update->status_key->label() }}</span>
                                            </p>
                                            <p class="text-sm text-zinc-600 dark:text-zinc-300 mt-2">
                                                "{{ $update->description }}"
                                            </p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-zinc-400 whitespace-nowrap">{{ $update->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-zinc-500">No recent activity found.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Upcoming Installations (1/3) -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden shadow-sm h-full">
                    <div class="p-5 border-b border-zinc-200 dark:border-zinc-700">
                        <h3 class="font-semibold text-zinc-900 dark:text-white">Upcoming Installations</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        @forelse($upcomingInstallations as $project)
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700">
                                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md p-2 text-center min-w-[3.5rem]">
                                    <span class="block text-xs text-zinc-500 uppercase">{{ $project->installation_date->format('M') }}</span>
                                    <span class="block text-lg font-bold text-zinc-900 dark:text-white">{{ $project->installation_date->format('d') }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white line-clamp-1">{{ $project->title }}</p>
                                    <p class="text-xs text-zinc-500 mt-1">{{ $project->location }}</p>
                                    <a href="{{ route('admin.projects.manage', $project) }}" class="text-xs text-blue-600 hover:underline mt-1 block">View Project &rarr;</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500 text-center py-4">No installations scheduled for the next 14 days.</p>
                        @endforelse

                        <flux:button href="{{ route('admin.projects.create') }}" variant="primary" class="w-full mt-4" icon="plus">Schedule New</flux:button>
                    </div>
                </div>
            </div>

        </div>
    </flux:main>
</section>
