<?php

namespace Database\Factories;

use App\Enums\TipoUbicacion;
use App\Models\Ubicacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ubicacion>
 */
class UbicacionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => 'Aula '.fake()->unique()->numberBetween(100, 999),
            'tipo' => fake()->randomElement(TipoUbicacion::cases()),
        ];
    }
}
