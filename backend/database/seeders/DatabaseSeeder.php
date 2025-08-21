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
            'name' => 'admin',
            'email' => 'admin@ejemplo.com',
            'password' => '12345678',
            'telefono' => '76543210',
            'rol' => 'administrador',
        ]);
        $this->call(EspecialidadSeeder::class);
        $this->call(PerfilPacienteSeeder::class);
        $this->call(PerfilMedicoSeeder::class);
        $this->call(AusenciaSeeder::class);
        $this->call(HorarioSeeder::class);
        $this->call(CitaSeeder::class);
    }

}
