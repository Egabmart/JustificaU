<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamamos al seeder que crea el administrador.
        // Podemos añadir más seeders aquí en el futuro.
        $this->call([
            AdminUserSeeder::class,
        ]);
    }
}