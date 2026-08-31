<?php

namespace App\Filament\Resources\PersonalTi\Pages;

use App\Filament\Resources\PersonalTi\PersonalTiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePersonalTi extends ManageRecords
{
    protected static string $resource = PersonalTiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
