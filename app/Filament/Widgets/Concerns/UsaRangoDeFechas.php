<?php

namespace App\Filament\Widgets\Concerns;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Lee el rango de fechas del formulario de filtros del dashboard y lo traduce a
 * filtros de consulta. Todos los widgets comparten este rango para que sus
 * cifras sean comparables entre si.
 */
trait UsaRangoDeFechas
{
    use InteractsWithPageFilters;

    protected function desde(): ?string
    {
        return $this->pageFilters['desde'] ?? null;
    }

    protected function hasta(): ?string
    {
        return $this->pageFilters['hasta'] ?? null;
    }

    /**
     * Acota una consulta de soportes al rango elegido. La columna va calificada
     * porque este filtro tambien corre dentro de subconsultas withCount, donde
     * "fecha" seria ambiguo.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function acotarSoportes(Builder $query): Builder
    {
        $desde = $this->desde();
        $hasta = $this->hasta();

        return $query
            ->when($desde, fn (Builder $q): Builder => $q->whereDate('soportes.fecha', '>=', $desde))
            ->when($hasta, fn (Builder $q): Builder => $q->whereDate('soportes.fecha', '<=', $hasta));
    }

    /**
     * El mismo rango, pero sobre los pares categoria+problema, a traves del
     * soporte al que pertenecen.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function acotarPares(Builder $query): Builder
    {
        return $query->whereHas('soporte', fn (Builder $q): Builder => $this->acotarSoportes($q));
    }
}
