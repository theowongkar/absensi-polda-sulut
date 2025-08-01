<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // Gate untuk kelola pegawai
        Gate::define('manage-employee', function ($user) {
            return $user->role === 'Admin';
        });

        Gate::define('manage-attendance', function ($user) {
            return $user->role === 'Admin';
        });
    }
}
