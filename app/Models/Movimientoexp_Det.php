<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoExp_Det extends Model
{
    public $timestamps = false;
    protected $table = 'movimiento_exp_det'; 
    protected $fillable = [
        'id_movimiento',
        'nro_mov',
        'ano_mov', 
        'tipo_mov', 
        'id_expediente', 
        'nro_expediente', 
        'ano_expediente', 
        'id_dependencia', 
        'id_tipo', 
        'observacion', 
        'estado_mov', 
    ];
}
