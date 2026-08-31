<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $title = 'Dashboard';

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rango de fechas')
                    ->description('Deja un campo vacío para no acotar ese extremo.')
                    /** El formulario hereda la rejilla de widgets, asi que hay que pedir la fila entera. */
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('desde')
                            ->label('Desde')
                            ->default(now()->startOfMonth()),
                        DatePicker::make('hasta')
                            ->label('Hasta')
                            ->default(now()->endOfMonth()),
                    ]),
            ]);
    }
}
