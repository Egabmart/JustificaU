<?php

namespace Database\Seeders;

use App\Factories\UserFactory;
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
        $admin = UserFactory::make(User::ROLE_ADMIN, [
            'name' => 'Administrador UAM',
            'email' => 'admin@uam.edu.ni',
            'cif' => '000000-ADMIN', // CIF de ejemplo para el admin
            'facultad' => 'Administración de Sistemas',
            'carrera' => 'N/A',
            'password' => Hash::make('admin123'), // IMPORTANTE: Las contraseñas siempre se guardan hasheadas (encriptadas)
        ]);

        User::updateOrCreate(
            [
                'email' => 'admin@uam.edu.ni'
            ],
            $admin->getAttributes()
        );
    }
}