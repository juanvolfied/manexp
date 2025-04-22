<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Personal; 
use App\Models\Perfil; 
use App\Models\PerfilUsuario;

class Usuarios extends Authenticatable
{    
    protected $table = 'usuarios';
    public $timestamps = false;
    protected $primaryKey = 'id_usuario'; // CLAVE IMPORTANTE
    public $incrementing = false; // Si NO es autoincremental
    protected $keyType = 'string'; // Si es tipo char/varchar

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

    // Relación con perfil_usuario
    public function perfilUsuario()
    {
        return $this->hasOne(PerfilUsuario::class, 'id_usuario', 'id_usuario');
    }

    public function perfil()
    {
        return $this->hasOneThrough(
            Perfil::class,
            PerfilUsuario::class,
            'id_usuario', // FK en perfil_usuario
            'id_perfil',  // FK en perfil
            'id_usuario', // Local key en Usuarios
            'id_perfil'   // Local key en PerfilUsuario
        );
    }

    // Verificar perfil (rol)
    public function hasRole($rol)
    {
        return optional($this->perfil)->nombre === $rol; // O "name" si tu campo se llama así
    }
}
