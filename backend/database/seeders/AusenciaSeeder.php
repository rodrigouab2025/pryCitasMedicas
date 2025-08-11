<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ausencia;
use App\Models\PerfilDoctor;

class AusenciaSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos el primer doctor existente
        $doctor = PerfilDoctor::first();

        if ($doctor) {
            Ausencia::create([
                'fecha_inicio'  => '2025-08-15',
                'fecha_final'   => '2025-08-20',
                'tiempo_inicio' => '08:00',
                'tiempo_final'  => '12:00',
                'razon'         => 'VACACIONES',
                'doctor_id'     => $doctor->id,
            ]);

            Ausencia::create([
                'fecha_inicio'  => '2025-09-01',
                'fecha_final'   => '2025-09-01',
                'tiempo_inicio' => '14:00',
                'tiempo_final'  => '18:00',
                'razon'         => 'CONSULTA PERSONAL',
                'doctor_id'     => $doctor->id,
            ]);
        }
    }
}