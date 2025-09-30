<?php

namespace App\Http\Controllers;

use App\Models\Expedientes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Mpdf\Mpdf;
use App\Services\BarcodeGenerator;
use Smalot\PdfParser\Parser;
use DateTime; 
use Illuminate\Support\Facades\File;

use Carbon\Carbon;


class CarpetasSGFController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $fecha = $request->input('fecharegistro', date('Y-m-d'));

        $carpetassgf = DB::table('carpetas_sgf')
        ->leftJoin('dependencia', 'carpetas_sgf.id_dependencia', '=', 'dependencia.id_dependencia')
        ->leftJoin('personal', 'carpetas_sgf.id_personal', '=', 'personal.id_personal')
        ->select(
            'carpetas_sgf.id_generado',
            'carpetas_sgf.carpetafiscal',
            'dependencia.abreviado',
            'carpetas_sgf.despacho',
            'carpetas_sgf.fechahora_registro',
            'personal.apellido_paterno',
            'personal.apellido_materno',
            'personal.nombres'
        )
        ->wheredate('fechahora_registro', $fecha)
//        ->wheredate('fecharegistro', date('Y-m-d', strtotime('-2 day')))
        ->orderBy('fechahora_registro', 'desc') 
        ->get();

        return view('carpetassgf.index', compact('carpetassgf', 'fecha'));
    }
    public function nuevoCarpetaSGF()
    {
        $dependencias = DB::table('dependencia')
            ->orderBy('descripcion', 'asc') 
            ->get();

        return view('carpetassgf.registrocarpetassgf', compact('dependencias'));
    }
    public function buscaCarpeta(Request $request)
    {    
        $codbarras = $request->input('codbarras');
        $registros = DB::table('carpetas_sgf')
            ->where('carpetafiscal', $codbarras)
            ->get();
        if ($registros->isNotEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'LA CARPETA FISCAL '. $codbarras .' YA SE ENCUENTRA REGISTRADA.',
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'OK',
            ]);
        }
    }









    public function consultarFiscal()
    {
        $fiscales = DB::table('personal')
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
        ->where('fiscal_asistente', 'F')
        ->orderBy('apellido_paterno', 'asc') 
        ->orderBy('apellido_materno', 'asc') 
        ->orderBy('nombres', 'asc') 
        ->get();

        return view('mesapartes.consultafechafiscal', compact('fiscales'));
    }
    public function consultarFiscaldetalle(Request $request)
    {
        $fiscal = $request->input('fiscal');    
        $fechareg = $request->input('fechareg');    

        $query = DB::table('libroescritos')
            ->select('*')
            ->where('libroescritos.id_fiscal', $fiscal)
            ->whereDate('fecharegistro', '=', $fechareg);        
        $segdetalle = $query
            ->orderBy('fecharegistro', 'desc')
            ->get();

                $anio = substr($fechareg, 0, 4); // "2025"
                $mes  = substr($fechareg, 5, 2); // "09"
        $querycargo = DB::table('librocargos')
            ->select('*')
            ->where('id_fiscal', $fiscal)
            ->whereDate('fechacargo', '=', $fechareg)
            ->first();
        $existedigital=false;
        $rutacargo="";
        if ($querycargo) {
            $rutalow = storage_path("app/mesapartescargos/{$anio}/{$mes}/" . strtolower($querycargo->codcargo) . ".pdf");
            $ruta = storage_path("app/mesapartescargos/{$anio}/{$mes}/" . strtoupper($querycargo->codcargo) . ".pdf");
            if (file_exists($rutalow)) {
                rename($rutalow, $ruta);
            }
            $existedigital=file_exists($ruta);
            $rutacargo="{$anio}/{$mes}/" . strtoupper($querycargo->codcargo);
        }
                
        if ($segdetalle->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'registros' => $segdetalle,
                'cargodigital' => $existedigital,
                'rutacargo' => $rutacargo,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'NO SE ENCONTRARON CARPETAS FISCALES CON LOS DATOS PROPORCIONADOS.',
            ]);
        }

    }


    public function consultarIntervalo()
    {
        return view('mesapartes.consultaintervalofecha');
    }
    public function consultarIntervalodetalle(Request $request)
    {
        $fechaini = $request->input('fechaini');    
        $fechafin = $request->input('fechafin');    

        $segdetalle = DB::table('libroescritos')
        ->leftJoin('personal', 'libroescritos.id_fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'libroescritos.id_dependencia', '=', 'dependencia.id_dependencia')
        ->leftJoin('usuarios', 'libroescritos.id_usuario', '=', 'usuarios.id_usuario')
        ->select(
            'libroescritos.codescrito',
            'libroescritos.tiporecepcion',
            'dependencia.abreviado',
            'libroescritos.despacho',
            'personal.apellido_paterno',
            'personal.apellido_materno',
            'personal.nombres',
            'libroescritos.tipo',
            'libroescritos.descripcion as descripcionescrito',
            'libroescritos.dependenciapolicial',
            'libroescritos.remitente',
            'libroescritos.carpetafiscal',
            'libroescritos.folios',
            'libroescritos.fecharegistro',
            'usuarios.usuario'
        )
        ->whereDate('fecharegistro', '>=', $fechaini)
        ->whereDate('fecharegistro', '<=', $fechafin)
        ->orderBy('codescrito', 'asc') 
        ->get();

        if ($segdetalle->isNotEmpty()) {
            
            $segdetalle->transform(function ($doc) {
                $anio = substr($doc->fecharegistro, 0, 4); // "2025"
                $mes  = substr($doc->fecharegistro, 5, 2); // "09"
                
                $rutalow = storage_path("app/mesapartes/{$anio}/{$mes}/" . strtolower($doc->codescrito) . ".pdf");
                $ruta = storage_path("app/mesapartes/{$anio}/{$mes}/" . strtoupper($doc->codescrito) . ".pdf");
                if (file_exists($rutalow)) {
                    rename($rutalow, $ruta);
                }
                $doc->existepdf = file_exists($ruta); // true o false
                return $doc;
            });

            return response()->json([
                'success' => true,
                'registros' => $segdetalle,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'NO SE ENCONTRARON CARPETAS FISCALES DEL'. $fechaini .' AL '. $fechafin .' .',
            ]);
        }

    }









    public function consultarEscritos()
    {
        //Auth::user()->personal->fiscal_asistente
        //return view('mesapartes.consultafechafiscal', compact('fiscales'));
        return view('mesapartes.consultaescritosdespacho');
    }
    public function consultarEscritosdetalle(Request $request)
    {
        $fechaini = $request->input('fechaini');    
        $fechafin = $request->input('fechafin');    

        $query = DB::table('libroescritos')
            ->leftJoin('personal', 'libroescritos.id_fiscal', '=', 'personal.id_personal')
            ->select('libroescritos.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres')
            ->whereDate('fecharegistro', '>=', $fechaini)        
            ->whereDate('fecharegistro', '<=', $fechafin)
            ->where('libroescritos.id_dependencia', Auth::user()->personal->id_dependencia);
            if (Auth::user()->personal->fiscal_asistente==="F") {
                $query->where('libroescritos.despacho', Auth::user()->personal->despacho)
                ->where('libroescritos.id_fiscal', Auth::user()->personal->id_personal);
            } 
            if (Auth::user()->personal->fiscal_asistente==="A") {
                $query->where('libroescritos.despacho', Auth::user()->personal->despacho);
            }


//fecha>=fini y fecha<=ffin y depen=dep y desp=coddesp y fis=pers para fiscal
//fecha>=fini y fecha<=ffin y depen=dep y (desp=coddesp o fis=0000) para asistente

        $segdetalle = $query
            //->orderBy('numero', 'desc')
            ->orderBy('personal.apellido_paterno', 'asc')
            ->orderBy('personal.apellido_materno', 'asc')
            ->orderBy('personal.nombres', 'asc')
            ->orderBy('fecharegistro', 'asc')
            ->get();
        
        if ($segdetalle->isNotEmpty()) {

            $segdetalle->transform(function ($doc) {
                $anio = substr($doc->fecharegistro, 0, 4); // "2025"
                $mes  = substr($doc->fecharegistro, 5, 2); // "09"

                $rutalow = storage_path("app/mesapartes/{$anio}/{$mes}/" . strtolower($doc->codescrito) . ".pdf");
                $ruta = storage_path("app/mesapartes/{$anio}/{$mes}/" . strtoupper($doc->codescrito) . ".pdf");
                if (file_exists($rutalow)) {
                    rename($rutalow, $ruta);
                }
                $doc->existepdf = file_exists($ruta); // true o false
                return $doc;
            });

            return response()->json([
                'success' => true,
                'registros' => $segdetalle,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'NO SE ENCONTRARON ESCRITOS EN EL INTERVALO DE FECHAS.',
            ]);
        }

    }




    public function store(Request $request)
    {
    $codigo = strtoupper($request->input('codbarras'));
    $coddep = $request->input('dependencia');
    $coddes = $request->input('despacho');

    // Verificar si ya existe el código
    $exists = DB::table('carpetas_sgf')->where('carpetafiscal', $codigo)->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->withErrors(['codbarras' => 'CÓDIGO YA SE ENCUENTRA REGISTRADO']);
    }
    if ($coddep=="") {
        return back()
            ->withInput()
            ->withErrors(['dependencia' => 'SELECCIONA LA DEPENDENCIA']);
    }
    if ($coddes=="") {
        return back()
            ->withInput()
            ->withErrors(['despacho' => 'SELECCIONA EL DESPACHO']);
    }
    
        try {

            DB::transaction(function () use ($request) {
                // Insertar el nuevo documento
                DB::table('carpetas_sgf')->insert([
                    'carpetafiscal' => strtoupper( $request->input('codbarras') ),
                    'id_dependencia' => $request->input('dependencia'),
                    'despacho' => $request->input('despacho'),
                    'fechahora_registro' => now(),
                    'id_personal' => Auth::user()->id_personal,
                ]);
            });
            return redirect()->route('carpetassgf.registrocarpetassgf')->with('success', 'INFORMACION REGISTRADA DE FORMA SATISFACTORIA.');

        } catch (QueryException $e) {
            if ($e->getCode() == '23000') { // Código SQLSTATE para violación de restricción (como unique)
                return back()
                    ->withInput()
                    ->withErrors(['codescrito' => 'CÓDIGO YA SE ENCUENTRA REGISTRADO']);
            }

            // Otro tipo de error
            return back()
                ->withInput()
                ->withErrors(['error' => 'ERROR AL REGISTRAR LA INFORMACIÓN.']);
        }        

    }

    public function edit($id_generado)
    {
        $dependencias = DB::table('dependencia')
            ->orderBy('descripcion', 'asc') 
            ->get();

        //return view('carpetassgf.registrocarpetassgf', compact('dependencias'));

        $carpetassgf = DB::table('carpetas_sgf')
        ->select(
            'carpetas_sgf.*',
        )
        ->where('id_generado', $id_generado) 
        ->first();

        return view('carpetassgf.editcarpetassgf', compact('dependencias','carpetassgf'));
    }

    public function update(Request $request, $id_generado)
    {
            DB::table('carpetas_sgf')
            ->where('id_generado', $id_generado)
            ->update([
                //'carpetafiscal' => strtoupper( $request->input('codbarras') ),
                'id_dependencia' => $request->input('dependencia'),
                'despacho' => $request->input('despacho'),
            ]);

        return redirect()->route('carpetassgf.carpetassgfindex')->with('success', 'INFORMACION ACTUALIZADA DE FORMA SATISFACTORIA.');
    }

    public function destroy(Expedientes $expediente)
    {
        $expediente->delete();
        //return redirect()->route('expediente.index')->with('success', 'EL REGISTRO HA SIDO ELIMINADO.');
        return response()->json([
            'success' => true,
            'redirect_url' => route('expediente.index'),
            'message' => 'EL REGISTRO HA SIDO ELIMINADO.',
        ]);        
    }




    public function mostrarGraficoDatos()
    {
        return view('carpetassgf.graficoindex');
    }
    public function graficoPorIntervalo(Request $request)
    {
        $request->validate([
            'fechainicio' => 'required|date',
            'fechafin' => 'required|date|after_or_equal:fechainicio',
        ]);

        $fechainicio = $request->fechainicio . ' 00:00:00';
        $fechafin    = $request->fechafin . ' 23:59:59';

        $datos = DB::table('carpetas_sgf')
            ->join('dependencia', 'carpetas_sgf.id_dependencia', '=', 'dependencia.id_dependencia')
            ->select('carpetas_sgf.id_dependencia', 'dependencia.descripcion', 'dependencia.abreviado', DB::raw('count(*) as total'))
            ->whereBetween('fechahora_registro', [$fechainicio, $fechafin])
            ->groupBy('carpetas_sgf.id_dependencia', 'dependencia.abreviado', 'dependencia.descripcion')
            ->pluck('total', 'dependencia.abreviado'); // o 'id_dependencia'


        // Convertir a arrays para Chart.js
        $labels = [];
        $totales = [];

        foreach ($datos as $abreviado => $total) {
            $labels[] = $abreviado;
            $totales[] = $total;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $totales,
        ]);
    }
    
    
}
