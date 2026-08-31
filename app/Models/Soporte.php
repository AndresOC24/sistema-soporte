<?php

namespace App\Models;

use Database\Factories\SoporteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon $fecha
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property int $duracion_minutos
 * @property int $solicitante_id
 * @property int $ubicacion_id
 * @property string|null $descripcion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Solicitante $solicitante
 * @property-read Ubicacion $ubicacion
 */
#[Table('soportes')]
#[Fillable(['fecha', 'hora_inicio', 'hora_fin', 'solicitante_id', 'ubicacion_id', 'descripcion'])]
class Soporte extends Model
{
    /** @use HasFactory<SoporteFactory> */
    use HasFactory;

    /** @return BelongsTo<Solicitante, $this> */
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(Solicitante::class);
    }

    /** @return BelongsTo<Ubicacion, $this> */
    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class);
    }

    /**
     * Los pares categoria+problema como registros propios. Es la relacion que
     * usa el formulario (repeater), porque cada par es una unidad editable.
     *
     * @return HasMany<SoporteProblema, $this>
     */
    public function pares(): HasMany
    {
        return $this->hasMany(SoporteProblema::class);
    }

    /** @return BelongsToMany<Problema, $this> */
    public function problemas(): BelongsToMany
    {
        return $this->belongsToMany(Problema::class, 'soporte_problema');
    }

    /**
     * Las categorias tocadas por este soporte. Va distinct porque dos problemas
     * de la misma categoria dejarian dos filas en el pivote.
     *
     * @return BelongsToMany<Categoria, $this>
     */
    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'soporte_problema')->distinct();
    }

    /** @return BelongsToMany<PersonalTi, $this> */
    public function personalTi(): BelongsToMany
    {
        return $this->belongsToMany(PersonalTi::class, 'personal_ti_soporte');
    }

    /**
     * Convierte minutos a "1 h 30 min". Compartido con los widgets del dashboard,
     * que suman minutos en SQL y necesitan la misma presentacion.
     */
    public static function formatearMinutos(int $minutos): string
    {
        $horas = intdiv($minutos, 60);
        $restantes = $minutos % 60;

        return $horas > 0 ? "{$horas} h {$restantes} min" : "{$restantes} min";
    }

    /** @return Attribute<string, never> */
    protected function duracionLegible(): Attribute
    {
        return Attribute::get(fn (): string => self::formatearMinutos($this->duracion_minutos));
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'duracion_minutos' => 'integer',
        ];
    }
}
