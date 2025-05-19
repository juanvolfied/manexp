<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionExp extends Model
{
    use HasFactory;
    
    protected $table = 'ubicacion_exp';
    public $timestamps = false;    

    // Los campos que se pueden asignar masivamente (por seguridad)
    protected $fillable = [
        'nro_movimiento',
        'ano_movimiento',
        'id_personal',
        'id_usuario',
        'archivo',
        'anaquel',
        'nro_paquete',
        'nro_inventario',
        'id_expediente',
        'nro_expediente',
        'ano_expediente',
        'id_dependencia',
        'id_tipo',
        'ubicacion',
        'tipo_ubicacion',
        'fecha_movimiento',
        'hora_movimiento',
        'motivo_movimiento',
        'activo',
        'estado',
        'paq_dependencia',
        'despacho'
    ];
}	