<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| ChatGPT entrypoints for Filament tenant panel
|--------------------------------------------------------------------------
|
| These routes provide safe entry points:
| - /admin       -> /admin/login
| - /admin/login -> /{firstTenant}/admin/login
|
| They run WITHOUT extra middleware to avoid 403 caused by restrictive 'web'
| or custom middleware added to route groups.
|
*/

Route::middleware([])->get('/chatgpt/ping', function () {
    return response('ok', 200);
})->name('chatgpt.ping');

Route::middleware([])->get('/admin', function () {
    return redirect()->to('/admin/login');
})->name('chatgpt.admin.entry');

Route::middleware([])->get('/admin/login', function () {
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

        $tenantKey = null;

        if (Schema::hasColumn('tenants', 'slug') && !empty($tenant->slug)) {
            $tenantKey = $tenant->slug;
        }

        if (!$tenantKey && isset($tenant->id) && !empty($tenant->id)) {
            $tenantKey = $tenant->id;
        }

        if (!$tenantKey && Schema::hasColumn('tenants', 'name') && !empty($tenant->name)) {
            $tenantKey = $tenant->name;
        }

        if (!$tenantKey) {
            return response($noTenantMsg, 404);
        }

        return redirect()->to('/' . $tenantKey . '/admin/login');
    } catch (Throwable $e) {
        return response($noTenantMsg, 404);
    }
})->name('chatgpt.admin.login');
