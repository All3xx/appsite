<?php

namespace App\Filament\Tenant\Resources\SiteResource\Pages;

use App\Filament\Tenant\Resources\SiteResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateSite extends CreateRecord
{
    protected static string $resource = SiteResource::class;
}
