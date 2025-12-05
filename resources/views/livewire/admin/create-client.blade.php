<?php

use function Livewire\Volt\{state, layout, rules};
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

layout('components.layouts.app');

state([
    'name' => '',
    'email' => '',
    'phone' => '', 
    'company_name' => '',
    'address' => '',
]);

rules([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'phone' => 'required|string|max:20', 
    'company_name' => 'nullable|string|max:255',
    'address' => 'nullable|string|max:500',
]);

$save = function () {
    $this->validate();

    User::create([
        'name' => $this->name,
        'email' => $this->email,
        'phone' => $this->phone,
        'company_name' => $this->company_name,
        'address' => $this->address,
        'password' => Hash::make(Str::random(16)), 
        'is_admin' => false,
    ]);

    session()->flash('status', 'Client onboarded successfully!');
    $this->redirect(route('admin.clients.index'), navigate: true);
};

?>

<section class="w-full">
    <flux:main>
        <div class="mx-auto max-w-2xl w-full">
            <div class="mb-6">
                <flux:heading size="xl" level="1">Onboard New Client</flux:heading>
                <flux:subheading class="mt-2">Add customer details before assigning a project.</flux:subheading>
            </div>
            
            <flux:separator class="mb-6" />

            <form wire:submit="save" class="my-6 w-full space-y-6">
                
                <flux:input wire:model="name" label="Client Name / Contact Person" />

                <flux:input wire:model="company_name" label="Company Name (Optional)" placeholder="e.g. Dangote Refinery" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="email" type="email" label="Email Address" />
                    
                    <flux:input wire:model="phone" label="WhatsApp Number" placeholder="e.g. +234..." />
                </div>

                <flux:textarea wire:model="address" label="Billing/Office Address" />

                <div class="flex justify-end gap-2 pt-4">
                    <flux:button href="{{ route('admin.clients.index') }}">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Onboard Client</flux:button>
                </div>
            </form>  
        </div>
    </flux:main>
</section>