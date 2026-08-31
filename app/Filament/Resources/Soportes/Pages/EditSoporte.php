<?php

namespace App\Filament\Resources\Soportes\Pages;

use App\Filament\Resources\Soportes\SoporteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSoporte extends EditRecord
{
    protected static string $resource = SoporteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
