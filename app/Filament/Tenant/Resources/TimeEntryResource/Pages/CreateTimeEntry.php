<?php

namespace App\Filament\Tenant\Resources\TimeEntryResource\Pages;

use App\Filament\Tenant\Resources\TimeEntryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateTimeEntry extends CreateRecord
{
    protected static string $resource = TimeEntryResource::class;
}
