<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Middleware\EnsureUserIsAdmin;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Settings Routes (Standard Profile management)
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

// Admin Routes (Protected Area)
// Added EnsureUserIsAdmin middleware to protect these routes
Route::middleware(['auth', 'verified', EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Dashboard (Moved Here)
    // The full route name is now 'admin.dashboard'
    Volt::route('/dashboard', 'admin.dashboard')->name('dashboard');
    
    // 2. Project Management
    Volt::route('/projects', 'admin.project-list')->name('projects.index');
    Volt::route('/projects/create', 'admin.create-project')->name('projects.create');
    Volt::route('/projects/{project}/edit', 'admin.edit-project')->name('projects.edit');
    Volt::route('/projects/{project}/manage', 'admin.manage-project')->name('projects.manage');

    // 3. Client Management
    Volt::route('/clients', 'admin.client-list')->name('clients.index');
    Volt::route('/clients/create', 'admin.create-client')->name('clients.create');
    Volt::route('/clients/{user}/edit', 'admin.edit-client')->name('clients.edit');
});

// Public Routes (No Login)
Volt::route('/track', 'public-search')->name('track.search');
Volt::route('/track/{code}', 'public-tracker')->name('track.public');