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
    public function medico()
    {
        return $this->belongsTo(PerfilMedico::class, 'medico_id');
    }
    public function citas()
    {
        return $this->hasMany(Cita::class, 'horario_id');
    }
}
