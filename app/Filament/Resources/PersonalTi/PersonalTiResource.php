<?php

namespace App\Filament\Resources\PersonalTi;

use App\Enums\RolPersonalTi;
use App\Filament\Resources\PersonalTi\Pages\ManagePersonalTi;
use App\Models\PersonalTi;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class PersonalTiResource extends Resource
{
    protected static ?string $model = PersonalTi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Catálogos';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'personal-ti';

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?string $modelLabel = 'persona de TI';

    protected static ?string $pluralModelLabel = 'personal de TI';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('rol')
                    ->label('Rol')
                    ->options(RolPersonalTi::class)
                    ->default(RolPersonalTi::Becario)
                    ->required(),
                Toggle::make('activo')
                    ->label('Activo este semestre')
                    ->helperText('Desactiva a quien ya no da soporte en vez de borrarlo, para conservar su historial.')
                    ->default(true),
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
                TextColumn::make('rol')
                    ->label('Rol')
                    ->badge(),
                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
                TextColumn::make('soportes_count')
                    ->label('Soportes atendidos')
                    ->counts('soportes')
                    ->badge()
                    ->sortable(),
            ])
            ->defaultSort('nombre')
            ->filters([
                TernaryFilter::make('activo')
                    ->label('Activo')
                    ->default(true),
            ])
            ->recordActions([
                EditAction::make(),
                /** Quien ya atendio soportes no se borra: se desactiva. */
                DeleteAction::make()
                    ->hidden(fn (PersonalTi $record): bool => $record->soportes()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePersonalTi::route('/'),
        ];
    }
}
