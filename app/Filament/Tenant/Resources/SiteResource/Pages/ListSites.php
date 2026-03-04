<?php

namespace App\Filament\Tenant\Resources\SiteResource\Pages;

use App\Filament\Tenant\Resources\SiteResource;
use Filament\Resources\Pages\ListRecords;

final class ListSites extends ListRecords
{
    protected static string $resource = SiteResource::class;
}
