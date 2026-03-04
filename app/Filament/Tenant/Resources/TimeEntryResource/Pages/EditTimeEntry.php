<?php

namespace App\Filament\Tenant\Resources\TimeEntryResource\Pages;

use App\Filament\Tenant\Resources\TimeEntryResource;
use Filament\Resources\Pages\EditRecord;

final class EditTimeEntry extends EditRecord
{
    protected static string $resource = TimeEntryResource::class;
}
