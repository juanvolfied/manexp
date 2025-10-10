<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expedientes; 
use App\Models\UbicacionExp; 
use App\Models\Dependencia; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class MantenimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function mostrarReactivacion()
    {
        //$dependencias = Dependencia::orderBy('descripcion', 'asc')->get();
        $dependencias = Dependencia::whereIn('id_dependencia', [34, 38, 42])
            ->orderBy('descripcion', 'asc')
            ->get();
        return view('mantenimiento.reactivainventario', compact('dependencias'));
    }

    public function buscarPorCodigo(Request $request)
    {    
        $nroinventario = $request->input('nroinventario');
        $registros = DB::table('ubicacion_exp')
            ->where('nro_inventario', $nroinventario)
            ->leftJoin('expediente', 'ubicacion_exp.id_expediente', '=', 'expediente.id_expediente')
            ->select('expediente.codbarras','ubicacion_exp.id_dependencia','ubicacion_exp.ano_expediente',
            'ubicacion_exp.nro_expediente','ubicacion_exp.id_tipo','ubicacion_exp.estado',
            'expediente.fecha_lectura','expediente.hora_lectura','expediente.fecha_inventario','expediente.hora_inventario',
            'ubicacion_exp.id_usuario','ubicacion_exp.archivo','ubicacion_exp.anaquel','ubicacion_exp.nro_paquete','ubicacion_exp.paq_dependencia','ubicacion_exp.despacho')
            ->get();

        if ($registros->isNotEmpty()) {
            $estado = $registros[0]->estado;
            $paq_dependencia = $registros[0]->paq_dependencia;
            $despacho = $registros[0]->despacho;
            
            $dep = Dependencia::where('id_dependencia',$paq_dependencia)
            ->orderBy('descripcion', 'asc')->first();
            $descdependencia = $dep->descripcion;


            $mens="";
            if ($estado=="I") {
                $existe = DB::table('inventario_reactiva')
                    ->where('nro_inventario', $nroinventario)
                    ->where('activo', 'S')
                    ->first();
                if ($existe) {
                    $mens="EL NRO DE INVENTARIO ". $nroinventario ." YA TIENE UN PERMISO DE REACTIVACION";
                    $estado = "A";
                } 
            } else {
                //if ((Auth::user()->id_usuario) != ($registros[0]->id_usuario)) {
                    $mens="EL NRO DE INVENTARIO ". $nroinventario ." ESTA ACTIVO Y REGISTRANDOSE";
                    $estado = "O";
                //}
            }

            // Devolver la respuesta con los registros
            return response()->json([
                'success' => true,
                'estado' => $estado,
                'paq_dependencia' => $paq_dependencia,
                'despacho' => $despacho,
                'des_dependencia' => $descdependencia,
                'message' => utf8_encode($mens),
                'registros' => $registros,
            ]);
            
        } else {
            return response()->json([
                'success' => false,
                'message' => 'NO SE ENCONTRO EL NRO DE INVENTARIO PROPORCIONADO.',
            ]);
        }
    }
    
    public function grabaReactivacion(Request $request)
    {
        $nroinventario = $request->input('nroinventarioact');
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        DB::table('inventario_reactiva')->insert([
            'nro_inventario' => $request->nroinventarioact,
            'fechahora_permiso' => $fechaHoraActualFormateada,
            'activo' => 'S',
        ]);
/*        return response()->json([
            'success' => true,
            'message' => 'SE HA REACTIVADO EL NRO DE INVENTARIO ' . $nroinventario . ' DE MANERA SATISFACTORIA.'
        ]);*/
        return redirect()->route('reactivainventario')->with('success', 'SE HA REACTIVADO EL NRO DE INVENTARIO ' . $nroinventario . ' DE MANERA SATISFACTORIA.');

    }





    public function mostrarDependencias()
    {
        $dependencias = DB::table('dependencia')
            ->orderBy('descripcion', 'asc')
            ->get();
        return view('mantenimiento.seleccionadependenciasinv', compact('dependencias'));
    }
    public function cambiaEstadoDependencia($id, Request $request)
    {
        //$producto = Producto::findOrFail($id);
        $nuevoEstado = $request->input('estado') === 'S' ? 'S' : 'N';
        //$producto->mostrarinventario = $nuevoEstado;
        //$producto->save();
        DB::table('dependencia')
        ->where('id_dependencia', $id)
        ->update(['inventario' => $nuevoEstado]);
        return response()->json(['success' => true, 'estado' => $nuevoEstado]);
    }

    public function mostrarDependenciasSGF()
    {
        $dependencias = DB::table('dependencia')
            ->orderBy('descripcion', 'asc')
            ->get();
        return view('mantenimiento.seleccionadependenciassgf', compact('dependencias'));
    }
    public function cambiaEstadoDependenciaSGF($id, Request $request)
    {
        //$producto = Producto::findOrFail($id);
        $nuevoEstado = $request->input('estado') === 'S' ? 'S' : 'N';
        //$producto->mostrarinventario = $nuevoEstado;
        //$producto->save();
        DB::table('dependencia')
        ->where('id_dependencia', $id)
        ->update(['mostrarsgf' => $nuevoEstado]);
        return response()->json(['success' => true, 'estado' => $nuevoEstado]);
    }    
}
