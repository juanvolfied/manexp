<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuarios; 
use App\Models\Perfil; 

class PerfilUsuario extends Model
{
    protected $table = 'perfil_usuario'; // Laravel espera plural, así que lo forzás
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'id_usuario',
        'id_perfil',
        'activo',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }
}
