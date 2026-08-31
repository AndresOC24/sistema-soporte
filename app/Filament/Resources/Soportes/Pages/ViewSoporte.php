<?php

namespace App\Filament\Resources\Soportes\Pages;

use App\Filament\Resources\Soportes\SoporteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSoporte extends ViewRecord
{
    protected static string $resource = SoporteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
