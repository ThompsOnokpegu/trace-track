<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Admin Routes (Protected)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // The Main Dashboard: List all projects
    Volt::route('/dashboard', 'admin.dashboard')->name('dashboard');
    Volt::route('/projects', 'admin.project-list')->name('projects.index');
    
    // Create a new Project
    Volt::route('/projects/create', 'admin.create-project')->name('projects.create');

    // Client Management
    Volt::route('/clients', 'admin.client-list')->name('clients.index');
    Volt::route('/clients/create', 'admin.create-client')->name('clients.create');
    Volt::route('/clients/{user}/edit', 'admin.edit-client')->name('clients.edit');


    // Project Management (The "Tracker" Control Center)
    Volt::route('/projects/{project}/manage', 'admin.manage-project')->name('projects.manage');
    Volt::route('/projects/{project}/edit', 'admin.edit-project')->name('projects.edit');
});

// 1. Public Tracker (No Login Required)
Volt::route('/track/{code}', 'client.public-tracker')->name('track.public');
// Public Search Route (Add this before the specific track route)
Volt::route('/track', 'client.public-search')->name('track.search');

// 2. Client Dashboard (For clients who do log in)
Route::middleware(['auth', 'verified'])->prefix('client')->name('client.')->group(function () {
    Volt::route('/my-projects', 'client.my-projects')->name('projects.index');
});