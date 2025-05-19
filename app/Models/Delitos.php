<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delitos extends Model
{
    protected $table = 'delito'; // esto es lo que arregla el problema

    protected $fillable = [
        'id_delito', 
        'desc_delito', 
    ];
}
