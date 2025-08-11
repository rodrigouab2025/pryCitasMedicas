<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilDoctor extends Model
{
    use HasFactory;

    protected $table = 'perfiles_doctores';

    protected $fillable = [
        'reg_profesional',
        'biografia',
        'especialidad_id',
        'user_id',
    ];
}