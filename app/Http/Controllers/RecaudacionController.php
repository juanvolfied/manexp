<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Mpdf\Mpdf;
use App\Services\BarcodeGenerator;
use Carbon\Carbon;

class RecaudacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexRegistro()
    {
        $recauda = DB::table('recaudacion')
            ->leftJoin('dependencia', 'recaudacion.dependencia', '=', 'dependencia.id_dependencia')
            ->where('id_operador', Auth::user()->id_personal)
            ->select('recaudacion.*','dependencia.abreviado') 
            ->orderBy('id_recaudacion', 'desc') 
            ->get();
        return view('recaudacion.indexregistro',compact('recauda'));
    }
    public function registroDatos()
    {
        $dependencias = DB::table('dependencia')
            ->select('id_dependencia','descripcion')
            ->orderBy('descripcion', 'asc') 
            ->get();            
        $iddependencias = DB::table('expediente')
            ->distinct()
            ->select('id_dependencia')
            ->whereRaw('LENGTH(id_dependencia) >= 10')
            ->get();            
        return view('recaudacion.registrodatos', compact('iddependencias','dependencias'));
    }








    
    public function editSolicitud($tipo_mov, $ano_mov, $nro_mov)
    {
        $regcab = DB::table('movimiento_exp_cab')
            ->where('tipo_mov', $tipo_mov)
            ->where('ano_mov', $ano_mov)
            ->where('nro_mov', $nro_mov)
            ->first();
        $regdet = DB::table('movimiento_exp_det')
            ->where('tipo_mov', $tipo_mov)
            ->where('ano_mov', $ano_mov)
            ->where('nro_mov', $nro_mov)
            ->leftJoin('expediente', 'movimiento_exp_det.id_expediente', '=', 'expediente.id_expediente')
            ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
            ->select(
                'movimiento_exp_det.nro_expediente',
                'movimiento_exp_det.ano_expediente',
                'movimiento_exp_det.id_dependencia',
                'movimiento_exp_det.id_tipo',
                'expediente.codbarras',
                'expediente.imputado',
                'expediente.agraviado',
                'expediente.nro_folios',
                'movimiento_exp_det.id_expediente',
                'delito.desc_delito'
            )
            ->orderBy('movimiento_exp_det.id_movimiento', 'desc')
            ->get();
        $personal = DB::table('personal')
            ->where('fiscal_asistente','F')
            ->where('id_dependencia',Auth::user()->personal->id_dependencia)
            ->where('despacho',Auth::user()->personal->despacho)
            ->where('activo','S')            
            ->orderBy('apellido_paterno', 'asc') 
            ->orderBy('apellido_materno', 'asc') 
            ->orderBy('nombres', 'asc') 
            ->get();
        $dependencia = DB::table('dependencia')
            ->where('id_dependencia', Auth::user()->personal->id_dependencia)
            ->first();
        $obsmovimiento = DB::table('observacion_movimiento')
            ->where('tipo_mov', $tipo_mov)
            ->where('ano_mov', $ano_mov)
            ->where('nro_mov', $nro_mov)
            ->first();
        return view('expediente_movs.solicitud', compact('regcab','regdet','obsmovimiento','personal','dependencia'));
    }

    public function registroGraba(Request $request)
    {
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');
        $fechaActual = now()->format('Y-m-d');
        $horaActual = now()->format('H:i:s');
        $anoActual = substr($fechaActual, 0, 4);
        $carpeta =
            str_pad($request->iddep, 11, '0', STR_PAD_LEFT) .
            str_pad($request->anoexp, 4,  '0', STR_PAD_LEFT) .
            str_pad($request->nroexp, 6,  '0', STR_PAD_LEFT) .
            str_pad($request->tipo, 4,  '0', STR_PAD_LEFT);
        DB::beginTransaction();
        try {
            DB::table('recaudacion')->insert([
                'id_dependencia' => $request->iddep,
                'ano_expediente' => $request->anoexp,
                'nro_expediente' => $request->nroexp,
                'id_tipo'        => $request->tipo,
                'carpeta'        => $carpeta,
                'dependencia'    => $request->dependencia,
                'despacho'       => $request->despacho,
                'fecharegistro'  => $fechaActual,
                'voucher'        => $request->voucher,
                'monto'          => $request->filled('monto') ? $request->monto : null,
                'id_operador'    => Auth::user()->id_personal,
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "DATOS GUARDADOS DE FORMA SATISFACTORIA",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Opcional: loguear el error
            //Log::error('Error al grabar solicitud: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => "OCURRIÓ UN ERROR AL GRABAR LOS DATOS. INTENTA NUEVAMENTE",
            ]);
        }
    }



    public function updateSolicitud(Request $request, $tipo_mov, $ano_mov, $nro_mov)
    {
        $request->validate([
            'codfiscal' => 'required|string',
            'scannedItems' => 'required|json', // Validamos que sea un JSON
        ]);
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);

        // Convertir el JSON a un array
        $scannedItems = json_decode($request->scannedItems, true);
        $itemsCount = count($scannedItems);

        DB::beginTransaction();
        try {        

            DB::table('movimiento_exp_cab')
            ->where('tipo_mov', $tipo_mov)
            ->where('ano_mov', $ano_mov)
            ->where('nro_mov', $nro_mov)
            ->update([
                'id_usuario'=> Auth::user()->id_usuario, 
                'fiscal'=>$request->codfiscal, 
                'fechahora_movimiento'=>$fechaHoraActualFormateada, 
                'cantidad_exp'=>$itemsCount,                
                'id_dependencia'=>Auth::user()->personal->id_dependencia,                
                'despacho'=>Auth::user()->personal->despacho                
            ]);
            DB::table('movimiento_exp_det')
            ->where('tipo_mov', $tipo_mov)
            ->where('ano_mov', $ano_mov)
            ->where('nro_mov', $nro_mov)
            ->delete();

            foreach ($scannedItems as $item) {
                $codbar = $item['codbarras'];
                $dep_exp=substr($codbar,0,11);
                $ano_exp=substr($codbar,11,4);
                $nro_exp=substr($codbar,15,6);
                $tip_exp=substr($codbar,21,4);
                $dep_exp = (int) $dep_exp; 
                $id_exp = $item['id_expediente'];
                
                DB::table('movimiento_exp_det')->insert([
                    'nro_mov'         => $nro_mov,
                    'ano_mov'         => $ano_mov,
                    'tipo_mov'        => $tipo_mov,
                    'id_expediente'   => $id_exp,
                    'nro_expediente'  => $nro_exp,
                    'ano_expediente'  => $ano_exp,
                    'id_dependencia'  => $dep_exp,
                    'id_tipo'         => $tip_exp,
                    'observacion'     => '',
                    'estado_mov'      => 'G',
                ]);            
                
            }

            DB::commit();

            return redirect()->route('solicitud.index')->with('success', 'SOLICITUD DE CARPETAS ACTUALIZADA DE FORMA SATISFACTORIA.');
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();

            // Puedes registrar el error si deseas hacer seguimiento
            // Log::error('Error al actualizar solicitud: ' . $e->getMessage());

            return redirect()->back()->with('messageErr', 'OCURRIÓ UN ERROR AL ACTUALIZAR LA SOLICITUD. INTENTA NUEVAMENTE.');
        }


    }


    public function generarGuiaIntPDF($tipo_mov, $ano_mov, $nro_mov)
    {
        $regcab = DB::table('movimiento_exp_cab')
            ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
            ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
            ->where('movimiento_exp_cab.tipo_mov', $tipo_mov)
            ->where('movimiento_exp_cab.ano_mov', $ano_mov)
            ->where('movimiento_exp_cab.nro_mov', $nro_mov)
            ->select(
                'movimiento_exp_cab.*',
                'personal.apellido_paterno',
                'personal.apellido_materno',
                'personal.nombres',
                'dependencia.descripcion',
                'dependencia.abreviado'
            )
            ->first();
        $regdet = DB::table('movimiento_exp_det')
            ->where('movimiento_exp_det.tipo_mov', $tipo_mov)
            ->where('movimiento_exp_det.ano_mov', $ano_mov)
            ->where('movimiento_exp_det.nro_mov', $nro_mov)
            ->leftJoin('expediente', 'movimiento_exp_det.id_expediente', '=', 'expediente.id_expediente')
            ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
            ->select(
                'movimiento_exp_det.nro_expediente',
                'movimiento_exp_det.ano_expediente',
                'movimiento_exp_det.id_dependencia',
                'movimiento_exp_det.id_tipo',
                'expediente.codbarras',
                'expediente.nro_folios',
                'expediente.agraviado',
                'expediente.imputado',
                'delito.desc_delito',
                'movimiento_exp_det.id_expediente'
            )
            ->orderBy('movimiento_exp_det.id_movimiento', 'desc')
            ->get();
        
        $barcodeData = str_pad($nro_mov, 5, '0', STR_PAD_LEFT) ."-".$ano_mov."-". ( $tipo_mov == 'GI' ? 'I' : $tipo_mov );

        // Usa el servicio BarcodeGenerator
        $barcodeService = new BarcodeGenerator();
        //$barcodePng = $barcodeService->generate('',"*".$barcodeData."*", 20, 'vertical', 'code128', true,1);
        $barcodePng = $barcodeService->generate('',$barcodeData, 20, 'vertical', 'code128', true,1);

        // Codifica en base64
        $barcode = base64_encode($barcodePng);
        $html = view('expediente_movs.pdfguiainternamiento', compact('regcab','regdet','barcode'))->render(); // Vista Blade

        $mpdf = new Mpdf([
            'mode' => 'c',
            'format' => 'A4-L',
            'default_font_size' => 10,
            'default_font' => 'Arial',
            'margin_left' => 5,
            'margin_right' => 10,
            'margin_top' => 3,
            'margin_bottom' => 3,
            'margin_header' => 1,
            'margin_footer' => 1
        ]);        
        $mpdf->WriteHTML($html);

        $pdfContent = $mpdf->Output('', 'S'); // 'S' = devuelve el contenido como string
        return response($pdfContent, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="guia_'.$tipo_mov.'_'.$ano_mov.'_'.$nro_mov.'.pdf"');

    }









    public function estadisticaUsuarios()
    {
        return view('recaudacion.estadisticausuarios');
    }
    public function estadisticasdetalle(Request $request)
    {
        $request->validate([
            'fechainicio' => 'required|date',
            'fechafin' => 'required|date|after_or_equal:fechainicio',
        ]);

        // Convertimos a objetos Carbon
        $fechainicio = Carbon::parse($request->fechainicio)->startOfDay();
        $fechafin = Carbon::parse($request->fechafin)->endOfDay();
        //$tipo = $request->tipo;
        $tipo = 2;

        // Generar un array con todas las fechas del rango
        $periodo = [];
        $personales = [];
        $conteos = [];        
        for ($date = $fechainicio->copy(); $date->lte($fechafin); $date->addDay()) {
            $periodo[] = $date->format('Y-m-d');
        }
        $colores = [
            '#3498db', // azul
            '#e74c3c', // rojo
            '#2ecc71', // verde
            '#f1c40f', // amarillo
            '#9b59b6', // púrpura
            'rgba(180, 47, 47, 1)', // 
            '#e67e22', // naranja
            '#34495e', // gris oscuro
            '#95a5a6', // gris claro
            '#d35400', // naranja oscuro
        ];        

        if ($tipo=="1") {//por dependencias
            $datos = DB::table('libroescritos')
                ->join('dependencia', 'libroescritos.id_dependencia', '=', 'dependencia.id_dependencia')
                ->selectRaw('DATE(fecharegistro) as fecha, libroescritos.id_dependencia, abreviado, COUNT(*) as total')
                ->whereBetween('fecharegistro', [$fechainicio, $fechafin])
                ->where('libroescritos.id_dependencia',$datoadd)
                ->groupBy('fecha', 'libroescritos.id_dependencia', 'abreviado')
                ->orderBy('fecha')
                ->get();

            // Reorganizamos los datos
            foreach ($datos as $row) {
                $fecha = $row->fecha;
                $nompersonal = $row->abreviado;
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
        }
        if ($tipo=="2") {//por fiscal
            $datos = DB::table('recaudacion')
                ->join('personal', 'recaudacion.id_operador', '=', 'personal.id_personal')
                ->selectRaw('DATE(fecharegistro) as fecha, recaudacion.id_operador, apellido_paterno, apellido_materno, nombres, COUNT(*) as total')
                ->whereBetween('fecharegistro', [$fechainicio, $fechafin])
                ->groupBy('fecha', 'recaudacion.id_operador', 'apellido_paterno','apellido_materno','nombres')
                ->orderBy('fecha')
                ->get();

            // Reorganizamos los datos
            foreach ($datos as $row) {
                $fecha = $row->fecha;
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
        }
        if ($tipo=="3") {//por tipo de documento
            $datos = DB::table('libroescritos')
                ->selectRaw('DATE(fecharegistro) as fecha, libroescritos.tipo, COUNT(*) as total')
                ->whereBetween('fecharegistro', [$fechainicio, $fechafin])
                ->groupBy('fecha', 'libroescritos.tipo')
                ->orderBy('fecha')
                ->get();

            $datotipo = [
            'E' => 'Escrito',
            'O' => 'Oficio',
            'S' => 'Solicitud',
            'C' => 'Carta',
            'I' => 'Invitación',
            'F' => 'Informe',
            'Z' => 'OTROS',
            '' => 'X',
            ]; 


            // Reorganizamos los datos
            foreach ($datos as $row) {
                $fecha = $row->fecha;
                $nompersonal = $datotipo[$row->tipo];
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
        }
        if ($tipo=="4") {//por Operador
            $datos = DB::table('libroescritos')
                ->join('personal', 'libroescritos.id_personal', '=', 'personal.id_personal')
                ->selectRaw('DATE(fecharegistro) as fecha, libroescritos.id_personal, apellido_paterno, apellido_materno, nombres, COUNT(*) as total')
                ->whereBetween('fecharegistro', [$fechainicio, $fechafin])
                ->where('libroescritos.id_personal',$datoadd)
                ->groupBy('fecha', 'libroescritos.id_personal', 'apellido_paterno','apellido_materno','nombres')
                ->orderBy('fecha')
                ->get();

            // Reorganizamos los datos
            foreach ($datos as $row) {
                $fecha = $row->fecha;
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
        }

        // Finalmente construimos el array para Chart.js
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


}
