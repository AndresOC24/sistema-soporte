<?php

namespace Database\Factories;

use App\Enums\RolPersonalTi;
use App\Models\PersonalTi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonalTi>
 */
class PersonalTiFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->name(),
            'rol' => RolPersonalTi::Becario,
            'activo' => true,
        ];
    }

    public function inactivo(): static
    {
        return $this->state(fn (): array => ['activo' => false]);
    }
}
