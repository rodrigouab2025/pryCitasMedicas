<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ausencia extends Model
{
    protected $table = 'ausencias';

    protected $fillable = [
        'fecha_inicio',
        'fecha_final',
        'tiempo_inicio',
        'tiempo_final',
        'razon',
        'estado',
        'medico_id',
    ];
}
