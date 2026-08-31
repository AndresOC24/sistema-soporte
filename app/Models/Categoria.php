<?php

namespace App\Models;

use Database\Factories\CategoriaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Table('categorias')]
#[Fillable(['nombre'])]
class Categoria extends Model
{
    /** @use HasFactory<CategoriaFactory> */
    use HasFactory;

    /** @return HasMany<Problema, $this> */
    public function problemas(): HasMany
    {
        return $this->hasMany(Problema::class);
    }

    /**
     * Los pares registrados en esta categoria. Es la unidad que cuentan los
     * reportes, no el soporte: un mismo soporte puede aportar varios pares.
     *
     * @return HasMany<SoporteProblema, $this>
     */
    public function pares(): HasMany
    {
        return $this->hasMany(SoporteProblema::class);
    }
}
