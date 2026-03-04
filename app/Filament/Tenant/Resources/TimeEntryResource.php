<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\TimeEntryResource\Pages;
use App\Models\Employee;
use App\Models\Site;
use App\Models\TimeEntry;
use App\Services\TimesheetService;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class TimeEntryResource extends Resource
{
    protected static ?string $model = TimeEntry::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Timesheet';
    protected static ?string $navigationLabel = 'Time Entries';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('employee_id')
                ->label('Employee')
                ->options(fn () => Employee::query()->orderBy('last_name')->get()->mapWithKeys(fn ($e) => [$e->id => $e->fullName()])->all())
                ->required(),
            Forms\Components\Select::make('site_id')
                ->label('Site')
                ->options(fn () => Site::query()->orderBy('name')->pluck('name', 'id')->all())
                ->required(),
            Forms\Components\DatePicker::make('work_date')->required(),
            Forms\Components\TextInput::make('work_minutes')->numeric()->required()->helperText('Minutes, will be rounded to 10'),
            Forms\Components\TextInput::make('break_minutes')->numeric()->default(0)->helperText('Minutes, will be rounded to 10'),
            Forms\Components\Textarea::make('notes')->nullable(),
            Forms\Components\TextInput::make('edit_reason')
                ->label('Reason (required)')
                ->required()
                ->dehydrated(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('work_date')->date()->sortable(),
            Tables\Columns\TextColumn::make('employee.last_name')->label('Last name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('employee.first_name')->label('First name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('site.name')->label('Site')->sortable(),
            Tables\Columns\TextColumn::make('work_minutes')->label('Work (min)')->sortable(),
        ])->actions([
            Tables\Actions\EditAction::make()
                ->using(function (TimeEntry $record, array $data) {
                    $reason = (string) ($data['edit_reason'] ?? '');
                    unset($data['edit_reason']);

                    return app(TimesheetService::class)->upsertTimeEntry($data, auth()->id(), $reason);
                }),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeEntries::route('/'),
            'create' => Pages\CreateTimeEntry::route('/create'),
            'edit' => Pages\EditTimeEntry::route('/{record}/edit'),
        ];
    }
}
