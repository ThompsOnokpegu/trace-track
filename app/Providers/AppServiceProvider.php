<?php

namespace App\Providers;
//1071 Specified key was too long; max key length is 1000 bytes
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //1071 Specified key was too long; max key length is 1000 bytes
        Schema::defaultStringLength(200);
    }
}
