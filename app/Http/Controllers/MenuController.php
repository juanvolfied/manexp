<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expedientes; 
use App\Models\UbicacionExp; 
use App\Models\Personal; 
use App\Models\Dependencia; 
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
        $dependencias = Dependencia::orderBy('descripcion', 'asc')->get();
        return view('inventario.reginventario', compact('dependencias'));
    }







    public function buscarPorCodigo(Request $request)
    {    
        $nroinventario = $request->input('nroinventario');
        $registros = Expedientes::where('nro_inventario', $nroinventario)->get();
        if ($registros->isNotEmpty()) {
            $estado = $registros[0]->estado;

            // Devolver la respuesta con los registros
            return response()->json([
                'success' => true,
                'estado' => $estado,
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
    //dd($request->all());  // Muestra los datos que se están enviando
        // Validar los datos
        $request->validate([
            'nroinventario' => 'required|string',
            'archivo' => 'required|numeric',
            'nropaquete' => 'required|string',
            'dependencia' => 'required|string',
            'despacho' => 'required|numeric',
            'codbarras' => 'required|string',
        ]);

        $codbar = $request->input('codbarras');

        // Buscar si el texto ya existe en la base de datos
        $existe = Expedientes::where('codbarras', $codbar)->exists();
	$dep_exp=substr($codbar,0,11);
	$ano_exp=substr($codbar,11,4);
	$nro_exp=substr($codbar,15,6);
	$tip_exp=substr($codbar,21,4);
        
	//$fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
	$fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
	$horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
	$anoActual = substr($fechaActual,0,4);

        if ($existe) {
            // Si el texto ya existe, mostrar mensaje de error
            //return redirect()->back()->with('messageErr', utf8_encode('El expediente ' . $codbar . ' ya está registrado en la base de datos.') );
            return response()->json([
                'success' => false,
                'message' => utf8_encode('El expediente ' . $codbar . ' ya está registrado en la base de datos.')
            ]);        
        } else {
            // Si no existe, guardarlo
            $registro = Expedientes::create([
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
                'nro_inventario' => $request->nroinventario,
                'archivo' => $request->archivo,
                'nro_paquete' => $request->nropaquete,
                'paq_dependencia' => $request->dependencia,
                'despacho' => $request->despacho,
                'id_personal' => Auth::user()->id_personal,
                'id_usuario' => Auth::user()->id_usuario,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Expediente ha guardado la Lectura.',
                'fechalect' => $fechaActual,
                'horalect' => $horaActual
            ]);        
	}
        //return redirect()->back()->with('messageOK', 'Registros guardados exitosamente');
    }

    public function grabainventarioExpediente(Request $request)
    {
        //dd($request->all());  // Muestra los datos que se están enviando

        // Validar los datos
        $request->validate([
            'scannedItems' => 'required|json', // Validamos que sea un JSON
        ]);
	//$fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
	$fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
	$horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
	$anoActual = substr($fechaActual,0,4);

        // Convertir el JSON a un array
        $scannedItems = json_decode($request->scannedItems, true);
	
        // Guardar cada registro de código interno e indicador
        foreach ($scannedItems as $item) {
            $registro = Expedientes::where('codbarras', $item['codbarras'])->first();
            $idExpediente = $registro->id_expediente; 
            $estado = $registro->estado; 
            
            if ($estado=="L") {
                DB::table('expediente')
                ->where('codbarras', $item['codbarras'])
                ->update([
                    'estado' => 'I',
                    'fecha_inventario' => $fechaActual,
                    'hora_inventario' => $horaActual
                ]);

                $dep_exp=substr($item['codbarras'],0,11);
                $ano_exp=substr($item['codbarras'],11,4);
                $nro_exp=substr($item['codbarras'],15,6);
                $tip_exp=substr($item['codbarras'],21,4);

                $ultimoRegistro = UbicacionExp::where('ano_movimiento', $anoActual)
	    	      ->orderBy('ano_movimiento', 'desc')
                      ->orderBy('nro_movimiento', 'desc')
                      ->first();
                $nromov=0;
                if ($ultimoRegistro) {
                    $nromov = $ultimoRegistro->nro_movimiento;
                }
                $nromov++;
                UbicacionExp::create([
                'nro_movimiento' => $nromov,
                'ano_movimiento' => $anoActual,
                'id_personal' => '',
                'id_expediente' => $idExpediente,
                'nro_expediente' => $nro_exp,
                'ano_expediente' => $ano_exp,
                'id_dependencia' => $dep_exp,
                'id_tipo' => $tip_exp,
                'fecha_movimiento' => $fechaActual,
                'hora_movimiento' => $horaActual,
                'motivo_movimiento' => 'Inventario',                
                ]);
            }
        }

        return redirect()->back()->with('messageOK', 'Registros Inventariados exitosamente');
    }
    public function eliminarItem(Request $request)
    {    
        $codbarras = $request->input('codbarras');
        $registro = Expedientes::where('codbarras', $codbarras)->first();
        if ($registro) {
            $idExpediente = $registro->id_expediente; 
            DB::table('expediente')->where('codbarras', $codbarras)->delete();
            DB::table('ubicacion_exp')->where('id_expediente', $idExpediente)->delete();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'No se encontró el ítem'], 404);
        }
    }





    public function seguimientoInventario()
    {
        $usuario = auth()->user();
        if ($usuario->perfil->descri_perfil !== 'Admin') {
            $segdatos = DB::table('expediente')
            ->leftJoin('usuarios', 'expediente.id_usuario', '=', 'usuarios.id_usuario')
            ->leftJoin('dependencia', 'expediente.paq_dependencia', '=', 'dependencia.id_dependencia')
            ->select('usuario', 'nro_inventario', 'archivo', 'nro_paquete', 'paq_dependencia', 'descripcion', 'despacho', DB::raw('count(*) as total'), DB::raw('MAX(id_expediente) as id_maximo'), DB::raw('MIN(fecha_inventario) as fecha_inv'))
            ->where('expediente.id_usuario', $usuario->id_usuario)  // filtro para solo devolver lo del usuario
            ->groupBy('usuario', 'nro_inventario', 'archivo', 'nro_paquete', 'paq_dependencia', 'descripcion', 'despacho')
            ->orderBy('id_maximo', 'desc')           
            ->get();
        } else {
            $segdatos = DB::table('expediente')
            ->leftJoin('usuarios', 'expediente.id_usuario', '=', 'usuarios.id_usuario')
            ->leftJoin('dependencia', 'expediente.paq_dependencia', '=', 'dependencia.id_dependencia')
            ->select('usuario', 'nro_inventario', 'archivo', 'nro_paquete', 'paq_dependencia', 'descripcion', 'despacho', DB::raw('count(*) as total'), DB::raw('MAX(id_expediente) as id_maximo'), DB::raw('MIN(fecha_inventario) as fecha_inv'))
            ->groupBy('usuario', 'nro_inventario', 'archivo', 'nro_paquete', 'paq_dependencia', 'descripcion', 'despacho')
            ->orderBy('id_maximo', 'desc')           
            ->get();
        }
        return view('inventario.seginventario', compact('segdatos'));
    }
    public function mostrarDetalle(Request $request)
    {
        $nroinventario = $request->input('nroinventario');    
        $segdetalle = Expedientes::where('nro_inventario', $nroinventario)->get();
//        return view('inventario.detalleseginventario', compact('segdetalle'));

        if ($segdetalle->isNotEmpty()) {
            // Devolver la respuesta con los registros
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
