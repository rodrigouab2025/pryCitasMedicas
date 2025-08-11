<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'fecha',
        'fecha_inicio',
        'fecha_final',
        'razon',
        'estado',
        'horario_id',
        'paciente_id',
    ];
}
