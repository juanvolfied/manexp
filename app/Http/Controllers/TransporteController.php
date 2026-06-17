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
            'nrocelular' => 'required|max:9',
            'activo' => 'required|in:S,N',
        ]);

        DB::table('tra_conductores')->insert([
            'id_conductor' => strtoupper( $request->input('id_conductor') ),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'nombres' => $request->input('nombres'),
            'nrocelular' => $request->input('nrocelular'),
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
            'nrocelular' => 'required|max:9',
            'activo' => 'required|in:S,N',
        ]);

        DB::table('tra_conductores')
        ->where('id_conductor', $request->input('id_conductor'))
        ->update([
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'nombres' => $request->input('nombres'),
            'nrocelular' => $request->input('nrocelular'),
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


    public function registroAsistencia()
    {
        $fecha = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $conductores = DB::table('tra_asistencia')
        ->leftJoin('tra_conductores', 'tra_asistencia.id_conductor', '=', 'tra_conductores.id_conductor')
        ->select(
            'tra_asistencia.*',
            'tra_conductores.apellido_paterno',
            'tra_conductores.apellido_materno',
            'tra_conductores.nombres',
        )
        ->where('fecha', '=', $fecha)
        ->orderBy('id_movimiento', 'desc') 
        ->get();

        return view('transporte.registroasistencia', compact('conductores'));
    }
    public function grabaAsistencia(Request $request)
    {
        $fechahora = now()->format('Y-m-d H:i:s');
        $fecha = substr($fechahora, 0,10);  
        $hora = substr($fechahora,11,8);  
        $idconductor=$request->input('idconductor');
        $existe = DB::table('tra_conductores')
            ->where('id_conductor', $idconductor)
            ->exists();
        if (!$existe) {
            return response()->json([
                'success' => false,
                'message' => "EL ID DEL CONDUCTOR INGRESADO ({$idconductor}) NO ESTA REGISTRADO.",
            ]);
        }
        $existe = DB::table('tra_asistencia')
            ->where('id_conductor', $idconductor)
            ->where('fecha', $fecha)
            ->exists();
        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => "EL ID DEL CONDUCTOR INGRESADO ({$idconductor}) YA REGISTRO ASISTENCIA HOY.",
            ]);
        }

        DB::table('tra_asistencia')->insert([
            'fecha' => $fecha,
            'hora' => $hora,
            'id_conductor' => $idconductor,
            'id_operador' => Auth::user()->id_personal,
            'tipo_movimiento' => 'I',
        ]);
        return response()->json([
            'success' => true,
            'message' => "ASISTENCIA REGISTRADA SATISFACTORIAMENTE.",
        ]);
    }




    public function controlMovimiento()
    {
        $fecha = now()->format('Y-m-d'); 
        $conductoressede = DB::table('tra_conductores as c')
            ->select(
                'c.id_conductor',
                'c.apellido_paterno',
                'c.apellido_materno',
                'c.nombres',
                'c.fechahora_ultimomov',
                DB::raw("
                    CASE 
                        WHEN EXISTS (
                            SELECT 1
                            FROM tra_asistencia a
                            WHERE a.id_conductor = c.id_conductor
                            AND a.fecha = '{$fecha}'
                        ) THEN 'S'
                        ELSE 'N'
                    END as marco_asistencia
                ")
            )
            ->get();
  
        $vehiculossede = DB::table('tra_vehiculos')
            ->where('ensede', 'S')
            ->get();
        $vehiculosdili = DB::table('tra_vehiculos')
            ->where('ensede', 'N')
            ->get();

            
        $conductoressede->transform(function ($doc) use ($fecha) {
            //$anio = substr($doc->fecharegistro, 0, 4); // "2025"
            //$mes  = substr($doc->fecharegistro, 5, 2); // "09"
            $doc->conductorensede = 'N';
            if ($doc->marco_asistencia=="S") {
                $idcond = $doc->id_conductor;
                $movs = DB::table('tra_controlvehiculos')
                    ->where('id_conductor', $idcond)
                    ->whereDate('fechahora_registro', $fecha)
                    ->orderBy('id_movimiento', 'desc') 
                    ->first();
                if ($movs) {
                    if ($movs->tipo_mov=='I') {
                        $doc->conductorensede = 'S';
                    } else {
                        $doc->conductorensede = 'N';
                    }
                } else {
                    $doc->conductorensede = 'S';
                }
            }
            return $doc; 
        });

        return view('transporte.movimiento', compact('conductoressede', 'vehiculossede', 'vehiculosdili'));            
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
        /*
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
        */
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

    public function controlMovimiento2()
    {
        $fecha = now()->format('Y-m-d'); 
        $conductoressede = DB::table('tra_conductores as c')
            ->select(
                'c.id_conductor',
                'c.apellido_paterno',
                'c.apellido_materno',
                'c.nombres',
                'c.fechahora_ultimomov',
                DB::raw("
                    CASE 
                        WHEN EXISTS (
                            SELECT 1
                            FROM tra_asistencia a
                            WHERE a.id_conductor = c.id_conductor
                            AND a.fecha = '{$fecha}'
                        ) THEN 'S'
                        ELSE 'N'
                    END as marco_asistencia
                ")
            )
            ->get();

        $vehiculossede = DB::table('tra_vehiculos')
            ->where('ensede', 'S')
            ->get();        
        $vehiculosdili = DB::table('tra_vehiculos')
            ->where('ensede', 'N')
            ->get();        

            
        $conductoressede->transform(function ($doc) use ($fecha) {
            //$anio = substr($doc->fecharegistro, 0, 4); // "2025"
            //$mes  = substr($doc->fecharegistro, 5, 2); // "09"
            $doc->conductorensede = 'N';
            if ($doc->marco_asistencia=="S") {
                $idcond = $doc->id_conductor;
                $movs = DB::table('tra_controlvehiculos')
                    ->where('id_conductor', $idcond)
                    ->whereDate('fechahora_registro', $fecha)
                    ->orderBy('id_movimiento', 'desc') 
                    ->first();
                if ($movs) {
                    if ($movs->tipo_mov=='I') {
                        $doc->conductorensede = 'S';
                    } else {
                        $doc->conductorensede = 'N';
                    }
                } else {
                    $doc->conductorensede = 'S';
                }
            }
            return $doc; 
        });

        return view('transporte.movimiento2', compact('conductoressede', 'vehiculossede', 'vehiculosdili'));
    }
    public function validaIDPlaca2(Request $request)
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

        $fecha = now()->format('Y-m-d');
        $existe = DB::table('tra_asistencia')
            ->where('id_conductor', $request->idco)
            ->where('fecha', $fecha)
            ->exists();
        if (!$existe) {
            return response()->json([
                'success' => false,
                'message' => "EL CONDUCTOR CON ID ({$request->idco}) NO REGISTRO ASISTENCIA HOY.",
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
                //'kilometraje' => $request->input('kilo'),
                //'observacion' => $request->input('obse'),
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




    public function programarSalida()
    {
        $movsvehiculos = DB::table('tra_controlvehiculos')
        ->leftJoin('tra_conductores', 'tra_controlvehiculos.id_conductor', '=', 'tra_conductores.id_conductor')
        ->leftJoin('tra_vehiculos', 'tra_controlvehiculos.placa', '=', 'tra_vehiculos.nroplaca')
        ->leftJoin('personal', 'tra_controlvehiculos.solicitante', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'tra_controlvehiculos.dependenciasoli', '=', 'dependencia.id_dependencia')
            ->select(
                'tra_controlvehiculos.*',
                'tra_conductores.apellido_paterno',
                'tra_conductores.apellido_materno',
                'tra_conductores.nombres',
                'tra_vehiculos.marca',
                'tra_vehiculos.modelo',
                'tra_vehiculos.color',
                'personal.apellido_paterno as apepatper',
                'personal.apellido_materno as apematper',
                'personal.nombres as nombreper',
                'dependencia.descripcion',
                'dependencia.abreviado'
            )
            ->where('progconductor', 'S')
            ->orderBy('id_movimiento', 'desc')
            ->get();

        $vehiculossede = DB::table('tra_vehiculos')
            ->where('ensede', 'S')
            ->get();
        $conductores = DB::table('tra_conductores')
            ->where('activo', 'S')
            ->get();
        $personal = DB::table('personal')
        ->leftJoin('dependencia', 'personal.id_dependencia', '=', 'dependencia.id_dependencia')
        ->select(
            'personal.id_personal',
            'personal.apellido_paterno',
            'personal.apellido_materno',
            'personal.nombres',
            'personal.id_dependencia',
            'personal.despacho',
            'dependencia.descripcion',
            'dependencia.abreviado'
        )
        ->orderBy('apellido_paterno', 'asc') 
        ->orderBy('apellido_materno', 'asc') 
        ->orderBy('nombres', 'asc') 
        ->get();

        return view('transporte.programarsalida', compact('movsvehiculos','vehiculossede','conductores','personal'));            
    }
    public function grabasolicitudplaca(Request $request)
    {
        $fecha = now()->format('Y-m-d');   
        $existe = DB::table('tra_vehiculos')
            ->where('nroplaca', $request->nroplaca)
            ->exists();
        if (!$existe) {
            return response()->json([
                'success' => false,
                'message' => "EL VEHICULO DE PLACA {$request->nroplaca} NO ESTA REGISTRADO.",
            ]);
        }
        $existe = DB::table('tra_conductores')
            ->where('id_conductor', $request->idconductor)
            ->exists();
        if (!$existe) {
            return response()->json([
                'success' => false,
                'message' => "EL CONDUCTOR CON ID {$request->idconductor} NO ESTA REGISTRADO.",
            ]);
        }        
        $datoplac = DB::table('tra_controlvehiculos')
            ->where('placa', $request->nroplaca)
            ->orderBy('id_movimiento', 'desc')
            ->first();
        if ($datoplac) {
            $tipo=$datoplac->tipo_mov;
            $esta=$datoplac->estado;
            if ($request->input('tipo') == "S") {
                $tpmsg = $esta=="P" ? "YA TIENE PROGRAMADA OTRA SALIDA" : "YA SE ENCUENTRA EN UNA COMISION";
                return response()->json([
                    'success' => false,
                    'message' => "EL VEHICULO DE PLACA {$request->nroplaca} {$tpmsg}.",
                ]);
            }
        }

        try {
            DB::beginTransaction(); // ← INICIA LA TRANSACCIÓN
            $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
            DB::table('tra_controlvehiculos')
            ->insert([
                'fechahora_registro' => $fechaHoraActualFormateada,
                'tipo_mov' => "S",
                'id_conductor' => $request->input('idconductor'),
                'placa' => $request->input('nroplaca'),
                'kilometraje' => $request->input('kilometraje'),
                'observacion' => $request->input('ruta'),
                'id_personal' => Auth::user()->id_personal,
                'progconductor' => "S",
                'fechahora_programado' => $fechaHoraActualFormateada,
                'estado' => "P",
            ]);
            DB::table('tra_vehiculos')
            ->where('nroplaca', $request->input('nroplaca'))
            ->update([
                'ensede' => "P"  ,
                'fechahora_ultimomov' => $fechaHoraActualFormateada,
            ]);    

            DB::commit(); // ← GUARDA TODO
            return response()->json([
                'success' => true,
                'message' => "LA SOLICITUD FUE GUARDADA DE FORMA SATISFACTORIA",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // ← DESHACE TODO
            return response()->json([
                'success' => false,
                'message' => "OCURRIO UN ERROR AL GUARDAR. INTENTE NUEVAMENTE.",
                'error'   => $e->getMessage(), 
            ]);
        }
    }
    public function controlMovimiento3()
    {
        $vehiculossede = DB::table('tra_vehiculos')
            ->where('ensede', 'S')
            ->get();        

        $vehiculosprog = DB::table('tra_controlvehiculos')
        ->leftJoin('tra_conductores', 'tra_controlvehiculos.id_conductor', '=', 'tra_conductores.id_conductor')
        ->leftJoin('tra_vehiculos', 'tra_controlvehiculos.placa', '=', 'tra_vehiculos.nroplaca')
        ->leftJoin('personal', 'tra_controlvehiculos.solicitante', '=', 'personal.id_personal')
            ->select(
                'tra_controlvehiculos.*',
                'tra_conductores.apellido_paterno',
                'tra_conductores.apellido_materno',
                'tra_conductores.nombres',
                'tra_vehiculos.marca',
                'tra_vehiculos.modelo',
                'tra_vehiculos.color',
                'tra_vehiculos.ensede',
                'personal.apellido_paterno as apepatper',
                'personal.apellido_materno as apematper',
                'personal.nombres as nombreper',
            )
            ->where('progconductor', 'S')
            ->orderBy('id_movimiento', 'asc')
            ->get();

        $conductoressede = DB::table('tra_conductores')
            ->select(
                'tra_conductores.*'
            )
            ->where('activo', 'S')
            ->whereNotIn('id_conductor', function ($query) {
                $query->select('id_conductor')
                    ->from('tra_controlvehiculos')
                    ->where('progconductor', 'S')
                    ->whereIn('estado', ['P', 'D']);
            })
            ->orderBy('apellido_paterno', 'asc')
            ->orderBy('apellido_materno', 'asc')
            ->orderBy('nombres', 'asc')
            ->get();
        $conductores = DB::table('tra_conductores')
            ->where('activo', 'S')
            ->get();

        $personal = DB::table('personal')
        ->leftJoin('dependencia', 'personal.id_dependencia', '=', 'dependencia.id_dependencia')
        ->select(
            'personal.id_personal',
            'personal.apellido_paterno',
            'personal.apellido_materno',
            'personal.nombres',
            'personal.id_dependencia',
            'personal.despacho',
            'dependencia.descripcion',
            'dependencia.abreviado'
        )
        ->orderBy('apellido_paterno', 'asc') 
        ->orderBy('apellido_materno', 'asc') 
        ->orderBy('nombres', 'asc') 
        ->get();



        return view('transporte.movimiento3', compact('vehiculossede', 'vehiculosprog','conductoressede','conductores','personal'));            
    }
    public function grabaMovimiento3(Request $request)
    {
        if ($request->input('tp')=='V') {//validar
            try {
                DB::beginTransaction(); 
                $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
                DB::table('tra_controlvehiculos')
                ->where('id_movimiento', $request->input('idmov'))
                ->update([
                    'estado' => "D"  ,
                    'fechahora_registro' => $fechaHoraActualFormateada,
                ]);
                DB::table('tra_vehiculos')
                ->where('nroplaca', $request->input('plac'))
                ->update([
                    'ensede' => "N",
                    'fechahora_ultimomov' => $fechaHoraActualFormateada,
                ]);    
                DB::commit(); // GUARDA TODO
                return response()->json([
                    'success' => true,
                    'message' => "EL MOVIMIENTO FUE GUARDADO DE FORMA SATISFACTORIA",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // DESHACE TODO
                return response()->json([
                    'success' => false,
                    'message' => "OCURRIO UN ERROR AL GUARDAR. INTENTE NUEVAMENTE.",
                    'error'   => $e->getMessage(), // ← opcional, quitar en producción
                ]);
            }
        }


        if ($request->input('tp')=='I' || $request->input('tp')=='P' || $request->input('tp')=='E') {
            $existe = DB::table('tra_conductores')
                ->where('id_conductor', $request->idconductor)
                ->exists();
            if (!$existe) {
                return response()->json([
                    'success' => false,
                    'message' => "EL CONDUCTOR CON ID {$request->idconductor} NO ESTA REGISTRADO.",
                ]);
            }        
        }


        if ($request->input('tp')=='I') {
            try {
                DB::beginTransaction(); 
                $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
                DB::table('tra_controlvehiculos')
                ->insert([
                    'fechahora_registro' => $fechaHoraActualFormateada,
                    'tipo_mov' => "I",
                    'id_conductor' => $request->input('idconductor'),
                    'placa' => $request->input('plac'),
                    'kilometraje' => $request->input('kilometraje'),
                    'observacion' => $request->input('ruta'),
                    'id_personal' => Auth::user()->id_personal,
                ]);

                DB::table('tra_controlvehiculos')
                ->where('id_movimiento', $request->input('idmov'))
                ->update([
                    'estado' => "S",
                ]);
                DB::table('tra_vehiculos')
                ->where('nroplaca', $request->input('plac'))
                ->update([
                    'ensede' => "S",
                    'fechahora_ultimomov' => $fechaHoraActualFormateada,
                ]);    
                DB::commit(); // GUARDA TODO
                return response()->json([
                    'success' => true,
                    'message' => "EL MOVIMIENTO FUE GUARDADO DE FORMA SATISFACTORIA",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // DESHACE TODO
                return response()->json([
                    'success' => false,
                    'message' => "OCURRIO UN ERROR AL GUARDAR. INTENTE NUEVAMENTE.",
                    'error'   => $e->getMessage(), // ← opcional, quitar en producción
                ]);
            }
        }


        if ($request->input('tp')=='P') {
            try {
                DB::beginTransaction(); // ← INICIA LA TRANSACCIÓN
                $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
                DB::table('tra_controlvehiculos')
                ->insert([
                    'fechahora_registro' => $fechaHoraActualFormateada,
                    'tipo_mov' => "S",
                    'id_conductor' => $request->input('idconductor'),
                    'placa' => $request->input('plac'),
                    'kilometraje' => $request->input('kilometraje'),
                    'observacion' => $request->input('ruta'),
                    'id_personal' => Auth::user()->id_personal,
                    'progconductor' => "S",
                    'fechahora_programado' => $fechaHoraActualFormateada,
                    'estado' => "P",
                    'solicitante' => $request->input('personalsoli'),
                    'dependenciasoli' => $request->input('iddepen'),
                    'despachosoli' => $request->input('despacho'),
                ]);
                DB::table('tra_vehiculos')
                ->where('nroplaca', $request->input('plac'))
                ->update([
                    'ensede' => "P"  ,
                    'fechahora_ultimomov' => $fechaHoraActualFormateada,
                ]);    

                DB::commit(); // ← GUARDA TODO
                return response()->json([
                    'success' => true,
                    'message' => "LA SOLICITUD FUE GUARDADA DE FORMA SATISFACTORIA",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // ← DESHACE TODO
                return response()->json([
                    'success' => false,
                    'message' => "OCURRIO UN ERROR AL GUARDAR. INTENTE NUEVAMENTE.",
                    'error'   => $e->getMessage(), 
                ]);
            }
        }

        if ($request->input('tp')=='E') {
            try {
                DB::beginTransaction(); // ← INICIA LA TRANSACCIÓN
                $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
                DB::table('tra_controlvehiculos')
                ->where('id_movimiento', $request->input('idmov'))
                ->update([
                    'id_conductor' => $request->input('idconductor'),
                    'placa' => $request->input('plac'),
                    'kilometraje' => $request->input('kilometraje'),
                    'observacion' => $request->input('ruta'),
                    'id_personal' => Auth::user()->id_personal,
                    'solicitante' => $request->input('personalsoli'),
                    'dependenciasoli' => $request->input('iddepen'),
                    'despachosoli' => $request->input('despacho'),
                ]);
                DB::commit(); // ← GUARDA TODO
                return response()->json([
                    'success' => true,
                    'message' => "LOS CAMBIOS FUERON GUARDADOS DE FORMA SATISFACTORIA",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // ← DESHACE TODO
                return response()->json([
                    'success' => false,
                    'message' => "OCURRIO UN ERROR AL GUARDAR. INTENTE NUEVAMENTE.",
                    'error'   => $e->getMessage(), 
                ]);
            }
        }


    }
    public function eliminarProgramacion(Request $request)
    {
        try {
            DB::beginTransaction(); 
            DB::table('tra_controlvehiculos')
            ->where('id_movimiento', $request->input('idmov'))
            ->delete();
            DB::table('tra_vehiculos')
            ->where('nroplaca', $request->input('plac'))
            ->update([
                'ensede' => "S",
            ]);    
            DB::commit(); // GUARDA TODO
            return response()->json([
                'success' => true,
                'message' => "EL MOVIMIENTO FUE ELIMINADO DE FORMA SATISFACTORIA",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // DESHACE TODO
            return response()->json([
                'success' => false,
                'message' => "OCURRIO UN ERROR AL GUARDAR. INTENTE NUEVAMENTE.",
                'error'   => $e->getMessage(), // ← opcional, quitar en producción
            ]);
        }
    }
    public function vehiculosDisponibles()
    {
        $fecha = now()->format('Y-m-d');   
        $vehiculossede = DB::table('tra_vehiculos')
            ->where('ensede', 'S')
            ->orderBy('nroplaca', 'asc')
            ->get();
        $vehiculosdili = DB::table('tra_vehiculos')
            ->where('ensede', 'N')
            ->orderBy('nroplaca', 'asc')
            ->get();

        return view('transporte.vehiculosdisponibles', compact('vehiculossede', 'vehiculosdili'));            
    }






    public function consultarIntervalo()
    {
        $fecha = now()->format('Y-m-d');   
        $fechaini2 = $fecha.' 00:00:00';
        $fechafin2 = $fecha.' 23:59:59';
        $movimientos = DB::select("
            SELECT
                s.placa,
                s.fechahora_registro AS fecha_salida,
                s.kilometraje AS kilometraje_salida,
                s.id_conductor,
                CONCAT(
                    c.apellido_paterno, ' ',
                    c.apellido_materno, ' ',
                    c.nombres
                ) AS conductor,                    
                s.observacion,
                (
                    SELECT i.fechahora_registro
                    FROM tra_controlvehiculos i
                    WHERE i.placa = s.placa
                    AND i.tipo_mov = 'I'
                    AND i.id_movimiento > s.id_movimiento
                    ORDER BY i.id_movimiento
                    LIMIT 1
                ) AS fecha_entrada,
                (
                    SELECT i.kilometraje
                    FROM tra_controlvehiculos i
                    WHERE i.placa = s.placa
                    AND i.tipo_mov = 'I'
                    AND i.id_movimiento > s.id_movimiento
                    ORDER BY i.id_movimiento
                    LIMIT 1
                ) AS kilometraje_entrada
            FROM tra_controlvehiculos s
            LEFT JOIN tra_conductores c
                ON c.id_conductor = s.id_conductor                
            WHERE s.tipo_mov = 'S'
            AND (s.estado IS NULL OR s.estado NOT IN ('P'))
            AND s.fechahora_registro BETWEEN ? AND ?
            ORDER BY s.placa, s.id_movimiento
        ", [$fechaini2, $fechafin2]);            

        $conductores = DB::table('tra_conductores')
            ->where('activo', 'S')
            ->orderBy('apellido_paterno', 'asc')
            ->orderBy('apellido_materno', 'asc')
            ->orderBy('nombres', 'asc')
            ->get();

        return view('transporte.consultaintervalofecha',compact('movimientos','conductores'));
    }
    public function consultarIntervalodetalle(Request $request)
    {
        $fechaini = $request->input('fechaini');    
        $fechafin = $request->input('fechafin');    
        $agrupar = $request->input('agrupar');  
        $filtropla = $request->input('filtroplaca');  
        $placa = $request->input('placa');  
        $filtrocon = $request->input('filtroconductor');  
        $idconductor = $request->input('idconductor');  
        
        $condplaca="";
        if ($filtropla==1) {
            $condplaca=" and s.placa = '".$placa."' ";
        }
        $condconductor="";
        if ($filtrocon==1) {
            $condconductor=" and s.id_conductor = '".$idconductor."' ";
        }

        $fechaini2 = $fechaini.' 00:00:00';
        $fechafin2 = $fechafin.' 23:59:59';
        $movimientos = DB::select("
            SELECT
                s.placa,
                s.fechahora_registro AS fecha_salida,
                s.kilometraje AS kilometraje_salida,
                s.id_conductor,
                CONCAT(
                    c.apellido_paterno, ' ',
                    c.apellido_materno, ' ',
                    c.nombres
                ) AS conductor,                    
                s.observacion,
                (
                    SELECT i.fechahora_registro
                    FROM tra_controlvehiculos i
                    WHERE i.placa = s.placa
                    AND i.tipo_mov = 'I'
                    AND i.id_movimiento > s.id_movimiento
                    ORDER BY i.id_movimiento
                    LIMIT 1
                ) AS fecha_entrada,
                (
                    SELECT i.kilometraje
                    FROM tra_controlvehiculos i
                    WHERE i.placa = s.placa
                    AND i.tipo_mov = 'I'
                    AND i.id_movimiento > s.id_movimiento
                    ORDER BY i.id_movimiento
                    LIMIT 1
                ) AS kilometraje_entrada
            FROM tra_controlvehiculos s
            LEFT JOIN tra_conductores c
                ON c.id_conductor = s.id_conductor                
            WHERE s.tipo_mov = 'S'
            ".$condplaca."
            ".$condconductor."
            AND (s.estado IS NULL OR s.estado NOT IN ('P'))
            AND s.fechahora_registro BETWEEN ? AND ?
            ORDER BY s.placa, s.id_movimiento
        ", [$fechaini2, $fechafin2]);            

        $conductores = DB::table('tra_conductores')
            ->where('activo', 'S')
            ->orderBy('apellido_paterno', 'asc')
            ->orderBy('apellido_materno', 'asc')
            ->orderBy('nombres', 'asc')
            ->get();

            return view('transporte.consultaintervalofecha',compact('movimientos','conductores','fechaini','fechafin', 'filtropla', 'placa', 'filtrocon', 'idconductor'));
            
/*
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
        ->whereDate('fechahora_registro', '<=', $fechafin);
        if ($agrupar==1) {
            $segdetalle->orderBy('apellido_paterno', 'asc') 
            ->orderBy('apellido_materno', 'asc') 
            ->orderBy('fechahora_registro', 'asc'); 
        } else {
            $segdetalle->orderBy('id_movimiento', 'asc');
        }
        $segdetalle = $segdetalle->get();

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
*/

    }




}
