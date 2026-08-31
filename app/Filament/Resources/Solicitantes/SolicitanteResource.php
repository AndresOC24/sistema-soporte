<?php

namespace App\Filament\Resources\Solicitantes;

use App\Filament\Resources\Solicitantes\Pages\ManageSolicitantes;
use App\Models\Solicitante;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SolicitanteResource extends Resource
{
    protected static ?string $model = Solicitante::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string|UnitEnum|null $navigationGroup = 'Catálogos';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?string $modelLabel = 'solicitante';

    protected static ?string $pluralModelLabel = 'solicitantes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('soportes_count')
                    ->label('Soportes pedidos')
                    ->counts('soportes')
                    ->badge()
                    ->sortable(),
            ])
            ->defaultSort('nombre')
            ->recordActions([
                EditAction::make(),
                /** Un solicitante con soportes no se puede borrar: la FK lo impide. */
                DeleteAction::make()
                    ->hidden(fn (Solicitante $record): bool => $record->soportes()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSolicitantes::route('/'),
        ];
    }
}
