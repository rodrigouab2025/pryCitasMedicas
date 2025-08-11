<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PerfilPaciente;
use Illuminate\Support\Facades\Hash;

class PerfilPacienteSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'PACIENTE DE PRUEBA',
            'email' => 'paciente@ejemplo.com',
            'password' => Hash::make('12345678'), 
        ]);
        PerfilPaciente::create([
            'nacimiento' => '1990-05-12',
            'sexo' => 'VARON',
            'direccion' => 'AV. SIEMPRE MUERTA 123',
            'historial_medico' => 'SIN ANTECEDENTES',
            'user_id' => $user->id,
        ]);
    }
}
