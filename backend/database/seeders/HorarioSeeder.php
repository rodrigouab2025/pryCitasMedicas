<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;
use App\Models\PerfilDoctor;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $doctor = PerfilDoctor::first();

        if ($doctor) {
            Horario::create([
                'dia_semana'   => 1,
                'tiempo_inicio' => '2025-08-11 08:00:00',
                'tiempo_final'  => '2025-08-11 12:00:00',
                'duracion_cita' => 30, 
                'doctor_id'    => $doctor->id,
            ]);
            Horario::create([
                'dia_semana'   => 3,
                'tiempo_inicio' => '2025-08-13 14:00:00',
                'tiempo_final'  => '2025-08-13 18:00:00',
                'duracion_cita' => 20, 
                'doctor_id'    => $doctor->id,
            ]);
        }
    }
}
