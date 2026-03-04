<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\RoleResource\Pages;
use App\Models\Permission;
use App\Models\Role;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Access';
    protected static ?string $navigationLabel = 'Roles';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->required()->maxLength(100),
            Forms\Components\Toggle::make('is_system')->disabled()->helperText('System roles cannot be deleted.'),
            Forms\Components\TextInput::make('priority')->numeric()->default(10),
            Forms\Components\CheckboxList::make('permissions')
                ->label('Permissions')
                ->relationship('permissions', 'name')
                ->options(Permission::query()->where('active', true)->orderBy('group')->orderBy('name')->pluck('name', 'id')->all())
                ->columns(2)
                ->searchable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\IconColumn::make('is_system')->boolean(),
            Tables\Columns\TextColumn::make('priority')->sortable(),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
