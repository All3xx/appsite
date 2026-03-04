<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
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
        // ChatGPT entrypoints for Filament tenant panel.
        // IMPORTANT: do NOT attach the 'web' middleware group here.
        // Some projects accidentally add restrictive middleware to 'web', which can cause 403 on /admin/login.
        // These routes only redirect, so they can run with no extra middleware.

        $chatgptRoutes = base_path('routes/chatgpt_entrypoints.php');

        if (is_file($chatgptRoutes)) {
            Route::group([], $chatgptRoutes);
        }
    }
}
