<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // La contraseña se guarda cifrada automáticamente (cast 'hashed' del modelo User).
        // Todos los usuarios de prueba entran con la contraseña: password
        User::create([
            'name' => 'Ana García',
            'email' => 'ana@example.com',
            'password' => 'password',
        ]);
        User::create([
            'name' => 'Luis Martínez',
            'email' => 'luis@example.com',
            'password' => 'password',
        ]);
        User::create([
            'name' => 'Marta López',
            'email' => 'marta@example.com',
            'password' => 'password',
        ]);
        User::create([
            'name' => 'Pablo Ruiz',
            'email' => 'pablo@example.com',
            'password' => 'password',
        ]);
    }
}