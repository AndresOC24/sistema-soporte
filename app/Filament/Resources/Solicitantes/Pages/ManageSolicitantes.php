<?php

namespace App\Filament\Resources\Solicitantes\Pages;

use App\Filament\Resources\Solicitantes\SolicitanteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSolicitantes extends ManageRecords
{
    protected static string $resource = SolicitanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
