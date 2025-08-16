<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'dia_semana',
        'tiempo_inicio',
        'tiempo_final',
        'duracion_cita',
        'estado',
        'medico_id',
    ];
}
