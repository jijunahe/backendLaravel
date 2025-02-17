<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'documento_identidad',
        'nombres',
        'apellidos',
        'email',
        'cargo',
        'fecha_nacimiento',
        'fecha_ingreso',
        'estado',
    ];
}
