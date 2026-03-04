<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\SiteResource\Pages;
use App\Models\Site;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Sites';
    protected static ?string $navigationLabel = 'Sites';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->required()->maxLength(150),
            Forms\Components\TextInput::make('address')->nullable()->maxLength(255),
            Forms\Components\TextInput::make('lat')->numeric()->nullable(),
            Forms\Components\TextInput::make('lng')->numeric()->nullable(),
            Forms\Components\TextInput::make('radius_m')->numeric()->nullable(),
            Forms\Components\Toggle::make('active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\IconColumn::make('active')->boolean(),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}
