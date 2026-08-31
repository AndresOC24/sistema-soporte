<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Un par categoria+problema dentro de un soporte. La categoria se guarda aqui
 * para reportar por categoria sin joins extra; se deriva siempre del problema
 * al guardar, asi que no puede quedar desincronizada.
 *
 * @property int $id
 * @property int $soporte_id
 * @property int $categoria_id
 * @property int $problema_id
 * @property-read Problema $problema
 * @property-read Categoria $categoria
 */
#[Table('soporte_problema')]
#[Fillable(['soporte_id', 'categoria_id', 'problema_id'])]
class SoporteProblema extends Pivot
{
    public $incrementing = true;

    public $timestamps = false;

    /** @return BelongsTo<Soporte, $this> */
    public function soporte(): BelongsTo
    {
        return $this->belongsTo(Soporte::class);
    }

    /** @return BelongsTo<Problema, $this> */
    public function problema(): BelongsTo
    {
        return $this->belongsTo(Problema::class);
    }

    /** @return BelongsTo<Categoria, $this> */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    protected static function booted(): void
    {
        static::saving(function (SoporteProblema $par): void {
            $par->categoria_id = Problema::whereKey($par->problema_id)->value('categoria_id');
        });
    }
}
