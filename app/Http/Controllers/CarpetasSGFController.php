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
            ->where('activo', 'S')
            ->where('mostrarsgf', 'S')
            ->orderBy('descripcion', 'asc') 
            ->get();

        return view('carpetassgf.registrocarpetassgf', compact('dependencias'));
    }
    public function buscaCarpeta(Request $request)
    {    
        if ($request->filled('codbarras')) {
            $codbarras = $request->input('codbarras');
        } else {
            $codbarras = str_pad($request->input('idde'), 11, '0', STR_PAD_LEFT) 
            . $request->input('anio') 
            . str_pad($request->input('expe'), 6, '0', STR_PAD_LEFT) 
            . str_pad($request->input('tipo'), 4, '0', STR_PAD_LEFT);
        }
        $registros = DB::table('carpetas_sgf')
            ->where('carpetafiscal', $codbarras)
            ->get();
        if ($registros->isNotEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'LA CARPETA FISCAL '. $codbarras .' YA SE ENCUENTRA REGISTRADA.',
            ]);
        } else {
            $dependencia="";
            $despacho="";
            $registro = DB::table('expediente')
                ->where('codbarras', $codbarras)
                ->first();
            if ($registro) {
                $id_expediente=$registro->id_expediente;
                $registro = DB::table('ubicacion_exp')
                    ->where('id_expediente', $id_expediente)
                    ->where('ubicacion', 'A')
                    ->where('tipo_ubicacion', 'I')
                    ->orderBy('ano_movimiento', 'desc') 
                    ->orderBy('nro_movimiento', 'desc') 
                    ->first();
                if ($registro) {
                    $dependencia=$registro->paq_dependencia;
                    $despacho=$registro->despacho;
                }
            } 

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'dependencia' => $dependencia,
                'despacho' => $despacho,
            ]);
        }
    }

    public function store(Request $request)
    {
    $codigo = $request->input('codbarrasgrabar');
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

            DB::transaction(function () use ($request, $codigo) {
                // Insertar el nuevo documento
                DB::table('carpetas_sgf')->insert([
                    'carpetafiscal' => strtoupper( $codigo ),
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

        // Convertimos a objetos Carbon
        $fechainicio = Carbon::parse($request->fechainicio)->startOfDay();
        $fechafin = Carbon::parse($request->fechafin)->endOfDay();
        
        // Generar un array con todas las fechas del rango
        $periodo = [];
        $personales = [];
        $conteos = [];        
        for ($date = $fechainicio->copy(); $date->lte($fechafin); $date->addDay()) {
            $periodo[] = $date->format('Y-m-d');
        }

        $datos = DB::table('carpetas_sgf')
            ->join('personal', 'carpetas_sgf.id_personal', '=', 'personal.id_personal')
            ->selectRaw('DATE(fechahora_registro) as fecha, carpetas_sgf.id_personal, apellido_paterno, apellido_materno, nombres, COUNT(*) as total')
            ->whereBetween('fechahora_registro', [$fechainicio, $fechafin])
            ->groupBy('fecha', 'carpetas_sgf.id_personal', 'apellido_paterno','apellido_materno','nombres')
            ->orderBy('fecha')
            ->get();

        // Reorganizamos los datos
        foreach ($datos as $row) {
            $fecha = $row->fecha;
            $personal = $row->id_personal;
            $nompersonal = $row->apellido_paterno ." ". $row->apellido_materno ." ". $row->nombres;
            // Guardamos los ids de personal
            if (!in_array($nompersonal, $personales)) {
                $personales[] = $nompersonal;
            }
            // Inicializamos si no existe
            if (!isset($conteos[$nompersonal])) {
                $conteos[$nompersonal] = array_fill_keys($periodo, 0);
            }
            $conteos[$nompersonal][$fecha] = $row->total;
        }            
        // Finalmente construimos el array para Chart.js
        $colores = [
            '#3498db', // azul
            '#e74c3c', // rojo
            '#2ecc71', // verde
            '#f1c40f', // amarillo
            '#9b59b6', // púrpura
            '#1abc9c', // turquesa
            '#e67e22', // naranja
            '#34495e', // gris oscuro
            '#95a5a6', // gris claro
            '#d35400', // naranja oscuro
        ];        
        $datasets = [];
        $colorIndex = 0;
        foreach ($personales as $personal) {
            $color = $colores[$colorIndex % count($colores)];
            $datasets[] = [
                'label' => "$personal",
                'data' => array_values($conteos[$personal]),
                'backgroundColor' => $color, // 👈 Aquí se aplica el color
                'borderColor' => $color,
                'borderWidth' => 1
            ];
            $colorIndex++;
        }

        // Retornar para el gr�fico (por ejemplo en formato JSON)
        return response()->json([
            'labels' => $periodo,
            'datasets' => $datasets,
        ]);
    }

    public function graficoPorIntervalox(Request $request)
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
