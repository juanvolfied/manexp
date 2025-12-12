<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

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
            ->leftJoin('perfil_usuario', 'usuarios.id_usuario', '=', 'perfil_usuario.id_usuario')
            ->select('usuarios.id_usuario', 'apellido_paterno', 'apellido_materno', 'nombres', 'usuario')
            ->whereBetween('id_perfil', [1, 3])
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

    public function validaInventario()
    {
        DB::statement("
            CREATE TEMPORARY TABLE temp_inventarios AS
            SELECT DISTINCT nro_inventario
            FROM ubicacion_exp
            where nro_inventario like 'are-%' ORDER BY nro_inventario;
        ");

        $result = DB::select("SELECT * FROM temp_inventarios ORDER BY nro_inventario DESC LIMIT 1");
        $ultimoRegistro = $result[0]->nro_inventario;
        $ultreg = substr($ultimoRegistro,4,6);

        DB::statement("
        CREATE TEMPORARY TABLE numeros_temp (n INT PRIMARY KEY);
        ");

        DB::statement("INSERT INTO numeros_temp (n)
        SELECT ones.n + tens.n * 10 + hunds.n * 100 + thous.n * 1000 + t_thous.n * 10000 + h_thous.n * 100000 AS n
        FROM 
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) ones,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) tens,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) hunds,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) thous,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) t_thous,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) h_thous
        WHERE (ones.n + tens.n*10 + hunds.n*100 + thous.n*1000 + t_thous.n*10000 + h_thous.n*100000) BETWEEN 1 AND ". $ultreg ."; ");  

        $are_faltantes = DB::select("
        WITH faltantes AS (
            SELECT n.n AS numero
            FROM numeros_temp n
            LEFT JOIN temp_inventarios t 
                ON t.nro_inventario = CONCAT('ARE-', LPAD(n.n, 6, '0'))
            WHERE t.nro_inventario IS NULL
        ),
        rangos AS (
            SELECT 
                MIN(numero) AS inicio,
                MAX(numero) AS fin
            FROM (
                SELECT 
                    numero,
                    numero - ROW_NUMBER() OVER (ORDER BY numero) AS grp
                FROM faltantes
            ) t
            GROUP BY grp
        )
        SELECT CONCAT('ARE-', LPAD(inicio, 6, '0')) as rangodesde, CONCAT('ARE-', LPAD(fin, 6, '0')) AS rangohasta
        FROM rangos
        ORDER BY inicio;
        "); //SELECT CONCAT('ARE-', LPAD(inicio, 6, '0'), ' al ARE-', LPAD(fin, 6, '0')) AS rango_faltante


        // Eliminar la tabla temporal
        DB::statement("DROP TEMPORARY TABLE IF EXISTS temp_inventarios");
        DB::statement("DROP TEMPORARY TABLE IF EXISTS numeros_temp");
        







        DB::statement("
            CREATE TEMPORARY TABLE temp_inventarios AS
            SELECT DISTINCT nro_inventario
            FROM ubicacion_exp
            where nro_inventario like 'int25%' ORDER BY nro_inventario;
        ");

        $result = DB::select("SELECT * FROM temp_inventarios ORDER BY nro_inventario DESC LIMIT 1");
        $ultimoRegistro = $result[0]->nro_inventario;
        $ultreg = substr($ultimoRegistro,5,5);

        DB::statement("
        CREATE TEMPORARY TABLE numeros_temp (n INT PRIMARY KEY);
        ");

        DB::statement("INSERT INTO numeros_temp (n)
        SELECT ones.n + tens.n * 10 + hunds.n * 100 + thous.n * 1000 + t_thous.n * 10000 + h_thous.n * 100000 AS n
        FROM 
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) ones,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) tens,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) hunds,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) thous,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) t_thous,
        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) h_thous
        WHERE (ones.n + tens.n*10 + hunds.n*100 + thous.n*1000 + t_thous.n*10000 + h_thous.n*100000) BETWEEN 1 AND ". $ultreg ."; ");  

        $int_faltantes = DB::select("
        WITH faltantes AS (
            SELECT n.n AS numero
            FROM numeros_temp n
            LEFT JOIN temp_inventarios t 
                ON t.nro_inventario = CONCAT('int25', LPAD(n.n, 5, '0'))
            WHERE t.nro_inventario IS NULL
        ),
        rangos AS (
            SELECT 
                MIN(numero) AS inicio,
                MAX(numero) AS fin
            FROM (
                SELECT 
                    numero,
                    numero - ROW_NUMBER() OVER (ORDER BY numero) AS grp
                FROM faltantes
            ) t
            GROUP BY grp
        )
        SELECT CONCAT('INT25', LPAD(inicio, 5, '0')) as rangodesde, CONCAT('INT25', LPAD(fin, 5, '0')) AS rangohasta
        FROM rangos
        ORDER BY inicio;
        "); //SELECT CONCAT('ARE-', LPAD(inicio, 6, '0'), ' al ARE-', LPAD(fin, 6, '0')) AS rango_faltante


        // Eliminar la tabla temporal
        DB::statement("DROP TEMPORARY TABLE IF EXISTS temp_inventarios");
        DB::statement("DROP TEMPORARY TABLE IF EXISTS numeros_temp");


$nosonareint = DB::select("
SELECT distinct nro_inventario FROM `ubicacion_exp` where nro_inventario not like 'are-%' and nro_inventario not like 'int25%' order by nro_inventario;
");








        //return response()->json($result);


        return view('inventario.validainventario', compact('are_faltantes','int_faltantes','nosonareint'));
    }    


    public function validaImprime(Request $request)
    {
        $arefaltantes = $request->arefaltantes;
        $intfaltantes = $request->intfaltantes;
        $nosonareint = $request->nosonareint;

        $html1 = '
            <style>
                table { border-collapse: collapse; }
                td { border: 1px solid #000; padding: 4px; }
            </style>
                <table style="font-size: 10px;">
                    <tr>
                        <td><b>DESDE</b></td>
                        <td><b>HASTA</b></td>
                    </tr>
                <tbody>';
                foreach ($arefaltantes as $p) {
                    if ($p['rangodesde']==$p['rangohasta']) {
                    $html1 .= "<tr>
                                <td align='center'>{$p['rangodesde']}</td>
                                <td></td>
                            </tr>";

                    } else {
                    $html1 .= "<tr>
                                <td align='center'>{$p['rangodesde']}</td>
                                <td align='center'>{$p['rangohasta']}</td>
                            </tr>";
                    }
                }
        $html1 .= '</tbody></table>';

        $html2 = '<table style="font-size: 10px;">
                    <tr>
                        <td><b>DESDE</b></td>
                        <td><b>HASTA</b></td>
                    </tr>
                <tbody>';
                foreach ($intfaltantes as $p) {
                    if ($p['rangodesde']==$p['rangohasta']) {
                    $html2 .= "<tr>
                                <td align='center'>{$p['rangodesde']}</td>
                                <td></td>
                            </tr>";

                    } else {
                    $html2 .= "<tr>
                                <td align='center'>{$p['rangodesde']}</td>
                                <td align='center'>{$p['rangohasta']}</td>
                            </tr>";
                    }
                }
        $html2 .= '</tbody></table>';


        $html3 = '
                <table style="font-size: 10px;">
                    <tr>
                        <td><b>NRO INVENTARIO</b></td>
                    </tr>';
                foreach ($nosonareint as $p) {
                    $html3 .= "<tr>
                                <td align='center'>{$p['nro_inventario']}</td>
                            </tr>";
                }
        $html3 .= '</table>';

        $mpdf = new Mpdf([
            'mode' => 'c',
            'format' => 'A4-P',
            'default_font_size' => 10,
            'default_font' => 'Arial',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 5,
            'margin_bottom' => 3,
            'margin_header' => 1,
            'margin_footer' => 1
        ]);        

        $mpdf->WriteHTML("<h3>FALTANTES CON PREFIJO ARE</h3>");
        $mpdf->SetColumns(4);
        $mpdf->WriteHTML($html1);
        $mpdf->SetColumns(1);
//$mpdf->AddPage();

        $mpdf->WriteHTML("<h3>FALTANTES CON PREFIJO INT25</h3>");
        $mpdf->SetColumns(4);
        $mpdf->WriteHTML($html2);
        $mpdf->SetColumns(1);

        $mpdf->WriteHTML("<h3>NO CORRESPONDEN</h3>");
        $mpdf->WriteHTML($html3);


        $pdfContent = $mpdf->Output('', 'S'); // 'S' = devuelve el contenido como string
        return response($pdfContent, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="valida.pdf"');



    }    

}
