<?php

namespace App\Filament\Resources\Categorias\Tables;

use App\Models\Categoria;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('problemas_count')
                    ->label('Problemas')
                    ->counts('problemas')
                    ->badge(),
            ])
            ->defaultSort('nombre')
            ->recordActions([
                EditAction::make(),
                /** Una categoria con problemas no se puede borrar: la FK lo impide. */
                DeleteAction::make()
                    ->hidden(fn (Categoria $record): bool => $record->problemas()->exists()),
            ]);
    }
}
