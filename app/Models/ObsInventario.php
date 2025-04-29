<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObsInventario extends Model
{
    protected $table = 'observacion_inventario'; 
    public $timestamps = false;

    protected $fillable = [
        'nro_inventario',
        'observacion', 
    ];
}
