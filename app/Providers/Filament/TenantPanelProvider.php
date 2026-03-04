<?php

namespace App\Providers\Filament;

use App\Filament\Auth\UsernameLogin;
use App\Http\Middleware\EnsureUserCanAccessTenant;
use App\Http\Middleware\SetTenantFromRoute;
use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class TenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tenant')
            ->path('{tenant}/admin')
            ->login(UsernameLogin::class)
            ->authGuard('web')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,

                SetTenantFromRoute::class,
                EnsureUserCanAccessTenant::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->resources([
                \App\Filament\Tenant\Resources\RoleResource::class,
                \App\Filament\Tenant\Resources\UserResource::class,
                \App\Filament\Tenant\Resources\EmployeeResource::class,
                \App\Filament\Tenant\Resources\SiteResource::class,
                \App\Filament\Tenant\Resources\TimeEntryResource::class,
            ]);
    }
}
