<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cita;
use App\Models\Horario;
use App\Models\PerfilPaciente;

class CitaSeeder extends Seeder
{
    public function run(): void
    {
        $horario = Horario::first();
        $paciente = PerfilPaciente::first();

        if ($horario && $paciente) {
            Cita::create([
                'fecha'         => '2025-08-15',
                'tiempo_inicio'  => '2025-08-15 08:00:00',
                'tiempo_final'   => '2025-08-15 08:30:00',
                'razon'         => 'CONTROL DE RUTINA',
                'estado'        => 'S',
                'horario_id'    => $horario->id,
                'paciente_id'   => $paciente->id,
            ]);

            Cita::create([
                'fecha'         => '2025-08-15',
                'tiempo_inicio'  => '2025-08-15 08:30:00',
                'tiempo_final'   => '2025-08-15 09:00:00',
                'razon'         => 'CONSULTA GENERAL',
                'estado'        => 'S',
                'horario_id'    => $horario->id,
                'paciente_id'   => $paciente->id,
            ]);
        }
    }
}
