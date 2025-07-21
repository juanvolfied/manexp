<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepPolicial extends Model
{
//    protected $table = 'personal'; 
    public $timestamps = false;

    protected $table = 'dependenciapolicial'; // opcional si el nombre sigue convenci�n
    protected $primaryKey = 'id_deppolicial'; // importante
    //public $incrementing = false; // porque es CHAR(8), no int
    //protected $keyType = 'string'; // porque es CHAR, no INT

    protected $fillable = [
        'descripciondep', 
    ];
}
