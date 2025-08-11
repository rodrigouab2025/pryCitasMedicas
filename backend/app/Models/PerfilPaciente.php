<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilPaciente extends Model
{
    //
    protected $table = 'perfiles_pacientes';

    protected $fillable = [
        'nacimiento',
        'sexo',
        'direccion',
        'historial_medico',
        'user_id',
    ];
}
