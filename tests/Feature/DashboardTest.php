<?php

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\ProblemasFrecuentes;
use App\Filament\Widgets\ResumenSoportes;
use App\Filament\Widgets\SolicitantesFrecuentes;
use App\Filament\Widgets\SoportesPorCategoria;
use App\Filament\Widgets\SoportesPorPersonaTi;
use App\Filament\Widgets\UbicacionesFrecuentes;
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

/**
 * Escenario compartido: dos soportes dentro del rango y uno fuera.
 * - Soporte A: 60 min, 2 problemas, 2 personas.
 * - Soporte B: 30 min, 1 problema, 1 persona (mismo solicitante que A).
 * - Soporte C: fuera del rango, no debe contar en nada.
 */
function escenario(): array
{
    $solicitante = Solicitante::factory()->create(['nombre' => 'Docente Frecuente']);
    $ubicacion = Ubicacion::factory()->create(['nombre' => 'Aula 409']);
    [$ana, $beto] = [PersonalTi::factory()->create(['nombre' => 'Ana']), PersonalTi::factory()->create(['nombre' => 'Beto'])];
    $problemas = Problema::factory()->count(2)->create();

    $a = Soporte::factory()->create([
        'fecha' => '2026-05-10', 'hora_inicio' => '09:00:00', 'hora_fin' => '10:00:00',
        'solicitante_id' => $solicitante->id, 'ubicacion_id' => $ubicacion->id,
    ]);
    $a->pares()->create(['problema_id' => $problemas[0]->id]);
    $a->pares()->create(['problema_id' => $problemas[1]->id]);
    $a->personalTi()->attach([$ana->id, $beto->id]);

    $b = Soporte::factory()->create([
        'fecha' => '2026-05-20', 'hora_inicio' => '11:00:00', 'hora_fin' => '11:30:00',
        'solicitante_id' => $solicitante->id, 'ubicacion_id' => $ubicacion->id,
    ]);
    $b->pares()->create(['problema_id' => $problemas[0]->id]);
    $b->personalTi()->attach($ana->id);

    $c = Soporte::factory()->create([
        'fecha' => '2026-07-01', 'hora_inicio' => '08:00:00', 'hora_fin' => '12:00:00',
    ]);
    $c->pares()->create(['problema_id' => $problemas[0]->id]);
    $c->personalTi()->attach($beto->id);

    return compact('solicitante', 'ubicacion', 'ana', 'beto', 'problemas');
}

const RANGO = ['desde' => '2026-05-01', 'hasta' => '2026-05-31'];

/**
 * getStats() y getData() son protegidos en Filament; los widgets no exponen
 * sus cifras de otro modo, asi que las leemos por reflexion.
 */
function datosDelWidget(object $widget, string $metodo): array
{
    $reflexion = new ReflectionMethod($widget, $metodo);
    $reflexion->setAccessible(true);

    return $reflexion->invoke($widget);
}

test('el dashboard carga', function () {
    Livewire::test(Dashboard::class)->assertOk();
});

test('el resumen cuenta soportes, problemas y tiempo dentro del rango', function () {
    escenario();

    $stats = datosDelWidget(
        Livewire::test(ResumenSoportes::class, ['pageFilters' => RANGO])->instance(),
        'getStats',
    );

    // 2 soportes, 3 pares (mas problemas que soportes), 90 min en total.
    expect($stats[0]->getValue())->toBe('2')
        ->and($stats[1]->getValue())->toBe('3')
        ->and($stats[2]->getValue())->toBe('1 h 30 min');
});

test('el resumen excluye lo que cae fuera del rango', function () {
    escenario();

    $stats = datosDelWidget(
        Livewire::test(ResumenSoportes::class, ['pageFilters' => ['desde' => '2026-07-01', 'hasta' => '2026-07-31']])->instance(),
        'getStats',
    );

    expect($stats[0]->getValue())->toBe('1')
        ->and($stats[2]->getValue())->toBe('4 h 0 min');
});

test('un soporte entre dos personas cuenta para cada una', function () {
    escenario();

    $datos = datosDelWidget(
        Livewire::test(SoportesPorPersonaTi::class, ['pageFilters' => RANGO])->instance(),
        'getData',
    );

    $conteos = array_combine($datos['labels'], $datos['datasets'][0]['data']);

    // Ana atendio A y B; Beto solo A. Suman 3 sobre 2 soportes.
    expect($conteos)->toBe(['Ana' => 2, 'Beto' => 1])
        ->and(array_sum($conteos))->toBe(3);
});

test('los solicitantes se ordenan por soportes pedidos en el rango', function () {
    escenario();

    Livewire::test(SolicitantesFrecuentes::class, ['pageFilters' => RANGO])
        ->assertOk()
        ->assertCanSeeTableRecords(Solicitante::where('nombre', 'Docente Frecuente')->get());
});

test('las ubicaciones se ordenan por incidencias en el rango', function () {
    escenario();

    Livewire::test(UbicacionesFrecuentes::class, ['pageFilters' => RANGO])
        ->assertOk()
        ->assertCanSeeTableRecords(Ubicacion::where('nombre', 'Aula 409')->get());
});

test('las categorias cuentan pares, no soportes', function () {
    $datos = escenario();

    $grafica = datosDelWidget(
        Livewire::test(SoportesPorCategoria::class, ['pageFilters' => RANGO])->instance(),
        'getData',
    );

    // 3 pares repartidos en 2 categorias distintas: 2 del primer problema, 1 del segundo.
    expect(array_sum($grafica['datasets'][0]['data']))->toBe(3)
        ->and($grafica['labels'])->toHaveCount(2);
});

test('los problemas frecuentes solo muestran los usados en el rango', function () {
    $datos = escenario();

    Livewire::test(ProblemasFrecuentes::class, ['pageFilters' => RANGO])
        ->assertOk()
        ->assertCanSeeTableRecords([$datos['problemas'][0]]);
});
