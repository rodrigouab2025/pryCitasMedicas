<?php

namespace Database\Seeders;

use App\Models\Especialidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EspecialidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Especialidad::create([
            'nombre' => 'MEDICINA GENERAL',
            'descripcion' => 'ATENCIÓN MÉDICA GENERAL',
        ]);

        Especialidad::create([
            'nombre' => 'ODONTOLOGÍA',
            'descripcion' => 'CUIDADO DENTAL Y BUCAL',
        ]);

        Especialidad::create([
            'nombre' => 'PEDIATRÍA',
            'descripcion' => 'ATENCIÓN MÉDICA A NIÑOS Y ADOLESCENTES',
        ]);
    }
}
