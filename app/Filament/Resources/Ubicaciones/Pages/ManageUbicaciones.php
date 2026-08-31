<?php

namespace App\Filament\Resources\Ubicaciones\Pages;

use App\Filament\Resources\Ubicaciones\UbicacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUbicaciones extends ManageRecords
{
    protected static string $resource = UbicacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
