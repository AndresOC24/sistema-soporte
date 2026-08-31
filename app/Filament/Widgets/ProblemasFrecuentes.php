<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\UsaRangoDeFechas;
use App\Models\Problema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ProblemasFrecuentes extends TableWidget
{
    use UsaRangoDeFechas;

    protected static ?int $sort = 5;

    protected static ?string $heading = 'Problemas más frecuentes';

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Problema::query()
                ->with('categoria')
                ->withCount(['pares' => fn (Builder $q): Builder => $this->acotarPares($q)])
                ->having('pares_count', '>', 0)
                ->orderByDesc('pares_count'))
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->badge(),
                TextColumn::make('nombre')
                    ->label('Problema'),
                TextColumn::make('pares_count')
                    ->label('Veces')
                    ->badge()
                    ->alignEnd(),
            ])
            ->emptyStateHeading('Sin problemas registrados en este rango');
    }
}
