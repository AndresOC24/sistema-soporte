<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TipoUbicacion: string implements HasLabel
{
    case Aula = 'aula';
    case Area = 'area';

    public function getLabel(): string
    {
        return match ($this) {
            self::Aula => 'Aula',
            self::Area => 'Área',
        };
    }
}
