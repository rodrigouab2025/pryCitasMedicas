<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilPaciente extends Model
{
    use HasFactory;

    protected $table = 'perfiles_pacientes';

    protected $fillable = [
        'nacimiento',
        'sexo',
        'direccion',
        'historial_medico',
        'user_id',
    ];
     public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }
}
