<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PerfilDoctor;
use Illuminate\Support\Facades\Hash;

class PerfilDoctorSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'DOCTOR DE PRUEBA',
            'email' => 'doctor@ejemplo.com',
            'password' => Hash::make('12345678'),
        ]);

        PerfilDoctor::create([
            'reg_profesional' => 'MP-12345',
            'biografia' => 'MÉDICO CON 10 AÑOS DE EXPERIENCIA EN MEDICINA GENERAL',
            'especialidad_id' => 1,
            'user_id' => $user->id,
        ]);
    }
}
