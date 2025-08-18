<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'fecha',
        'tiempo_inicio',
        'tiempo_final',
        'razon',
        'estado',
        'horario_id',
        'paciente_id',
    ];
     public function paciente()
    {
        return $this->belongsTo(PerfilPaciente::class, 'paciente_id');
    }
}
