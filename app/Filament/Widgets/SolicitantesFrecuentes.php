<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\UsaRangoDeFechas;
use App\Models\Solicitante;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class SolicitantesFrecuentes extends TableWidget
{
    use UsaRangoDeFechas;

    protected static ?int $sort = 3;

    protected static ?string $heading = 'Quién pide más ayuda';

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Solicitante::query()
                ->withCount(['soportes' => fn (Builder $q): Builder => $this->acotarSoportes($q)])
                ->having('soportes_count', '>', 0)
                ->orderByDesc('soportes_count'))
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('nombre')
                    ->label('Solicitante'),
                TextColumn::make('soportes_count')
                    ->label('Soportes')
                    ->badge()
                    ->alignEnd(),
            ])
            ->emptyStateHeading('Sin solicitantes en este rango');
    }
}
