<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\Section;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('create-section', function ($user) {
            return $user->role == UserRole::AUTHOR;
        });
    }
}
