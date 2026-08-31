<?php

namespace App\Models;

use Database\Factories\SolicitanteFactory;
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
#[Table('solicitantes')]
#[Fillable(['nombre'])]
class Solicitante extends Model
{
    /** @use HasFactory<SolicitanteFactory> */
    use HasFactory;

    /** @return HasMany<Soporte, $this> */
    public function soportes(): HasMany
    {
        return $this->hasMany(Soporte::class);
    }
}
