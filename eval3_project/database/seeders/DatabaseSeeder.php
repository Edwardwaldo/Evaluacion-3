<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Crea un usuario de prueba para poder probar la API de proyectos
     * (por ejemplo en Postman) sin tener que registrarse primero desde
     * el navegador. El id de este usuario (normalmente 1) es el valor
     * que se debe usar en el campo created_by al probar POST /api/proyectos.
     */
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['correo' => 'test@techsolutions.cl'],
            [
                'nombre' => 'Usuario de Prueba',
                'clave' => '123456', // se cifra automaticamente (cast 'hashed' del modelo Usuario)
            ]
        );
    }
}
