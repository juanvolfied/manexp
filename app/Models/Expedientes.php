<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Delitos; 

class Expedientes extends Model
{
    use HasFactory;
    
    protected $table = 'expediente';
    public $timestamps = false;    

    protected $primaryKey = 'id_expediente';
    public $incrementing = true; // es autoincremental
    protected $keyType = 'int'; 

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
        'id_personal',
        'id_usuario'
    ];
    public function delito()
    {
        return $this->belongsTo(Delitos::class);
    }
}
