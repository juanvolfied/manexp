<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoExp_Cab extends Model
{
    public $timestamps = false;
    protected $table = 'movimiento_exp_cab'; 
    protected $fillable = [
        'nro_mov',
        'ano_mov', 
        'tipo_mov', 
        'id_usuario', 
        'fiscal', 
        'fechahora_movimiento', 
        'fechahora_envio', 
        'fechahora_recepcion', 
        'estado_mov', 
        'activo', 
    ];
}
