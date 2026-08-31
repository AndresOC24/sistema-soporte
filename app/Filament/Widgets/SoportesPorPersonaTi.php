<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\UsaRangoDeFechas;
use App\Models\PersonalTi;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SoportesPorPersonaTi extends ChartWidget
{
    use UsaRangoDeFechas;

    protected static ?int $sort = 6;

    protected ?string $heading = 'Soportes por persona de TI';

    protected ?string $description = 'Un soporte atendido entre varios cuenta para cada una, así que la suma supera el total de soportes.';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '20rem';

    protected function getData(): array
    {
        /** @var Collection<int, PersonalTi> $personal */
        $personal = PersonalTi::query()
            ->withCount(['soportes' => fn (Builder $q): Builder => $this->acotarSoportes($q)])
            ->having('soportes_count', '>', 0)
            ->orderByDesc('soportes_count')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Soportes atendidos',
                    'data' => $personal->pluck('soportes_count')->all(),
                ],
            ],
            'labels' => $personal->pluck('nombre')->all(),
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
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['ticks' => ['precision' => 0]]],
        ];
    }
}
