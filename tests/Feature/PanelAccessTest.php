<?php

use App\Filament\Resources\Categorias\RelationManagers\ProblemasRelationManager;
use App\Models\Categoria;
use App\Models\PersonalTi;
use App\Models\Problema;
use App\Models\Soporte;
use App\Models\User;

test('la raiz redirige al login del panel', function () {
    $this->get('/')->assertRedirect(route('filament.admin.auth.login'));
});

test('un visitante sin sesion es enviado al login', function () {
    $this->get('/admin')->assertRedirect(route('filament.admin.auth.login'));
});

test('un usuario autenticado entra al panel fuera del entorno local', function () {
    expect(config('app.env'))->not->toBe('local');

    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertSuccessful();
});

test('todas las paginas del panel cargan', function (string $ruta) {
    $this->actingAs(User::factory()->create())
        ->get($ruta)
        ->assertSuccessful();
})->with([
    'dashboard' => '/admin',
    'soportes' => '/admin/soportes',
    'nuevo soporte' => '/admin/soportes/create',
    'categorias' => '/admin/categorias',
    'nueva categoria' => '/admin/categorias/create',
    'ubicaciones' => '/admin/ubicaciones',
    'solicitantes' => '/admin/solicitantes',
    'personal de TI' => '/admin/personal-ti',
]);

test('la edicion de una categoria carga con su gestor de problemas', function () {
    $categoria = Categoria::factory()
        ->has(Problema::factory()->count(3), 'problemas')
        ->create();

    $this->actingAs(User::factory()->create())
        ->get("/admin/categorias/{$categoria->id}/edit")
        ->assertSuccessful()
        ->assertSeeLivewire(ProblemasRelationManager::class);
});

test('el dashboard carga con datos reales', function () {
    $soporte = Soporte::factory()->create();
    $soporte->pares()->create(['problema_id' => Problema::factory()->create()->id]);
    $soporte->personalTi()->attach(PersonalTi::factory()->create());

    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertSuccessful();
});

test('la vista de un soporte carga', function () {
    $soporte = Soporte::factory()->create();
    $soporte->pares()->create(['problema_id' => Problema::factory()->create()->id]);
    $soporte->personalTi()->attach(PersonalTi::factory()->create());

    $this->actingAs(User::factory()->create())
        ->get("/admin/soportes/{$soporte->id}")
        ->assertSuccessful();
});
