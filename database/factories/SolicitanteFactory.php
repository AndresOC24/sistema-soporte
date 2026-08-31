<?php

namespace Database\Factories;

use App\Models\Solicitante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Solicitante>
 */
class SolicitanteFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->name(),
        ];
    }
}
