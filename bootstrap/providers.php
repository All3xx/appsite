<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,

    App\Providers\AppSantierServiceProvider::class,

    App\Providers\Filament\MasterPanelProvider::class,
    App\Providers\Filament\TenantPanelProvider::class,
];