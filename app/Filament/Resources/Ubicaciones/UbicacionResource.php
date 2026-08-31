<?php

namespace App\Filament\Resources\Ubicaciones;

use App\Enums\TipoUbicacion;
use App\Filament\Resources\Ubicaciones\Pages\ManageUbicaciones;
use App\Models\Ubicacion;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class UbicacionResource extends Resource
{
    protected static ?string $model = Ubicacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Catálogos';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'ubicaciones';

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?string $modelLabel = 'ubicación';

    protected static ?string $pluralModelLabel = 'ubicaciones';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->placeholder('Aula 409, Postgrado, Biblioteca...')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('tipo')
                    ->label('Tipo')
                    ->options(TipoUbicacion::class)
                    ->default(TipoUbicacion::Aula)
                    ->required(),
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
                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge(),
                TextColumn::make('soportes_count')
                    ->label('Soportes')
                    ->counts('soportes')
                    ->badge(),
            ])
            ->defaultSort('nombre')
            ->filters([
                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options(TipoUbicacion::class),
            ])
            ->recordActions([
                EditAction::make(),
                /** Una ubicacion con soportes no se puede borrar: la FK lo impide. */
                DeleteAction::make()
                    ->hidden(fn (Ubicacion $record): bool => $record->soportes()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUbicaciones::route('/'),
        ];
    }
}
