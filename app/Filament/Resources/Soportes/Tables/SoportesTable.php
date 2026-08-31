<?php

namespace App\Filament\Resources\Soportes\Tables;

use App\Models\Soporte;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SoportesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            /**
             * Casi todas las columnas son opcionales: se prenden y apagan desde
             * el boton "Columnas" y la eleccion queda guardada por usuario.
             * Fecha y solicitante quedan fijas porque sin ellas la tabla no se lee.
             */
            ->columns([
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('hora_inicio')
                    ->label('Inicio')
                    ->formatStateUsing(fn (string $state): string => substr($state, 0, 5))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('hora_fin')
                    ->label('Fin')
                    ->formatStateUsing(fn (string $state): string => substr($state, 0, 5))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('duracion_minutos')
                    ->label('Duración')
                    ->formatStateUsing(fn (int $state): string => Soporte::formatearMinutos($state))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('solicitante.nombre')
                    ->label('Solicitante')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ubicacion.nombre')
                    ->label('Ubicación')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('ubicacion.tipo')
                    ->label('Tipo de ubicación')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('categorias.nombre')
                    ->label('Categorías')
                    ->badge()
                    ->color('warning')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('problemas.nombre')
                    ->label('Problemas')
                    ->badge()
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->toggleable(),
                TextColumn::make('personalTi.nombre')
                    ->label('Atendió')
                    ->badge()
                    ->color('gray')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->toggleable(),
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->wrap()
                    ->limit(80)
                    ->placeholder('Sin descripción')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('fecha', 'desc')
            ->filters([
                Filter::make('rango_fechas')
                    ->schema([
                        DatePicker::make('desde')->label('Desde'),
                        DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['desde'] ?? null, fn (Builder $q, string $desde): Builder => $q->whereDate('fecha', '>=', $desde))
                        ->when($data['hasta'] ?? null, fn (Builder $q, string $hasta): Builder => $q->whereDate('fecha', '<=', $hasta))),
                SelectFilter::make('ubicacion')
                    ->label('Ubicación')
                    ->relationship('ubicacion', 'nombre')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('solicitante')
                    ->label('Solicitante')
                    ->relationship('solicitante', 'nombre')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('personalTi')
                    ->label('Atendió')
                    ->relationship('personalTi', 'nombre')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('problemas')
                    ->label('Problema')
                    ->relationship('problemas', 'nombre')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
