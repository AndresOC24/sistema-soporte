<?php

namespace Database\Factories;

use App\Models\Solicitante;
use App\Models\Soporte;
use App\Models\Ubicacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Soporte>
 */
class SoporteFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $inicio = fake()->dateTimeBetween('-6 months', 'now')->setTime(
            fake()->numberBetween(7, 18),
            fake()->randomElement([0, 15, 30, 45]),
        );

        $fin = (clone $inicio)->modify('+'.fake()->numberBetween(5, 120).' minutes');

        return [
            'fecha' => $inicio->format('Y-m-d'),
            'hora_inicio' => $inicio->format('H:i:s'),
            'hora_fin' => $fin->format('H:i:s'),
            'solicitante_id' => Solicitante::factory(),
            'ubicacion_id' => Ubicacion::factory(),
            'descripcion' => fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }
}
