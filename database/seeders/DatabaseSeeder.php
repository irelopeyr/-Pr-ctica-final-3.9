<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

    // El método run() llama a los seeders específicos para poblar la base de datos con datos de prueba. 
    public function run(): void{
    $this->call([
        UserSeeder::class,
        ProjectSeeder::class,
    ]);
}
}
