<?php

use App\Filament\Resources\Soportes\Pages\CreateSoporte;
use App\Filament\Resources\Soportes\Pages\ListSoportes;
use App\Models\PersonalTi;
use App\Models\Problema;
use App\Models\Solicitante;
use App\Models\Soporte;
use App\Models\Ubicacion;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('registra un soporte con un par y una persona', function () {
    $problema = Problema::factory()->create();
    $solicitante = Solicitante::factory()->create();
    $ubicacion = Ubicacion::factory()->create();
    $persona = PersonalTi::factory()->create();

    Livewire::test(CreateSoporte::class)
        ->fillForm([
            'fecha' => '2026-08-20',
            'hora_inicio' => '09:15',
            'hora_fin' => '10:45',
            'solicitante_id' => $solicitante->id,
            'ubicacion_id' => $ubicacion->id,
            'pares' => [
                ['categoria_id' => $problema->categoria_id, 'problema_id' => $problema->id],
            ],
            'personalTi' => [$persona->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $soporte = Soporte::sole();

    expect($soporte->duracion_minutos)->toBe(90)
        ->and($soporte->problemas->pluck('id')->all())->toBe([$problema->id])
        ->and($soporte->personalTi->pluck('id')->all())->toBe([$persona->id])
        ->and($soporte->pares->first()->categoria_id)->toBe($problema->categoria_id);
});

test('registra un soporte con varios pares y varias personas', function () {
    $problemas = Problema::factory()->count(2)->create();
    $personal = PersonalTi::factory()->count(2)->create();

    Livewire::test(CreateSoporte::class)
        ->fillForm([
            'fecha' => '2026-08-20',
            'hora_inicio' => '08:00',
            'hora_fin' => '08:30',
            'solicitante_id' => Solicitante::factory()->create()->id,
            'ubicacion_id' => Ubicacion::factory()->create()->id,
            'pares' => $problemas
                ->map(fn (Problema $p): array => ['categoria_id' => $p->categoria_id, 'problema_id' => $p->id])
                ->all(),
            'personalTi' => $personal->pluck('id')->all(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $soporte = Soporte::sole();

    expect($soporte->pares()->count())->toBe(2)
        ->and($soporte->personalTi()->count())->toBe(2);
});

test('rechaza una hora de fin anterior a la de inicio', function () {
    $problema = Problema::factory()->create();

    Livewire::test(CreateSoporte::class)
        ->fillForm([
            'fecha' => '2026-08-20',
            'hora_inicio' => '10:00',
            'hora_fin' => '09:00',
            'solicitante_id' => Solicitante::factory()->create()->id,
            'ubicacion_id' => Ubicacion::factory()->create()->id,
            'pares' => [['categoria_id' => $problema->categoria_id, 'problema_id' => $problema->id]],
            'personalTi' => [PersonalTi::factory()->create()->id],
        ])
        ->call('create')
        ->assertHasFormErrors(['hora_fin']);

    expect(Soporte::count())->toBe(0);
});

test('exige al menos un par categoria mas problema', function () {
    Livewire::test(CreateSoporte::class)
        ->fillForm([
            'fecha' => '2026-08-20',
            'hora_inicio' => '10:00',
            'hora_fin' => '11:00',
            'solicitante_id' => Solicitante::factory()->create()->id,
            'ubicacion_id' => Ubicacion::factory()->create()->id,
            'pares' => [],
            'personalTi' => [PersonalTi::factory()->create()->id],
        ])
        ->call('create')
        ->assertHasFormErrors(['pares']);

    expect(Soporte::count())->toBe(0);
});

test('el listado de soportes carga', function () {
    $soportes = Soporte::factory()->count(3)->create();

    Livewire::test(ListSoportes::class)
        ->assertOk()
        ->assertCanSeeTableRecords($soportes);
});

test('la tabla ofrece las columnas opcionales', function () {
    $tabla = Livewire::test(ListSoportes::class);

    // Visibles de entrada.
    foreach (['fecha', 'hora_inicio', 'duracion_minutos', 'solicitante.nombre', 'ubicacion.nombre', 'problemas.nombre', 'personalTi.nombre'] as $columna) {
        $tabla->assertTableColumnExists($columna);
    }

    // Ocultas por defecto, disponibles desde el selector de columnas.
    foreach (['hora_fin', 'ubicacion.tipo', 'categorias.nombre', 'descripcion', 'created_at'] as $columna) {
        $tabla->assertTableColumnExists($columna);
    }
});

test('las columnas ocultas muestran datos al activarlas', function () {
    $problema = Problema::factory()->create();
    $soporte = Soporte::factory()->create([
        'hora_inicio' => '09:00:00',
        'hora_fin' => '10:30:00',
        'descripcion' => 'El proyector no daba señal por el cable',
    ]);
    $soporte->pares()->create(['problema_id' => $problema->id]);

    $tabla = Livewire::test(ListSoportes::class);

    $aActivar = ['hora_fin', 'categorias.nombre', 'descripcion'];

    $estado = array_map(
        fn (array $columna): array => in_array($columna['name'], $aActivar, true)
            ? [...$columna, 'isToggled' => true]
            : $columna,
        $tabla->instance()->getDefaultTableColumnState(),
    );

    $tabla->call('applyTableColumnManager', $estado)
        ->assertSee('10:30')
        ->assertSee($problema->categoria->nombre)
        ->assertSee('El proyector no daba señal por el cable');
});
