<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'dia_semana',
        'fecha_inicio',
        'fecha_final',
        'duracion_cita',
        'estado',
        'doctor_id',
    ];
}
