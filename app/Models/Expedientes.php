<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedientes extends Model
{
    use HasFactory;
    
    protected $table = 'expediente';
    public $timestamps = false;    

    // Los campos que se pueden asignar masivamente (por seguridad)
    protected $fillable = [
        'id_expediente',
        'codbarras',
        'nro_expediente',
        'ano_expediente',
        'id_dependencia',
        'id_tipo',
        'fecha_ingreso',
        'hora_ingreso',
        'imputado',
        'agraviado',
        'delito',
        'nro_oficio',
        'nro_folios',
        'estado',
        'fecha_lectura',
        'hora_lectura',
        'fecha_inventario',
        'hora_inventario',
        'nro_inventario',
        'archivo',
        'nro_paquete',
        'paq_dependencia',
        'despacho',
        'id_personal',
        'id_usuario'
    ];

}
