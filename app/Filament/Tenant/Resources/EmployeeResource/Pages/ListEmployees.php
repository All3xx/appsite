<?php

namespace App\Filament\Tenant\Resources\EmployeeResource\Pages;

use App\Filament\Tenant\Resources\EmployeeResource;
use Filament\Resources\Pages\ListRecords;

final class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
}
