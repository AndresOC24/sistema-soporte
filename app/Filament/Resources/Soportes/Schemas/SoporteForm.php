<?php

namespace App\Filament\Resources\Soportes\Schemas;

use App\Models\Categoria;
use App\Models\PersonalTi;
use App\Models\Problema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class SoporteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cuándo')
                    ->description('La duración se calcula sola a partir de las horas.')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('fecha')
                            ->label('Fecha')
                            ->default(today())
                            ->maxDate(today())
                            ->required(),
                        TimePicker::make('hora_inicio')
                            ->label('Hora de inicio')
                            ->seconds(false)
                            ->required(),
                        TimePicker::make('hora_fin')
                            ->label('Hora de fin')
                            ->seconds(false)
                            ->after('hora_inicio')
                            ->required(),
                    ]),

                Section::make('Quién y dónde')
                    ->description('El solicitante es quien pidió la ayuda; la ubicación es dónde se atendió. No siempre coinciden.')
                    ->columns(2)
                    ->schema([
                        Select::make('solicitante_id')
                            ->label('Solicitante')
                            ->relationship('solicitante', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            /** El catalogo de solicitantes se alimenta al vuelo al registrar. */
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique('solicitantes', 'nombre'),
                            ]),
                        Select::make('ubicacion_id')
                            ->label('Ubicación')
                            ->relationship('ubicacion', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique('ubicaciones', 'nombre'),
                            ]),
                    ]),

                Section::make('Qué se atendió')
                    ->description('Un soporte puede traer varios pares categoría + problema. Cada par cuenta por separado en los reportes.')
                    ->schema([
                        Repeater::make('pares')
                            ->label('Pares categoría + problema')
                            ->relationship()
                            ->addActionLabel('Agregar otro problema')
                            ->minItems(1)
                            ->defaultItems(1)
                            ->columns(2)
                            ->schema([
                                Select::make('categoria_id')
                                    ->label('Categoría')
                                    ->options(fn (): Collection => Categoria::orderBy('nombre')->pluck('nombre', 'id'))
                                    ->required()
                                    ->live()
                                    /** Al cambiar de categoria el problema anterior deja de ser valido. */
                                    ->afterStateUpdated(fn (Set $set) => $set('problema_id', null)),
                                Select::make('problema_id')
                                    ->label('Problema')
                                    ->options(fn (Get $get): Collection => Problema::query()
                                        ->where('categoria_id', $get('categoria_id'))
                                        ->orderBy('nombre')
                                        ->pluck('nombre', 'id'))
                                    ->required()
                                    ->distinct()
                                    ->disabled(fn (Get $get): bool => blank($get('categoria_id')))
                                    ->helperText(fn (Get $get): ?string => blank($get('categoria_id')) ? 'Elige primero una categoría.' : null)
                                    /** Los problemas nuevos se agregan bajo la categoria ya elegida. */
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->label('Nombre del problema')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->createOptionUsing(fn (array $data, Get $get): int => Problema::firstOrCreate([
                                        'categoria_id' => $get('categoria_id'),
                                        'nombre' => $data['nombre'],
                                    ])->getKey()),
                            ]),
                    ]),

                Section::make('Quién atendió')
                    ->description('Un caso puede atenderlo más de una persona. Cuenta para cada una.')
                    ->schema([
                        Select::make('personalTi')
                            ->label('Personal de TI')
                            ->relationship('personalTi', 'nombre')
                            ->getOptionLabelFromRecordUsing(fn (PersonalTi $record): string => $record->activo
                                ? $record->nombre
                                : $record->nombre.' (inactivo)')
                            ->multiple()
                            ->preload()
                            ->required(),
                    ]),

                Section::make('Detalle')
                    ->collapsed()
                    ->schema([
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->helperText('Opcional. El detalle principal ya está en los pares categoría + problema.')
                            ->rows(3),
                    ]),
            ]);
    }
}
