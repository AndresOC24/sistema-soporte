<?php

namespace App\Filament\Resources\Soportes\Schemas;

use App\Models\Soporte;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SoporteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('fecha')
                            ->label('Fecha')
                            ->date('d/m/Y'),
                        TextEntry::make('hora_inicio')
                            ->label('Horario')
                            ->formatStateUsing(fn (string $state, Soporte $record): string => substr($state, 0, 5).' — '.substr($record->hora_fin, 0, 5)),
                        TextEntry::make('duracion_minutos')
                            ->label('Duración')
                            ->formatStateUsing(fn (int $state): string => Soporte::formatearMinutos($state)),
                        TextEntry::make('solicitante.nombre')
                            ->label('Solicitante'),
                        TextEntry::make('ubicacion.nombre')
                            ->label('Ubicación'),
                        TextEntry::make('personalTi.nombre')
                            ->label('Atendió')
                            ->badge()
                            ->color('gray'),
                    ]),
                Section::make('Pares categoría + problema')
                    ->schema([
                        TextEntry::make('pares')
                            ->hiddenLabel()
                            ->state(fn (Soporte $record): array => $record->pares
                                ->map(fn ($par): string => $par->categoria->nombre.': '.$par->problema->nombre)
                                ->all())
                            ->badge(),
                    ]),
                Section::make('Descripción')
                    ->visible(fn (Soporte $record): bool => filled($record->descripcion))
                    ->schema([
                        TextEntry::make('descripcion')
                            ->hiddenLabel(),
                    ]),
            ]);
    }
}
