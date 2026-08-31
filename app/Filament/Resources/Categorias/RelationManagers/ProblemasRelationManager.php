<?php

namespace App\Filament\Resources\Categorias\RelationManagers;

use App\Models\Problema;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProblemasRelationManager extends RelationManager
{
    protected static string $relationship = 'problemas';

    protected static ?string $title = 'Problemas';

    protected static ?string $modelLabel = 'problema';

    protected static ?string $pluralModelLabel = 'problemas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('soportes_count')
                    ->label('Veces atendido')
                    ->counts('soportes')
                    ->badge(),
            ])
            ->defaultSort('nombre')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                /** Un problema ya usado en un soporte no se puede borrar: la FK lo impide. */
                DeleteAction::make()
                    ->hidden(fn (Problema $record): bool => $record->soportes()->exists()),
            ]);
    }
}
