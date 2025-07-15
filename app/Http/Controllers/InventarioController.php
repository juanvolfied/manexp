<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function registraInventario()
    {
        return view('inventario.reginventario');
    }


    public function mostrarVistaGrafico()
    {
//        $usuarios = Usuarios::all(); // O lo que uses para listar usuarios
        $usuarios = DB::table('usuarios')
            ->leftJoin('personal', 'usuarios.id_personal', '=', 'personal.id_personal')
            ->select('id_usuario', 'apellido_paterno', 'apellido_materno', 'nombres', 'usuario')
            ->orderBy('apellido_paterno', 'asc') 
            ->orderBy('apellido_materno', 'asc') 
            ->orderBy('nombres', 'asc') 
            ->get();
        
        return view('inventario.grafico', compact('usuarios'));
    }
    public function graficoPorUsuario(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|integer',
            'fechainicio' => 'required|date',
            'fechafin' => 'required|date|after_or_equal:fechainicio',
            'tpdatos' => 'required|integer',
        ]);

        $id_usuario = $request->id_usuario;
        $fechainicio = Carbon::parse($request->fechainicio);
        $fechafin = Carbon::parse($request->fechafin);
        $tpdatos = $request->tpdatos;

        // Generar un array con todas las fechas del rango
        $periodo = [];
        for ($date = $fechainicio->copy(); $date->lte($fechafin); $date->addDay()) {
            $periodo[] = $date->format('Y-m-d');
        }

        // Consulta a la base de datos
        if ($tpdatos==="1") {
        $datos = DB::table('ubicacion_exp')
            ->select(DB::raw('DATE(fecha_inventario) as dia'), DB::raw('count(*) as total'))
            ->where('id_usuario', $id_usuario)
            ->whereBetween('fecha_inventario', [$fechainicio, $fechafin])
            ->groupBy(DB::raw('DATE(fecha_inventario)'))
            ->pluck('total', 'dia');
        } else {
        $datos = DB::table('ubicacion_exp')
            ->select(
                DB::raw('DATE(fecha_inventario) as dia'),
                DB::raw('COUNT(DISTINCT nro_inventario) as total')
            )
            ->where('id_usuario', $id_usuario)
            ->whereBetween('fecha_inventario', [$fechainicio, $fechafin])
            ->groupBy(DB::raw('DATE(fecha_inventario)'))
            ->pluck('total', 'dia');
        }

        // Asegurar que todos los d�as del periodo est�n representados (aunque tengan 0)
        $resultado = [];
        foreach ($periodo as $dia) {
            $resultado[$dia] = $datos[$dia] ?? 0;
        }

        // Retornar para el gr�fico (por ejemplo en formato JSON)
        return response()->json([
            'labels' => array_keys($resultado),
            'data' => array_values($resultado),
        ]);
    }

    
    public function mostrarGraficoPie()
    {
        return view('inventario.graficopie');
    }    
    public function graficoPieFecha(Request $request)
    {

        $tpfecha = $request->tpfecha;
        $fechainicio = Carbon::parse($request->fechainicio);
        $fechafin = Carbon::parse($request->fechafin);
        $tpdatos = $request->tpdatos;

        // Consulta a la base de datos
        if ($tpdatos==="1") {
        $query = DB::table('ubicacion_exp')
            ->leftJoin('dependencia', 'ubicacion_exp.paq_dependencia', '=', 'dependencia.id_dependencia')
            ->select('abreviado', DB::raw('count(*) as total'));
        } else {
        $query = DB::table('ubicacion_exp')
            ->leftJoin('dependencia', 'ubicacion_exp.paq_dependencia', '=', 'dependencia.id_dependencia')
            ->select(
                'abreviado',
                DB::raw('COUNT(DISTINCT nro_inventario) as total')
            );
        }
        if ($tpfecha==="F") {
            $query = $query->whereBetween('fecha_inventario', [$fechainicio, $fechafin]);
        }
        $datos = $query->groupBy('abreviado')
            ->pluck('total', 'abreviado');

        $resultado = $datos->toArray();

        // Retornar para el gr�fico (por ejemplo en formato JSON)
        return response()->json([
            'labels' => array_keys($resultado),
            'data' => array_values($resultado),
        ]);
    }

}
