<?php

namespace App\Filament\Master\Resources\PermissionResource\Pages;

use App\Filament\Master\Resources\PermissionResource;
use Filament\Resources\Pages\ListRecords;

final class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;
}
