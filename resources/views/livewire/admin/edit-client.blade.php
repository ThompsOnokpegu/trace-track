<?php

use function Livewire\Volt\{state, layout, rules, mount};
use App\Models\User;
use Illuminate\Validation\Rule;

layout('components.layouts.app');

state([
    'user' => null,
    'name' => '',
    'email' => '',
    'phone' => '', 
    'company_name' => '',
    'address' => '',
]);

mount(function (User $user) {
    $this->user = $user;
    $this->name = $user->name;
    $this->email = $user->email;
    $this->phone = $user->phone;
    $this->company_name = $user->company_name;
    $this->address = $user->address;
});

// Dynamic rules to ignore current user's email during update
$save = function () {
    $this->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
        'phone' => 'required|string|max:20', 
        'company_name' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:500',
    ]);

    $this->user->update([
        'name' => $this->name,
        'email' => $this->email,
        'phone' => $this->phone,
        'company_name' => $this->company_name,
        'address' => $this->address,
    ]);

    session()->flash('status', 'Client details updated successfully!');
    $this->redirect(route('admin.clients.index'), navigate: true);
};

$delete = function () {
    // Optional: Prevent deletion if they have active projects
    if ($this->user->projects()->exists()) {
        $this->dispatch('flux-toast', variant: 'danger', message: 'Cannot delete client with existing projects. Delete projects first.');
        return;
    }

    $this->user->delete();
    session()->flash('status', 'Client deleted.');
    $this->redirect(route('admin.clients.index'), navigate: true);
};

?>

<section class="w-full">
    <flux:main>
        <div class="max-w-2xl">
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <flux:heading size="xl" level="1">Edit Client</flux:heading>
                    <flux:subheading class="mt-2">Update contact information.</flux:subheading>
                </div>
                
                <flux:button wire:confirm="Are you sure? This action cannot be undone." wire:click="delete" variant="danger" size="sm" icon="trash">Delete Client</flux:button>
            </div>
            
            <flux:separator class="mb-6" />

            <form wire:submit="save" class="my-6 w-full space-y-6">
                
                <flux:input wire:model="name" label="Client Name / Contact Person" />

                <flux:input wire:model="company_name" label="Company Name (Optional)" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="email" type="email" label="Email Address" />
                    <flux:input wire:model="phone" label="WhatsApp Number" />
                </div>

                <flux:textarea wire:model="address" label="Billing/Office Address" />

                <div class="flex justify-end gap-2 pt-4">
                    <flux:button href="{{ route('admin.clients.index') }}">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save Changes</flux:button>
                </div>
            </form>  
        </div>
    </flux:main>
</section>