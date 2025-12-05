<?php

use function Livewire\Volt\{state, layout, rules, save};
use App\Models\User;
use App\Models\Project;
use App\Enums\ProjectStage;

layout('components.layouts.app');

state([
    'title' => '',
    'location' => '',
    'user_id' => '', 
    'installation_date' => '',
    'notes' => '',
    'users' => fn() => User::all(), 
]);

rules([
    'title' => 'required|string|max:255',
    'location' => 'required|string|max:255',
    'user_id' => 'required|exists:users,id',
    'installation_date' => 'nullable|date',
    'notes' => 'nullable|string',
]);

$create = function () {
    $this->validate();

    $project = Project::create([
        'user_id' => $this->user_id,
        'title' => $this->title,
        'location' => $this->location,
        'installation_date' => $this->installation_date ?: null,
        'notes' => $this->notes,
        'current_status' => ProjectStage::SCHEDULED_VISIT,
    ]);

    $project->updates()->create([
        'status_key' => ProjectStage::SCHEDULED_VISIT,
        'description' => 'Project created. Initial site visit to be scheduled.',
        'notify_client' => false, 
    ]);

    session()->flash('status', 'Project created successfully!');
    $this->redirect(route('admin.projects.index'), navigate: true);
};

?>

<section class="w-full">
    <flux:main>
        <div class="max-w-2xl mx-auto" shadow="sm" rounded="lg" padding="6">
            <div class="mb-6">
                <flux:heading size="xl" level="1">Create New Project</flux:heading>
                <flux:subheading class="mt-2">Initiate a new elevator installation tracker.</flux:subheading>
            </div>
            
            <flux:separator class="mb-6" />

            <form wire:submit="create" class="space-y-6">
                
                <!-- Native Select styled to look like Flux -->
                <div>
                    <label class="block text-sm font-medium text-zinc-800 mb-1">Select Client</label>
                    <div class="relative">
                        <select wire:model="user_id" class="block w-full rounded-lg border-zinc-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 pl-3 pr-10 text-sm">
                            <option value="">-- Choose Customer --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->company_name ?? 'Individual' }})</option>
                            @endforeach
                        </select>
                    </div>
                    @error('user_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                    <div class="text-xs text-zinc-500 mt-1">
                        Can't find them? <a href="{{ route('admin.clients.create') }}" class="underline text-blue-600">Onboard new client</a>
                    </div>
                </div>

                <flux:input wire:model="title" label="Project Title" placeholder="e.g. Lekki Phase 1 - Duplex Lift" />

                <flux:input wire:model="location" label="Site Location" placeholder="e.g. 12 Admiralty Way, Lagos" />

                <flux:input wire:model="installation_date" type="date" label="Estimated Installation Date (Optional)" />

                <flux:textarea wire:model="notes" label="Internal Notes" placeholder="Any specific details for the technical team..." />

                <div class="flex justify-end gap-2 pt-4">
                    <flux:button href="{{ route('admin.projects.index') }}">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Create Project</flux:button>
                </div>
            </form>
        </div>
    </flux:main>
</section>