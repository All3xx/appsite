<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| ChatGPT entrypoints for Filament tenant panel
|--------------------------------------------------------------------------
|
| Problem fixed:
| Filament tenant panel uses routes like: /{tenant}/admin/login
| When user opens /admin (without tenant), Filament may try to generate
| the tenant login URL without the {tenant} parameter and throw 500.
|
| These routes provide a simple, safe entry point:
| - /admin       -> /admin/login
| - /admin/login -> /{firstTenant}/admin/login
|
*/

Route::get('/admin', function () {
    return redirect()->to('/admin/login');
})->name('chatgpt.admin.entry');

Route::get('/admin/login', function () {
    // Default message for empty DB or missing model.
    $noTenantMsg = 'No tenant found. Create one in DB table tenants, then open /{tenant}/admin/login or /admin/login.';

    try {
        if (!class_exists(\App\Models\Tenant::class)) {
            return response($noTenantMsg, 404);
        }

        if (!Schema::hasTable('tenants')) {
            return response($noTenantMsg, 404);
        }

        $tenantClass = \App\Models\Tenant::class;
        $tenant = $tenantClass::query()->first();

        if (!$tenant) {
            return response($noTenantMsg, 404);
        }

        // Pick a stable route key.
        $tenantKey = null;

        // Prefer slug if it exists.
        if (Schema::hasColumn('tenants', 'slug') && !empty($tenant->slug)) {
            $tenantKey = $tenant->slug;
        }

        // Fallback to id.
        if (!$tenantKey && isset($tenant->id) && !empty($tenant->id)) {
            $tenantKey = $tenant->id;
        }

        // Final fallback.
        if (!$tenantKey && Schema::hasColumn('tenants', 'name') && !empty($tenant->name)) {
            $tenantKey = $tenant->name;
        }

        if (!$tenantKey) {
            return response($noTenantMsg, 404);
        }

        return redirect()->to('/' . $tenantKey . '/admin/login');
    } catch (Throwable $e) {
        // Do not leak exception details.
        return response($noTenantMsg, 404);
    }
})->name('chatgpt.admin.login');
