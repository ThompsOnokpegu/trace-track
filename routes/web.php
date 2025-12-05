<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    Volt::route('/projects', 'admin.project-list')->name('projects.index');
    
    // Create a new Project
    Volt::route('/projects/create', 'admin.create-project')->name('projects.create');
    // Project Management (The "Tracker" Control Center)
    Volt::route('/projects/{project}/manage', 'admin.manage-project')->name('projects.manage');

    // Client Management
    Volt::route('/clients', 'admin.client-list')->name('clients.index');
    Volt::route('/clients/create', 'admin.create-client')->name('clients.create');
});
