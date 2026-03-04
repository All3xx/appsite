<?php

namespace App\Filament\Tenant\Resources\TimeEntryResource\Pages;

use App\Filament\Tenant\Resources\TimeEntryResource;
use Filament\Resources\Pages\ListRecords;

final class ListTimeEntries extends ListRecords
{
    protected static string $resource = TimeEntryResource::class;
}
