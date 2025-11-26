<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }    
    public function indexConductor()
    {
        $conductores = DB::table('tra_conductores')
        ->select(
            'tra_conductores.*',
        )
        ->orderBy('apellido_paterno', 'asc')
        ->orderBy('apellido_materno', 'asc')
        ->orderBy('nombres', 'asc')
        ->get();
        return view('transporte.indexconductor', compact('conductores'));
    }

    public function createConductor()
    {
        //$dependencias = Dependencia::where('activo', 'S')
        //    ->orderBy('descripcion', 'asc')
        //    ->get();
        return view('transporte.createconductor');//, compact('dependencias')
    }

    public function storeConductor(Request $request)
    {
        $request->validate([
            'id_conductor' => 'required|size:8|unique:tra_conductores,id_conductor',
            'apellido_paterno' => 'required|max:30',
            'apellido_materno' => 'required|max:30',
            'nombres' => 'required|max:30',
            'activo' => 'required|in:S,N',
        ]);

        DB::table('tra_conductores')->insert([
            'id_conductor' => strtoupper( $request->input('id_conductor') ),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'nombres' => $request->input('nombres'),
            'nrolicencia' => $request->input('nrolicencia'),
            'categoria' => $request->input('categoria'),
            'activo' => $request->input('activo'),
        ]);

        return redirect()->route('transporte.indexconductor')->with('success', 'Registro creado correctamente.');
    }

    public function showConductor($conductor)
    {
        return view('transporte.showconductor', compact('conductor'));
    }

    public function editConductor($conductor)
    {
        $conductores = DB::table('tra_conductores')
        ->select(
            'tra_conductores.*',
        )
        ->where('id_conductor',$conductor)
        ->first();
        return view('transporte.editconductor', compact('conductores'));
    }

    public function updateConductor(Request $request, $conductor)
    {
        $request->validate([
            'apellido_paterno' => 'required|max:30',
            'apellido_materno' => 'required|max:30',
            'nombres' => 'required|max:30',
            'activo' => 'required|in:S,N',
        ]);

        DB::table('tra_conductores')
        ->where('id_conductor', $request->input('id_conductor'))
        ->update([
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'nombres' => $request->input('nombres'),
            'nrolicencia' => $request->input('nrolicencia'),
            'categoria' => $request->input('categoria'),
            'activo' => $request->input('activo'),
        ]);


        return redirect()->route('transporte.indexconductor')->with('success', 'Registro actualizado.');
    }

    public function destroyConductor($conductor)
    {
        $deleted = DB::table('tra_conductores')
        ->where('id_conductor', $conductor)
        ->delete();
        return redirect()->route('transporte.indexconductor')->with('success', 'Registro eliminado.');
    }



    

    public function indexVehiculo()
    {
        $vehiculos = DB::table('tra_vehiculos')
        ->select(
            'tra_vehiculos.*',
        )
        ->orderBy('nroplaca', 'asc')
        ->get();
        return view('transporte.indexvehiculo', compact('vehiculos'));
    }

    public function createVehiculo()
    {
        return view('transporte.createvehiculo');
    }

    public function storeVehiculo(Request $request)
    {
        $request->validate([
            'nroplaca' => 'required|size:7|unique:tra_vehiculos,nroplaca',
            'marca' => 'required|max:20',
            'modelo' => 'required|max:20',
            'color' => 'required|max:20',
            'activo' => 'required|in:S,N',
        ]);
        DB::table('tra_vehiculos')->insert([
            'nroplaca' => strtoupper( $request->input('nroplaca') ),
            'marca' => $request->input('marca'),
            'modelo' => $request->input('modelo'),
            'color' => $request->input('color'),
            'activo' => $request->input('activo'),
        ]);

        return redirect()->route('transporte.indexvehiculo')->with('success', 'Registro creado correctamente.');
    }

    public function showVehiculo($nroplaca)
    {
        return view('transporte.showvehiculo', compact('vehiculo'));
    }

    public function editVehiculo($nroplaca)
    {
        $vehiculos = DB::table('tra_vehiculos')
        ->select(
            'tra_vehiculos.*',
        )
        ->where('nroplaca',$nroplaca)
        ->first();
        return view('transporte.editvehiculo', compact('vehiculos'));
    }

    public function updateVehiculo(Request $request, $nroplaca)
    {
        $request->validate([
            'marca' => 'required|max:20',
            'modelo' => 'required|max:20',
            'color' => 'required|max:20',
            'activo' => 'required|in:S,N',
        ]);

        DB::table('tra_vehiculos')
        ->where('nroplaca', $request->input('nroplaca'))
        ->update([
            'marca' => $request->input('marca'),
            'modelo' => $request->input('modelo'),
            'color' => $request->input('color'),
            'activo' => $request->input('activo'),
        ]);
        return redirect()->route('transporte.indexvehiculo')->with('success', 'Registro actualizado.');
    }

    public function destroyVehiculo($nroplaca)
    {
        $deleted = DB::table('tra_vehiculos')
        ->where('nroplaca', $nroplaca)
        ->delete();
        return redirect()->route('transporte.indexvehiculo')->with('success', 'Registro eliminado.');
    }






    public function controlMovimiento()
    {
        $conductoressede = DB::table('tra_conductores')
            ->where('ensede', 'S')
            ->get();        
        $vehiculossede = DB::table('tra_vehiculos')
            ->where('ensede', 'S')
            ->get();        
        $conductoresdili = DB::table('tra_conductores')
            ->where('ensede', 'N')
            ->get();        
        $vehiculosdili = DB::table('tra_vehiculos')
            ->where('ensede', 'N')
            ->get();        
        return view('transporte.movimiento', compact('conductoressede', 'vehiculossede', 'conductoresdili', 'vehiculosdili'));
    }
    public function grabaMovimiento(Request $request)
    {
        $existe = DB::table('tra_conductores')
            ->where('id_conductor', $request->idco)
            ->exists();
        if (!$existe) {
            return response()->json([
                'success' => false,
                'message' => "EL CONDUCTOR CON ID {$request->idco} NO ESTA REGISTRADO.",
            ]);
        }        
        $existe = DB::table('tra_vehiculos')
            ->where('nroplaca', $request->plac)
            ->exists();
        if (!$existe) {
            return response()->json([
                'success' => false,
                'message' => "EL VEHICULO DE PLACA {$request->plac} NO ESTA REGISTRADO.",
            ]);
        }
        $datoidco = DB::table('tra_controlvehiculos')
            ->where('id_conductor', $request->idco)
            ->orderBy('id_movimiento', 'desc')
            ->first();
        if ($datoidco) {
            $tipo=$datoidco->tipo_mov;
            if ($request->input('tipo') == $tipo) {
                $tpmsg = $tipo=="I" ? "YA SE ENCUENTRA EN LA SEDE" : "YA SE ENCUENTRA EN UNA DILIGENCIA";
                return response()->json([
                    'success' => false,
                    'message' => "EL CONDUCTOR CON ID {$request->idco} {$tpmsg}.",
                ]);
            }
        }
        $datoplac = DB::table('tra_controlvehiculos')
            ->where('placa', $request->plac)
            ->orderBy('id_movimiento', 'desc')
            ->first();
        if ($datoplac) {
            $tipo=$datoplac->tipo_mov;
            if ($request->input('tipo') == $tipo) {
                $tpmsg = $tipo=="I" ? "YA SE ENCUENTRA EN LA SEDE" : "YA SE ENCUENTRA EN UNA DILIGENCIA";
                return response()->json([
                    'success' => false,
                    'message' => "EL VEHICULO DE PLACA {$request->plac} {$tpmsg}.",
                ]);
            }
        }



        try {

            DB::beginTransaction(); // ← INICIA LA TRANSACCIÓN
            
            $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
            DB::table('tra_controlvehiculos')
            ->insert([
                'fechahora_registro' => $fechaHoraActualFormateada,
                'tipo_mov' => $request->input('tipo'),
                'id_conductor' => $request->input('idco'),
                'placa' => $request->input('plac'),
                'kilometraje' => $request->input('kilo'),
                'observacion' => $request->input('obse'),
                'id_personal' => Auth::user()->id_personal,
            ]);
            DB::table('tra_conductores')
            ->where('id_conductor', $request->input('idco'))
            ->update([
                'ensede' => $request->input('tipo')=="I" ? "S" : "N"  ,
                'fechahora_ultimomov' => $fechaHoraActualFormateada,
            ]);
            DB::table('tra_vehiculos')
            ->where('nroplaca', $request->input('plac'))
            ->update([
                'ensede' => $request->input('tipo')=="I" ? "S" : "N"  ,
                'fechahora_ultimomov' => $fechaHoraActualFormateada,
            ]);        
            DB::commit(); // ← GUARDA TODO

            return response()->json([
                'success' => true,
                'message' => "EL MOVIMIENTO FUE GUARDADO DE FORMA SATISFACTORIA",
            ]);

        } catch (\Exception $e) {

            DB::rollBack(); // ← DESHACE TODO

            return response()->json([
                'success' => false,
                'message' => "OCURRIO UN ERROR AL GUARDAR. INTENTE NUEVAMENTE.",
                'error'   => $e->getMessage(), // ← opcional, quitar en producción
            ]);
        }


    }

    public function validaIDPlaca(Request $request)
    {
        if ($request->filled('idco')) {
            $existe = DB::table('tra_conductores')
                ->where('id_conductor', $request->idco)
                ->exists();
            if (!$existe) {
                return response()->json([
                    'success' => false,
                    'message' => "EL CONDUCTOR CON ID {$request->idco} NO ESTA REGISTRADO.",
                ]);
            }        
        }        
        if ($request->filled('plac')) {
            $existe = DB::table('tra_vehiculos')
                ->where('nroplaca', $request->plac)
                ->exists();
            if (!$existe) {
                return response()->json([
                    'success' => false,
                    'message' => "EL VEHICULO DE PLACA {$request->plac} NO ESTA REGISTRADO.",
                ]);
            }        
        }
        return response()->json([
            'success' => true,
            'message' => "DATO VALIDADO",
        ]);        
    }

    public function consultarIntervalo()
    {
        return view('transporte.consultaintervalofecha');
    }
    public function consultarIntervalodetalle(Request $request)
    {
        $fechaini = $request->input('fechaini');    
        $fechafin = $request->input('fechafin');    

        $segdetalle = DB::table('tra_controlvehiculos')
        ->leftJoin('tra_conductores', 'tra_controlvehiculos.id_conductor', '=', 'tra_conductores.id_conductor')
        ->leftJoin('tra_vehiculos', 'tra_controlvehiculos.placa', '=', 'tra_vehiculos.nroplaca')
        ->select(
            'tra_controlvehiculos.fechahora_registro',
            'tra_controlvehiculos.tipo_mov',
            'tra_conductores.apellido_paterno',
            'tra_conductores.apellido_materno',
            'tra_conductores.nombres',
            'tra_controlvehiculos.placa',
            'tra_vehiculos.marca',
            'tra_vehiculos.modelo',
            'tra_vehiculos.color',
            'tra_controlvehiculos.kilometraje',
            'tra_controlvehiculos.observacion',
        )
        ->whereDate('fechahora_registro', '>=', $fechaini)
        ->whereDate('fechahora_registro', '<=', $fechafin)
        ->orderBy('id_movimiento', 'desc') 
        ->get();

        if ($segdetalle->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'registros' => $segdetalle,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'NO SE ENCONTRARON MOVIMIENTOS DEL'. $fechaini .' AL '. $fechafin .' .',
            ]);
        }

    }





}
