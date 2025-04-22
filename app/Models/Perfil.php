<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuarios; 

class Perfil extends Model
{
    protected $table = 'perfil'; // esto es lo que arregla el problema

    protected $fillable = ['id_perfil', 'descri_perfil', 'activo'];
    public function usuarios()
    {
        return $this->belongsToMany(Usuarios::class);
    }
}
