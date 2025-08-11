<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call(EspecialidadSeeder::class);
        $this->call(PerfilPacienteSeeder::class);
        $this->call(PerfilDoctorSeeder::class);
        $this->call(AusenciaSeeder::class);
        $this->call(HorarioSeeder::class);
        $this->call(CitaSeeder::class);
    }
}
