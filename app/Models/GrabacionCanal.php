<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrabacionCanal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'canal_index',
        'estado',
        'archivo_actual',
        'resolucion',
    ];
}
