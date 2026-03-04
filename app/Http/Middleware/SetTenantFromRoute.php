<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetTenantFromRoute
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = (string) ($request->route('tenant') ?? '');
        if ($slug === '') {
            abort(404);
        }

        $tenant = Tenant::query()->where('slug', $slug)->first();
        if (!$tenant) {
            abort(404);
        }

        app(TenantContext::class)->set($tenant);

        return $next($request);
    }
}
