<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    protected $table = 'dependencia'; 
    public $timestamps = false;

    protected $fillable = [
        'id_dependencia',
        'codigo_siga', 
        'descripcion', 
        'abreviado', 
        'activo', 
    ];
}
