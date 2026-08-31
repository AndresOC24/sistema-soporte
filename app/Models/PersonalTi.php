<?php

namespace App\Models;

use App\Enums\RolPersonalTi;
use Database\Factories\PersonalTiFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nombre
 * @property RolPersonalTi $rol
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Table('personal_ti')]
#[Fillable(['nombre', 'rol', 'activo'])]
class PersonalTi extends Model
{
    /** @use HasFactory<PersonalTiFactory> */
    use HasFactory;

    /** @return BelongsToMany<Soporte, $this> */
    public function soportes(): BelongsToMany
    {
        return $this->belongsToMany(Soporte::class, 'personal_ti_soporte');
    }

    /**
     * Los becarios rotan cada semestre: se desactivan en vez de borrarse,
     * para no perder los soportes historicos que atendieron.
     *
     * @param  Builder<PersonalTi>  $query
     */
    public function scopeActivo(Builder $query): void
    {
        $query->where('activo', true);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'rol' => RolPersonalTi::class,
            'activo' => 'boolean',
        ];
    }
}
