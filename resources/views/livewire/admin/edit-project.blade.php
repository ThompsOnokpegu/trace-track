<?php

use function Livewire\Volt\{state, layout, rules, mount};
use App\Models\User;
use App\Models\Project;

layout('components.layouts.app');

state([
    'project' => null,
    'title' => '',
    'location' => '',
    'user_id' => '', 
    'installation_date' => '',
    'notes' => '',
    'users' => fn() => User::all(), 
]);

mount(function (Project $project) {
    $this->project = $project;
    $this->title = $project->title;
    $this->location = $project->location;
    $this->user_id = $project->user_id;
    $this->installation_date = $project->installation_date?->format('Y-m-d');
    $this->notes = $project->notes;
});

rules([
    'title' => 'required|string|max:255',
    'location' => 'required|string|max:255',
    'user_id' => 'required|exists:users,id',
    'installation_date' => 'nullable|date',
    'notes' => 'nullable|string',
]);

$save = function () {
    $this->validate();

    $this->project->update([
        'user_id' => $this->user_id,
        'title' => $this->title,
        'location' => $this->location,
        'installation_date' => $this->installation_date ?: null,
        'notes' => $this->notes,
    ]);

    session()->flash('status', 'Project details updated successfully!');
    $this->redirect(route('admin.projects.index'), navigate: true);
};

// Separate action to delete project (optional but useful)
$delete = function () {
    $this->project->delete();
    session()->flash('status', 'Project deleted.');
    $this->redirect(route('admin.projects.index'), navigate: true);
};

?>

<section class="w-full">
    <flux:main>
        <div class="max-w-2xl">
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <flux:heading size="xl" level="1">Edit Project</flux:heading>
                    <flux:subheading class="mt-2">Update project details or client assignment.</flux:subheading>
                </div>
                
                <!-- Danger Zone -->
                <flux:button wire:confirm="Are you sure you want to delete this project?" wire:click="delete" variant="danger" size="sm" icon="trash">Delete</flux:button>
            </div>
            
            <flux:separator class="mb-6" />

            <form wire:submit="save" class="space-y-6">
                
                <!-- Native Select -->
                <div>
                    <label class="block text-sm font-medium text-zinc-800 dark:text-zinc-200 mb-1">Assigned Client</label>
                    <div class="relative border-1 border-zinc-700 rounded-lg">
                        <select wire:model="user_id" class="block w-full rounded-lg border-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 pl-3 pr-10 text-sm">
                            <option value="">-- Choose Customer --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('user_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <flux:input wire:model="title" label="Project Title" />

                <flux:input wire:model="location" label="Site Location" />

                <flux:input wire:model="installation_date" type="date" label="Estimated Installation Date" />

                <flux:textarea wire:model="notes" label="Internal Notes" />

                <div class="flex justify-end gap-2 pt-4">
                    <flux:button href="{{ route('admin.projects.index') }}">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save Changes</flux:button>
                </div>
            </form>
        </div>
    </flux:main>
</section>
