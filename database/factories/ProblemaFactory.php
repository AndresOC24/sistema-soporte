<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Problema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Problema>
 */
class ProblemaFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categoria_id' => Categoria::factory(),
            'nombre' => ucfirst(fake()->unique()->word()).' '.fake()->word(),
        ];
    }
}
