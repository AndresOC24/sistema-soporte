<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Administrador', 'password' => 'admin123'],
        );

        $this->call(CatalogoSeeder::class);
    }
}
