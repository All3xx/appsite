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
        // Load ChatGPT entrypoints without touching routes/web.php.
        $chatgptRoutes = base_path('routes/chatgpt_entrypoints.php');

        if (is_file($chatgptRoutes)) {
            Route::middleware('web')->group($chatgptRoutes);
        }
    }
}
