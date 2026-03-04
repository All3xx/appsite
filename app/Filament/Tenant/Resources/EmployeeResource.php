<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Employees';
    protected static ?string $navigationLabel = 'Employees';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('employee_code')->required()->maxLength(32)
                ->helperText('Unique ID inside tenant. Example: M-000123'),
            Forms\Components\TextInput::make('first_name')->required()->maxLength(80),
            Forms\Components\TextInput::make('last_name')->required()->maxLength(80),
            Forms\Components\TextInput::make('phone')->required()->maxLength(32),
            Forms\Components\TextInput::make('email')->email()->nullable()->maxLength(190),
            Forms\Components\TextInput::make('cnp')->nullable()->maxLength(32),
            Forms\Components\DatePicker::make('hire_date')->nullable(),
            Forms\Components\Toggle::make('active')->default(true),
            Forms\Components\TextInput::make('hourly_rate')->numeric()->required()
                ->helperText('EUR per hour (current). Changes should be done via rate history later.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('employee_code')->searchable(),
            Tables\Columns\TextColumn::make('last_name')->searchable(),
            Tables\Columns\TextColumn::make('first_name')->searchable(),
            Tables\Columns\TextColumn::make('phone')->toggleable(),
            Tables\Columns\IconColumn::make('active')->boolean(),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
