<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilMedico extends Model
{
    use HasFactory;

    protected $table = 'perfiles_medicos';

    protected $fillable = [
        'reg_profesional',
        'biografia',
        'especialidad_id',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'medico_id');
    }
}