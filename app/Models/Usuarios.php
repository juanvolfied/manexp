<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Personal; 

class Usuarios extends Authenticatable
{    
    protected $table = 'usuarios';

    protected $fillable = [
        'id_usuario',
        'id_personal',
        'usuario',
        'password',
        'activo'
    ];
    protected $hidden = [
        'password',
    ];

    public function getAuthIdentifierName()
    {
        return 'usuario';
    }
    // Relación con la tabla personal
    public function personal()
    {
        return $this->belongsTo(Personal::class, 'id_personal', 'id_personal');
    }
}
