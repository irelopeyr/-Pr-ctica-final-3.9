<?php
// Sirve para poblar la tabla de proyectos con datos de prueba
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'user_id' => 1,
                'title' => 'Rediseño web de la cafetería',
                'description' => 'Nueva web con carta y reservas online.',
                'due_date' => '2026-12-15',
                'status' => 'en curso',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'title' => 'App de reservas',
                'description' => 'Aplicación para reservar mesas desde el móvil.',
                'due_date' => '2027-02-01',
                'status' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'title' => 'Tienda online de ropa',
                'description' => 'Catálogo y carrito de compra.',
                'due_date' => '2026-11-30',
                'status' => 'terminado',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'title' => 'Blog de viajes',
                'description' => 'Blog con galería de fotos y comentarios.',
                'due_date' => '2027-01-20',
                'status' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('project_user')->insert([
            ['project_id' => 1, 'user_id' => 2, 'role' => 'Desarrollador', 'contribution_hours' => 12],
            ['project_id' => 1, 'user_id' => 3, 'role' => 'Diseñador', 'contribution_hours' => 5],
            ['project_id' => 2, 'user_id' => 1, 'role' => 'Tester', 'contribution_hours' => 8],
            ['project_id' => 2, 'user_id' => 3, 'role' => 'Desarrollador', 'contribution_hours' => 20],
            ['project_id' => 3, 'user_id' => 2, 'role' => 'Coordinador', 'contribution_hours' => 15],
            ['project_id' => 4, 'user_id' => 1, 'role' => 'Diseñador', 'contribution_hours' => 3],
            ['project_id' => 4, 'user_id' => 4, 'role' => 'Desarrollador', 'contribution_hours' => 10],
        ]);
    }
}