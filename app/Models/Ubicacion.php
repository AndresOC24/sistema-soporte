<?php

namespace App\Models;

use App\Enums\TipoUbicacion;
use Database\Factories\UbicacionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nombre
 * @property TipoUbicacion $tipo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Table('ubicaciones')]
#[Fillable(['nombre', 'tipo'])]
class Ubicacion extends Model
{
    /** @use HasFactory<UbicacionFactory> */
    use HasFactory;

    /** @return HasMany<Soporte, $this> */
    public function soportes(): HasMany
    {
        return $this->hasMany(Soporte::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'tipo' => TipoUbicacion::class,
        ];
    }
}
