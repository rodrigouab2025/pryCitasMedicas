<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PerfilMedico;
use Illuminate\Support\Facades\Hash;

class PerfilMedicoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'MEDICO DE PRUEBA',
            'email' => 'medico@ejemplo.com',
            'password' => Hash::make('12345678'),
            'rol' => 'medico',
        ]);

        PerfilMedico::create([
            'reg_profesional' => 'MP-12345',
            'biografia' => 'MÉDICO CON 10 AÑOS DE EXPERIENCIA EN MEDICINA GENERAL',
            'especialidad_id' => 1,
            'user_id' => $user->id,
        ]);
    }
}
