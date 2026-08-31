<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\UsaRangoDeFechas;
use App\Models\Categoria;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SoportesPorCategoria extends ChartWidget
{
    use UsaRangoDeFechas;

    protected static ?int $sort = 2;

    protected ?string $heading = 'Problemas por categoría';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '20rem';

    protected function getData(): array
    {
        /** @var Collection<int, Categoria> $categorias */
        $categorias = Categoria::query()
            ->withCount(['pares' => fn (Builder $q): Builder => $this->acotarPares($q)])
            ->having('pares_count', '>', 0)
            ->orderByDesc('pares_count')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Problemas atendidos',
                    'data' => $categorias->pluck('pares_count')->all(),
                ],
            ],
            'labels' => $categorias->pluck('nombre')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['x' => ['ticks' => ['precision' => 0]]],
        ];
    }
}
