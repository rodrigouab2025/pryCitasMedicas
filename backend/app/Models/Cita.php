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
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }
    public function medico()
    {
        return $this->hasOneThrough(
            PerfilMedico::class,  
            Horario::class,      
            'id', 
            'id', 
            'horario_id', 
            'medico_id'
        );
    }
}
