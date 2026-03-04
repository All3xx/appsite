<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserCanAccessTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $tenantId = app(TenantContext::class)->tenantId();

        if (!$user || !$tenantId) {
            abort(403);
        }

        if ($user->is_master) {
            return $next($request);
        }

        if ((int) $user->tenant_id !== (int) $tenantId) {
            abort(403);
        }

        return $next($request);
    }
}
