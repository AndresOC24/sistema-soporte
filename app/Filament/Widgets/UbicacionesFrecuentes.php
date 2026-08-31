<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\UsaRangoDeFechas;
use App\Models\Ubicacion;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UbicacionesFrecuentes extends TableWidget
{
    use UsaRangoDeFechas;

    protected static ?int $sort = 4;

    protected static ?string $heading = 'Ubicaciones con más incidencias';

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Ubicacion::query()
                ->withCount(['soportes' => fn (Builder $q): Builder => $this->acotarSoportes($q)])
                ->having('soportes_count', '>', 0)
                ->orderByDesc('soportes_count'))
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('nombre')
                    ->label('Ubicación'),
                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge(),
                TextColumn::make('soportes_count')
                    ->label('Soportes')
                    ->badge()
                    ->alignEnd(),
            ])
            ->emptyStateHeading('Sin ubicaciones en este rango');
    }
}
