<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expedientes; 
use App\Models\UbicacionExp; 
use App\Models\Personal; 
use App\Models\Dependencia; 
use App\Models\ObsInventario; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function mostrarMenu()
    {
        return view('menu.index');
    }
    public function nuevoExpediente()
    {
        //$dependencias = Dependencia::orderBy('descripcion', 'asc')->get();
        //$dependencias = Dependencia::whereIn('id_dependencia', [34, 38, 42])
        $dependencias = Dependencia::where('inventario', 'S')
            ->orderBy('descripcion', 'asc')
            ->get();
        return view('inventario.reginventario', compact('dependencias'));
    }
    public function nuevoExpedientev2()
    {
        //$dependencias = Dependencia::orderBy('descripcion', 'asc')->get();
        //$dependencias = Dependencia::whereIn('id_dependencia', [34, 38, 42])
        $dependencias = Dependencia::where('inventario', 'S')
            ->orderBy('descripcion', 'asc')
            ->get();
        return view('inventario.reginventariov2', compact('dependencias'));
    }
    public function nuevoExpedientev3()
    {
        $dependencias = Dependencia::where('inventario', 'S')
            ->orderBy('descripcion', 'asc')
            ->get();
        return view('inventario.reginventariov3', compact('dependencias'));
    }







    public function buscarPorCodigo(Request $request)
    {    
        $nroinventario = $request->input('nroinventario');
        $registros = DB::table('ubicacion_exp')
            ->where('nro_inventario', $nroinventario)
            ->leftJoin('expediente', 'ubicacion_exp.id_expediente', '=', 'expediente.id_expediente')
            ->select('expediente.codbarras','ubicacion_exp.id_dependencia','ubicacion_exp.ano_expediente',
            'ubicacion_exp.acompanados','ubicacion_exp.cuadernos',
            'ubicacion_exp.nro_expediente','ubicacion_exp.id_tipo','ubicacion_exp.estado',
            'ubicacion_exp.fecha_lectura','ubicacion_exp.hora_lectura','ubicacion_exp.fecha_inventario','ubicacion_exp.hora_inventario',
            'ubicacion_exp.id_usuario','ubicacion_exp.archivo','ubicacion_exp.anaquel','ubicacion_exp.nro_paquete','ubicacion_exp.serie','ubicacion_exp.paq_dependencia','ubicacion_exp.despacho','ubicacion_exp.tomo')
            ->get();

        if ($registros->isNotEmpty()) {
            $estado = $registros[0]->estado;
            $paq_dependencia = $registros[0]->paq_dependencia;
            $despacho = $registros[0]->despacho;
            //$id_expediente = $registros[0]->id_expediente;
            //$ubiexpe = UbicacionExp::where('id_expediente', $id_expediente)->first();
            //$paq_dependencia = $ubiexpe->paq_dependencia;
            //$despacho = $ubiexpe->despacho;

            $mens="";
            if ($estado=="I") {

                $existe = DB::table('inventario_reactiva')
                ->where('nro_inventario', $nroinventario)
                ->where('activo', 'S')
                ->first();
                if ($existe) {
                    $estado="A";
                    $mens="ACTIVADO";
                } else {
                    $mens="Este Nro de Inventario YA FUE REGISTRADO";
                }
            } else {
                if ((Auth::user()->id_usuario) != ($registros[0]->id_usuario)) {
                    $mens="Este Nro de Inventario lo est&aacute; registrando OTRO USUARIO";
                    $estado = "O";
                }
            }

            // Devolver la respuesta con los registros
            return response()->json([
                'success' => true,
                'estado' => $estado,
                'paq_dependencia' => $paq_dependencia,
                'despacho' => $despacho,
                'message' => utf8_encode($mens),
                'registros' => $registros,
            ]);
            
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros para el nro de inventario proporcionado.',
            ]);
        }
    }
    
    public function grabalecturaExpediente(Request $request)
    {
        $request->validate([
            'nroinventario' => 'required|string',
            'archivo' => 'required|numeric',
            'nropaquete' => 'required|string',
            'dependencia' => 'required|string',
            'despacho' => 'required|numeric',
            'codbarras' => 'required|string',
        ]);

        $codbar = $request->input('codbarras');
        $existe = DB::table('expediente')->where('codbarras', $codbar)->first();
        $dep_exp=substr($codbar,0,11);
        $ano_exp=substr($codbar,11,4);
        $nro_exp=substr($codbar,15,6);
        $tip_exp=substr($codbar,21,4);
        $dep_exp = (int) $dep_exp; 
        $tomo = $request->input('tomo');
        $idexpe = 0;
        if ($existe) {
            $idexpe = $existe->id_expediente; 
            $ubitomo = DB::table('ubicacion_exp')
            ->where('id_expediente', $idexpe)
            ->where('tomo', $tomo)
            ->first();
        }
            
        //$fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);

        if ($existe) {
            if ($ubitomo) {            
                return response()->json([
                    'success' => false,
                    'message' => utf8_encode('El expediente ' . $codbar . ' ya est&aacute; registrado en la base de datos.')
                ]);        
            }
        } 

        try {
            DB::transaction(function () use (
                $existe, $idexpe, $codbar, $nro_exp, $ano_exp, $dep_exp, $tip_exp, $fechaActual, $horaActual,
                $tomo, $anoActual, $request, &$idExpediente
            ) {

                if ($existe) {
                    $idExpediente = $idexpe;
                } else {
                    $idExpediente = DB::table('expediente')->insertGetId([
                        'codbarras' => $codbar,
                        'nro_expediente' => $nro_exp,
                        'ano_expediente' => $ano_exp,
                        'id_dependencia' => $dep_exp,
                        'id_tipo' => $tip_exp,
                        'fecha_ingreso' => $fechaActual,
                        'hora_ingreso' => $horaActual,
                        'estado' => 'L',
                        'fecha_lectura' => $fechaActual,
                        'hora_lectura' => $horaActual,
                        'id_personal' => Auth::user()->id_personal,
                        'id_usuario' => Auth::user()->id_usuario,
                    ]);
                }

                $ultimoRegistro = DB::table('ubicacion_exp')
                    ->where('ano_movimiento', $anoActual)
                    ->orderBy('nro_movimiento', 'desc')
                    ->first();

                $nromov = $ultimoRegistro ? $ultimoRegistro->nro_movimiento + 1 : 1;

                DB::table('ubicacion_exp')->insert([
                    'nro_movimiento' => $nromov,
                    'ano_movimiento' => $anoActual,
                    'id_personal' => Auth::user()->id_personal,
                    'id_usuario' => Auth::user()->id_usuario,
                    'archivo' => $request->archivo,
                    'anaquel' => $request->anaquel,
                    'nro_paquete' => $request->nropaquete,
                    'serie' => $request->serie,
                    'nro_inventario' => $request->nroinventario,
                    'id_expediente' => $idExpediente,

                    'nro_expediente' => $nro_exp,
                    'ano_expediente' => $ano_exp,
                    'id_dependencia' => $dep_exp,
                    'id_tipo' => $tip_exp,
                    'ubicacion' => 'A',             //A=Archivo D=Despacho
                    'tipo_ubicacion' => 'T',        //I=Inventario T=Transito
                    'fecha_movimiento' => $fechaActual,
                    'hora_movimiento' => $horaActual,
                    'motivo_movimiento' => 'Inventario',                
                    'paq_dependencia' => $request->dependencia,
                    'despacho' => $request->despacho,
                    'activo' => 'S',
                    'estado' => 'L',
                    'fecha_lectura' => $fechaActual,
                    'hora_lectura' => $horaActual,
                    'tomo' => $tomo,
                    'acompanados' => ($request->has('checkacomp') ? 'S' : 'N') ,
                    'cuadernos' => ($request->has('checkcuade') ? 'S' : 'N') ,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Expediente ha guardado la Lectura.',
                'fechalect' => $fechaActual,
                'horalect' => $horaActual
            ]);        
	    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'HUBO UN ERROR AL GUARDAR LA LECTURA, INTENTELO NUEVAMENTE : ' . $e->getMessage()
            ]);
        }


        //return redirect()->back()->with('messageOK', 'Registros guardados exitosamente');
    }

    public function grabainventarioExpediente(Request $request)
    {
        $request->validate([
            'nroinventarioobs' => 'required|string',
            'observacion' => 'nullable|string',        
            'scannedItems' => 'required|json', // Validamos que sea un JSON
        ]);
        //$fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);

        // Convertir el JSON a un array
        $scannedItems = json_decode($request->scannedItems, true);

        DB::beginTransaction();
        try {
                    
            if (trim($request->observacion) !="" ) {
                ObsInventario::create([
                'nro_inventario' => $request->nroinventarioobs,
                'observacion' => $request->observacion,
                ]);
            }
                DB::table('inventario_reactiva')
                ->where('nro_inventario', $request->nroinventarioobs)
                ->where('activo', 'S')
                ->update(['activo' => 'N']);
	
            foreach ($scannedItems as $item) {
                $registro = Expedientes::where('codbarras', $item['codbarras'])->first();
                $idExpediente = $registro->id_expediente; 
                $registro = DB::table('ubicacion_exp')
                    ->where('id_expediente', $idExpediente)
                    ->where('tomo', $item['tomo'])->first();
                $estado = $registro->estado; 
                
                if ($estado=="L") {
                    DB::table('expediente')
                    ->where('codbarras', $item['codbarras'])
                    ->update([
                        'estado' => 'I',
                        'fecha_inventario' => $fechaActual,
                        'hora_inventario' => $horaActual
                    ]);
                    DB::table('ubicacion_exp')
                    ->where('id_expediente', $idExpediente)
                    ->where('tomo', $item['tomo'])
                    ->update([
                        'estado' => 'I',
                        'ubicacion' => 'A',             //A=Archivo D=Despacho
                        'tipo_ubicacion' => 'I',         //I=Inventario T=Transito
                        'fecha_inventario' => $fechaActual,
                        'hora_inventario' => $horaActual
                    ]);
                }
            }
            DB::commit(); // Confirmar la transacción

            return redirect()->back()->with('messageOK', 'Registros Inventariados exitosamente');

        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción

            return redirect()->back()->with('messageErr', 'HUBO UN ERROR AL GUARDAR, INTENTELO NUEVAMENTE: ' . $e->getMessage());
        }

    }

    public function eliminarItem(Request $request)
    {    
        $codbarras = $request->input('codbarras');
        $tomo = $request->input('tomo');

        try {
            DB::beginTransaction(); // ⬅️ Inicia la transacción

            $registro = DB::table('expediente')->where('codbarras', $codbarras)->first();
            if (!$registro) {
                return response()->json(['error' => 'No se encontró el item'], 404);
            }            
            $idExpediente = $registro->id_expediente; 
            DB::table('ubicacion_exp')
            ->where('id_expediente', $idExpediente)
            ->where('tomo', $tomo)
            ->delete();

            $existe = DB::table('ubicacion_exp')->where('id_expediente', $idExpediente)->exists();
            if (!$existe) {
                DB::table('expediente')->where('codbarras', $codbarras)->delete();
            }
            DB::commit(); // ⬅️ Confirma los cambios
            return response()->json(['success' => true]);            
        } catch (\Exception $e) {
            DB::rollBack(); // ⬅️ Revierte si hay error
            return response()->json([
                'error' => 'Error al eliminar el item',
                'detalle' => $e->getMessage()
            ], 500);
        }

    }





    public function seguimientoInventario()
    {
        $usuario = auth()->user();
        if ($usuario->perfil->descri_perfil === 'Inventario') {
            $segdatos = DB::table('ubicacion_exp')
            ->leftJoin('expediente', 'ubicacion_exp.id_expediente', '=', 'expediente.id_expediente')
            ->leftJoin('usuarios', 'ubicacion_exp.id_usuario', '=', 'usuarios.id_usuario')
            ->leftJoin('dependencia', 'ubicacion_exp.paq_dependencia', '=', 'dependencia.id_dependencia')
            ->select('usuario', 'nro_inventario', 'archivo', 'anaquel', 'nro_paquete','serie', 'paq_dependencia', 'descripcion', 'despacho', DB::raw('count(*) as total'), DB::raw('MAX(expediente.id_expediente) as id_maximo'), DB::raw('MIN(ubicacion_exp.fecha_inventario) as fecha_inv'))
            ->where('ubicacion_exp.id_usuario', $usuario->id_usuario)  // filtro para solo devolver lo del usuario
            ->where('ubicacion_exp.nro_inventario','<>', '')  // filtro para solo devolver lo del usuario
            ->groupBy('usuario', 'nro_inventario', 'archivo', 'anaquel', 'nro_paquete','serie', 'paq_dependencia', 'descripcion', 'despacho')
            ->orderBy('id_maximo', 'desc')           
            ->get();
        } else {
            $segdatos = DB::table('ubicacion_exp')
            ->leftJoin('expediente', 'ubicacion_exp.id_expediente', '=', 'expediente.id_expediente')
            ->leftJoin('usuarios', 'ubicacion_exp.id_usuario', '=', 'usuarios.id_usuario')
            ->leftJoin('dependencia', 'ubicacion_exp.paq_dependencia', '=', 'dependencia.id_dependencia')
            ->where('ubicacion_exp.nro_inventario','<>', '')  // filtro para solo devolver lo del usuario
            ->select('usuario', 'nro_inventario', 'archivo', 'anaquel', 'nro_paquete','serie', 'paq_dependencia', 'descripcion', 'despacho', DB::raw('count(*) as total'), DB::raw('MAX(expediente.id_expediente) as id_maximo'), DB::raw('MIN(ubicacion_exp.fecha_inventario) as fecha_inv'))
            ->groupBy('usuario', 'nro_inventario', 'archivo', 'anaquel', 'nro_paquete','serie', 'paq_dependencia', 'descripcion', 'despacho')
            ->orderBy('id_maximo', 'desc')           
            ->get();
        }
        return view('inventario.seginventario', compact('segdatos'));
    }
    public function mostrarDetalle(Request $request)
    {
        $nroinventario = $request->input('nroinventario');    
        $segdetalle = DB::table('ubicacion_exp')
            ->where('nro_inventario', $nroinventario)
            ->leftJoin('expediente', 'ubicacion_exp.id_expediente', '=', 'expediente.id_expediente')
            ->select('expediente.codbarras','ubicacion_exp.id_dependencia','ubicacion_exp.ano_expediente',
            'ubicacion_exp.nro_expediente','ubicacion_exp.id_tipo','ubicacion_exp.estado',
            'ubicacion_exp.fecha_lectura','ubicacion_exp.hora_lectura','ubicacion_exp.fecha_inventario','ubicacion_exp.hora_inventario',
            'ubicacion_exp.id_usuario','ubicacion_exp.archivo','ubicacion_exp.anaquel','ubicacion_exp.nro_paquete','ubicacion_exp.paq_dependencia','ubicacion_exp.despacho','ubicacion_exp.tomo')
            ->get();

        if ($segdetalle->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'registros' => $segdetalle,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros para el nro de inventario proporcionado.',
            ]);
        }
    }

}
