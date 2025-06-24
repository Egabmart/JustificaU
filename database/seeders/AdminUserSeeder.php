<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // <-- Importar el modelo User
use Illuminate\Support\Facades\Hash; // <-- Importar Hash para la contraseña

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usamos updateOrCreate para evitar crear duplicados si ejecutamos el seeder varias veces.
        // Busca un usuario con el email 'admin@uam.edu.ni', y si no existe, lo crea con todos estos datos.
        User::updateOrCreate(
            [
                'email' => 'admin@uam.edu.ni'
            ],
            [
                'name' => 'Administrador UAM',
                'cif' => '000000-ADMIN', // CIF de ejemplo para el admin
                'facultad' => 'Administración de Sistemas',
                'carrera' => 'N/A',
                'password' => Hash::make('admin123'), // IMPORTANTE: Las contraseñas siempre se guardan hasheadas (encriptadas)
                'role' => 'admin', // Asignamos el rol de administrador
            ]
        );
    }
}