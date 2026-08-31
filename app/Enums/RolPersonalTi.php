<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RolPersonalTi: string implements HasLabel
{
    case Jefe = 'jefe';
    case Asistente = 'asistente';
    case Becario = 'becario';

    public function getLabel(): string
    {
        return match ($this) {
            self::Jefe => 'Jefe de TI',
            self::Asistente => 'Asistente de soporte',
            self::Becario => 'Becario',
        };
    }
}
