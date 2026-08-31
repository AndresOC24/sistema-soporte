<?php

use App\Models\PersonalTi;
use App\Models\Problema;
use App\Models\Soporte;
use App\Models\SoporteProblema;
use Illuminate\Database\QueryException;

test('la duracion se calcula sola a partir de las horas', function () {
    $soporte = Soporte::factory()->create([
        'hora_inicio' => '09:15:00',
        'hora_fin' => '10:45:00',
    ]);

    expect($soporte->refresh()->duracion_minutos)->toBe(90);
});

test('la duracion se recalcula al cambiar las horas', function () {
    $soporte = Soporte::factory()->create([
        'hora_inicio' => '09:00:00',
        'hora_fin' => '09:30:00',
    ]);

    $soporte->update(['hora_fin' => '11:00:00']);

    expect($soporte->refresh()->duracion_minutos)->toBe(120);
});

test('el par guarda la categoria derivada del problema', function () {
    $problema = Problema::factory()->create();
    $soporte = Soporte::factory()->create();

    $par = $soporte->pares()->create(['problema_id' => $problema->id]);

    expect($par->categoria_id)->toBe($problema->categoria_id);
});

test('un soporte con varios pares suma mas problemas que soportes', function () {
    $soporte = Soporte::factory()->create();
    $problemas = Problema::factory()->count(3)->create();

    foreach ($problemas as $problema) {
        $soporte->pares()->create(['problema_id' => $problema->id]);
    }

    expect(Soporte::count())->toBe(1)
        ->and(SoporteProblema::count())->toBe(3)
        ->and($soporte->problemas()->count())->toBe(3);
});

test('un soporte atendido por varias personas cuenta para cada una', function () {
    $soporte = Soporte::factory()->create();
    $personal = PersonalTi::factory()->count(2)->create();

    $soporte->personalTi()->attach($personal);

    foreach ($personal as $persona) {
        expect($persona->soportes()->count())->toBe(1);
    }
});

test('no se puede borrar un catalogo en uso', function () {
    $soporte = Soporte::factory()->create();

    expect(fn () => $soporte->solicitante->delete())
        ->toThrow(QueryException::class);
});

test('borrar un soporte se lleva sus pares pero no los catalogos', function () {
    $problema = Problema::factory()->create();
    $soporte = Soporte::factory()->create();
    $soporte->pares()->create(['problema_id' => $problema->id]);

    $soporte->delete();

    expect(SoporteProblema::count())->toBe(0)
        ->and(Problema::whereKey($problema->id)->exists())->toBeTrue();
});
