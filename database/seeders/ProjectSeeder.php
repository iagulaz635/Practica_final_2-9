<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Los usuarios ya existen porque UserSeeder se ejecuta antes (ver DatabaseSeeder)
        $ana = User::where('email', 'ana@example.com')->first();
        $luis = User::where('email', 'luis@example.com')->first();
        $marta = User::where('email', 'marta@example.com')->first();
        $pablo = User::where('email', 'pablo@example.com')->first();

        // Proyecto 1: propietaria Ana
        $web = $ana->projects()->create([
            'title' => 'Rediseño de la web corporativa',
            'description' => 'Renovar la imagen y la estructura del sitio web de la empresa.',
            'due_date' => '2026-12-15',
            'status' => 'en curso',
        ]);
        $web->participants()->attach([
            $luis->id => ['role' => 'Desarrollador', 'contribution_hours' => 24.5],
            $marta->id => ['role' => 'Diseñador', 'contribution_hours' => 18],
        ]);

        // Proyecto 2: propietaria Ana
        $app = $ana->projects()->create([
            'title' => 'App móvil de reservas',
            'description' => 'Aplicación para reservar mesas en restaurantes.',
            'due_date' => '2027-03-01',
            'status' => 'pendiente',
        ]);
        $app->participants()->attach([
            $pablo->id => ['role' => 'Tester', 'contribution_hours' => 0],
        ]);

        // Proyecto 3: propietario Luis
        $api = $luis->projects()->create([
            'title' => 'API de facturación',
            'description' => 'Servicio REST para generar y consultar facturas.',
            'due_date' => '2026-11-30',
            'status' => 'en curso',
        ]);
        $api->participants()->attach([
            $ana->id => ['role' => 'Desarrollador', 'contribution_hours' => 40],
            $pablo->id => ['role' => 'Tester', 'contribution_hours' => 12.25],
        ]);

        // Proyecto 4: propietaria Marta, sin participantes todavía
        $marta->projects()->create([
            'title' => 'Manual de identidad visual',
            'description' => 'Documento con logotipos, colores y tipografías de la marca.',
            'due_date' => '2026-10-01',
            'status' => 'finalizado',
        ]);
    }
}