<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personal'; 
    public $timestamps = false;

    protected $fillable = [
        'id_personal',
        'apellido_paterno', 
        'apellido_materno', 
        'nombres', 
        'activo', 
    ];
}
