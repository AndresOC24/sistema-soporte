<?php

namespace App\Filament\Resources\Soportes;

use App\Filament\Resources\Soportes\Pages\CreateSoporte;
use App\Filament\Resources\Soportes\Pages\EditSoporte;
use App\Filament\Resources\Soportes\Pages\ListSoportes;
use App\Filament\Resources\Soportes\Pages\ViewSoporte;
use App\Filament\Resources\Soportes\Schemas\SoporteForm;
use App\Filament\Resources\Soportes\Schemas\SoporteInfolist;
use App\Filament\Resources\Soportes\Tables\SoportesTable;
use App\Models\Soporte;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SoporteResource extends Resource
{
    protected static ?string $model = Soporte::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLifebuoy;

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'soporte';

    protected static ?string $pluralModelLabel = 'soportes';

    public static function form(Schema $schema): Schema
    {
        return SoporteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SoporteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SoportesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSoportes::route('/'),
            'create' => CreateSoporte::route('/create'),
            'view' => ViewSoporte::route('/{record}'),
            'edit' => EditSoporte::route('/{record}/edit'),
        ];
    }
}
