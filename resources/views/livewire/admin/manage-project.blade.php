<?php

use function Livewire\Volt\{state, layout, rules, mount};
use App\Models\Project;
use App\Enums\ProjectStage;
use Illuminate\Support\Facades\Http;

layout('components.layouts.app');

state(['project', 'newStatus', 'note' => '', 'notify' => true]);

mount(function (Project $project) {
    $this->project = $project->load(['client', 'updates' => fn($q) => $q->latest()]);
    $this->newStatus = $project->current_status->value;
});

$stages = fn() => ProjectStage::cases();

$updateStatus = function () {
    $this->validate([
        'newStatus' => 'required|string', 
        'note' => 'nullable|string|max:500',
    ]);

    if ($this->project->current_status->value === $this->newStatus) {
        $this->dispatch('flux-toast', variant: 'warning', message: 'Status is already set to this stage.');
        return;
    }

    // 1. Update Project
    $this->project->update(['current_status' => $this->newStatus]);

    // 2. Create History Log
    $stageEnum = ProjectStage::from($this->newStatus);
    
    $update = $this->project->updates()->create([
        'status_key' => $stageEnum,
        'description' => $this->note ?: "Status updated to " . $stageEnum->label(),
        'notify_client' => $this->notify,
    ]);

    // 3. Fire Webhook to n8n (Only if notify is checked)
    if ($this->notify && env('N8N_WEBHOOK_URL')) {
        try {
            // We use 'dispatch' to the queue if possible, but for simplicity we do it inline with a short timeout
            // so the admin UI doesn't freeze if n8n is slow.
            Http::timeout(2)->post(env('N8N_WEBHOOK_URL'), [
                'type' => 'status_update',
                'client_name' => $this->project->client->name,
                'client_phone' => $this->project->client->phone, // Ensure this is +234 format in DB
                'client_email' => $this->project->client->email,
                'project_title' => $this->project->title,
                'new_status' => $stageEnum->label(),
                'status_color' => $stageEnum->color(),
                'description' => $update->description,
                'tracking_url' => route('track.public', $this->project->tracking_code),
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            // Log the error silently so the Admin doesn't see a crash
            logger()->error("n8n Webhook connection failed: " . $e->getMessage());
            
            // Optional: visual feedback
            $this->dispatch('flux-toast', variant: 'warning', message: 'Status updated, but notification failed to send.');
            $this->project->refresh();
            $this->note = ''; 
            return;
        }
    }

    // 4. Refresh Data & UI
    $this->project->refresh();
    $this->note = ''; 
    
    $this->dispatch('flux-toast', variant: 'success', message: 'Project status updated & Client notified!');
};

?>

<section class="w-full">
    <flux:main>
        
        <!-- Header -->
        <div class="flex max-md:flex-col items-start justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <flux:heading size="xl" level="1">{{ $project->title }}</flux:heading>
                    <code class="text-sm bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 rounded text-zinc-600 dark:text-zinc-400 font-mono">{{ $project->tracking_code }}</code>
                </div>
                <flux:subheading class="mt-1">
                    Client: <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $project->client->name }}</span> 
                    &bull; {{ $project->location }}
                </flux:subheading>
            </div>
            
            <flux:button href="{{ route('admin.projects.index') }}" icon="arrow-left">Back to List</flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- LEFT COLUMN: Update Controls (1/3 width) -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Status Card -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 shadow-sm">
                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
                        <flux:icon.arrow-path class="size-4" /> Update Progress
                    </h3>
                    
                    <form wire:submit="updateStatus" class="space-y-4">
                        
                        <!-- Status Selector -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">New Stage</label>
                            <div class="relative">
                                <select wire:model="newStatus" class="block w-full rounded-lg border-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 pl-3 pr-10 text-sm">
                                    @foreach($this->stages() as $stage)
                                        <option value="{{ $stage->value }}">{{ $stage->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Notes -->
                        <flux:textarea wire:model="note" label="Update Note (Optional)" placeholder="e.g. Container #MSC123 has cleared customs." />

                        <!-- Notify Toggle -->
                        <flux:checkbox wire:model="notify" label="Notify Client (Email/WhatsApp)" />

                        <flux:button type="submit" variant="primary" class="w-full">Update Status</flux:button>
                    </form>
                </div>

                <!-- Quick Details -->
                <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-5 border border-zinc-200 dark:border-zinc-700">
                    <h4 class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Project Info</h4>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-zinc-500 dark:text-zinc-400">Created:</span>
                            <span class="font-medium dark:text-zinc-200">{{ $project->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500 dark:text-zinc-400">Install Date:</span>
                            <span class="font-medium dark:text-zinc-200">{{ $project->installation_date ? $project->installation_date->format('M d, Y') : 'Not set' }}</span>
                        </div>
                        <div class="pt-2 border-t border-zinc-200 dark:border-zinc-700">
                            <span class="block text-zinc-500 dark:text-zinc-400 text-xs mb-1">Internal Notes:</span>
                            <p class="text-zinc-700 dark:text-zinc-300 italic">{{ $project->notes ?? 'No notes available.' }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: History Timeline (2/3 width) -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm min-h-[500px]">
                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-6">Activity History</h3>

                    <div class="relative pl-4">
                        <!-- Vertical Line -->
                        <div class="absolute top-0 bottom-0 left-[19px] w-0.5 bg-zinc-200 dark:bg-zinc-700"></div>

                        <div class="space-y-8">
                            @foreach($project->updates as $update)
                                <div class="relative flex gap-6 group">
                                    <!-- Dot -->
                                    <div class="absolute left-0 mt-1.5 size-7 rounded-full border-4 border-white dark:border-zinc-900 bg-{{ $update->status_key->color() }}-100 dark:bg-{{ $update->status_key->color() }}-900 flex items-center justify-center z-10">
                                        <div class="size-2 rounded-full bg-{{ $update->status_key->color() }}-500"></div>
                                    </div>

                                    <!-- Content -->
                                    <div class="ml-14 w-full">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline">
                                            <h4 class="font-bold text-zinc-900 dark:text-zinc-100 text-lg">
                                                {{ $update->status_key->label() }}
                                            </h4>
                                            <span class="text-xs text-zinc-500 dark:text-zinc-400 font-mono">
                                                {{ $update->created_at->format('M d, Y • h:i A') }}
                                            </span>
                                        </div>
                                        
                                        <div class="mt-2 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg border border-zinc-100 dark:border-zinc-700 text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $update->description }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </flux:main>
</section>