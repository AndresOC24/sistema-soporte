<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\UsaRangoDeFechas;
use App\Models\Soporte;
use App\Models\SoporteProblema;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResumenSoportes extends StatsOverviewWidget
{
    use UsaRangoDeFechas;

    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $soportes = $this->acotarSoportes(Soporte::query());

        $totalSoportes = (clone $soportes)->count();
        $totalMinutos = (int) (clone $soportes)->sum('duracion_minutos');

        $totalProblemas = $this->acotarPares(SoporteProblema::query())->count();

        return [
            Stat::make('Soportes', number_format($totalSoportes))
                ->description('Visitas o atenciones registradas')
                ->color('primary'),

            Stat::make('Problemas atendidos', number_format($totalProblemas))
                ->description('Pares categoría + problema; un soporte puede traer varios')
                ->color('info'),

            Stat::make('Tiempo invertido', Soporte::formatearMinutos($totalMinutos))
                ->description($totalSoportes > 0
                    ? 'Promedio de '.Soporte::formatearMinutos(intdiv($totalMinutos, $totalSoportes)).' por soporte'
                    : 'Sin soportes en el rango')
                ->color('success'),
        ];
    }
}
