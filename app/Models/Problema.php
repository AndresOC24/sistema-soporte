<?php

namespace App\Models;

use Database\Factories\ProblemaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $categoria_id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Categoria $categoria
 */
#[Table('problemas')]
#[Fillable(['categoria_id', 'nombre'])]
class Problema extends Model
{
    /** @use HasFactory<ProblemaFactory> */
    use HasFactory;

    /** @return BelongsTo<Categoria, $this> */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /** @return BelongsToMany<Soporte, $this> */
    public function soportes(): BelongsToMany
    {
        return $this->belongsToMany(Soporte::class, 'soporte_problema');
    }

    /** @return HasMany<SoporteProblema, $this> */
    public function pares(): HasMany
    {
        return $this->hasMany(SoporteProblema::class);
    }
}
