<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
//    protected $table = 'personal'; 
    public $timestamps = false;

    protected $table = 'personal'; // opcional si el nombre sigue convención
    protected $primaryKey = 'id_personal'; // importante
    public $incrementing = false; // porque es CHAR(8), no int
    protected $keyType = 'string'; // porque es CHAR, no INT

    protected $fillable = [
        'id_personal',
        'apellido_paterno', 
        'apellido_materno', 
        'nombres', 
        'activo', 
    ];
}
