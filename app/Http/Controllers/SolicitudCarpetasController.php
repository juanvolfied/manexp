<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Mpdf\Mpdf;
use App\Services\BarcodeGenerator;

class SolicitudCarpetasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexSolicitud()
    {
        //$guiacab = MovimientoExp_Cab::all();
        $guiacab = DB::table('movimiento_exp_cab')
        ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
        ->where('tipo_mov','SO')
        ->where(function($query) {
            $query->where('id_usuario', Auth::user()->id_usuario)
                ->orWhere('fiscal', Auth::user()->id_personal); 
        })
        ->select('movimiento_exp_cab.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres','dependencia.abreviado') 
        //->orderByRaw("FIELD(movimiento_exp_cab.estado_mov, 'G', 'Z', 'E', 'R')")
        ->orderBy('fechahora_movimiento', 'desc') 
        ->orderBy('ano_mov', 'desc') 
        ->orderBy('nro_mov', 'desc') 
        ->get();
        return view('expediente_movs.indexsolicitud',compact('guiacab'));
    }
    public function createSolicitud()
    {
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
        return view('expediente_movs.solicitud', compact('personal','dependencia'));
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
    public function buscaCarpetaSolicitud(Request $request)
    {
        if ($request->has('codbarras')) {        
            $request->validate([
                'codbarras' => 'required|string',
            ]);
            $codbar = $request->input('codbarras');
    /*
            $dep_exp=substr($codbar,0,11);
            $ano_exp=substr($codbar,11,4);
            $nro_exp=substr($codbar,15,6);
            $tip_exp=substr($codbar,21,4);
            $dep_exp = (int) $dep_exp;
            $nro_exp = (int) $nro_exp; 
            $existe = Expedientes::where('id_dependencia', $dep_exp)
                ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
                ->where('ano_expediente', $ano_exp)
                ->where('nro_expediente', $nro_exp)
                ->where('id_tipo', $tip_exp)
                ->first();
    */
            $existe = DB::table('expediente')->where('codbarras', $codbar)
                ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
                ->first();

            if ($existe) {
                $estado = $existe->estado; 
                if ($estado=="I") {
                    $existe2 = DB::table('movimiento_exp_det')
                    ->where('id_expediente', $existe->id_expediente)
                    ->where('tipo_mov', "SO")
                    ->where('estado_mov', "S")
                        ->first();
                    if ($existe2) {
                        return response()->json([
                            'success' => false,
                            'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' SE ENCUENTRA EN OTRA SOLICITUD.'),
                        ]);                            
                    } else {
                        return response()->json([
                            'success' => true,
                            'id_expediente' => $existe->id_expediente,
                            'imputado' => $existe->imputado,
                            'agraviado' => $existe->agraviado,
                            'desc_delito' => $existe->desc_delito,
                            'nro_folios' => $existe->nro_folios,
                            'message' => 'OK'
                        ]);        
                    }
                } else {

                    return response()->json([
                        'success' => false,
                        'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' SE ENCUENTRA FUERA DE ARCHIVO.')
                    ]);

                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' NO HA SIDO REGISTRADO.'),
                ]);        
            }
        
        }
        // Si viene año y nro expediente
        elseif ($request->has('ano') && $request->has('nroexp')) {
            $ano_expediente = $request->input('ano');    
            $nro_expediente = $request->input('nroexp');    

            $query = DB::table('expediente')
                ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
                ->select('expediente.*','delito.desc_delito');
            if (!empty($ano_expediente)) {
                $query->where('expediente.ano_expediente', $ano_expediente);
            }
            if (!empty($nro_expediente)) {
                //$query->where('expediente.nro_expediente', 'like', "%{$nro_expediente}%");
                $query->where('expediente.nro_expediente', 'like', "{$nro_expediente}");
            }
            $query->where('expediente.estado', 'I');
            $segdetalle = $query
                ->orderBy('codbarras', 'asc')
                ->get();

            if ($segdetalle->isNotEmpty()) {
                $numeroRegistros = $segdetalle->count();
                $segdetalle->transform(function ($doc) {
                    $existe2 = DB::table('movimiento_exp_det')
                    ->where('id_expediente', $doc->id_expediente)
                    ->where('tipo_mov', "SO")
                    ->where('estado_mov', "S")
                    ->first();
                    $doc->otrasolicitud = $existe2; // true o false
                    return $doc;
                });
                return response()->json([
                    'success' => true,
                    'registros' => $segdetalle,
                    'nroregistros' => $numeroRegistros,
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'NO SE ENCONTRARON CARPETAS FISCALES INTERNADAS EN ARCHIVO CON LOS DATOS PROPORCIONADOS.',
                ]);
            }

        }
        // Si no viene ninguno o vienen incompletos
        else {
                return response()->json([
                    'success' => false,
                    'message' => utf8_encode('DEBE INGRESAR UN CODIGO DE CARPETA FISCAL O EL AÑO Y NUMERO DE EXPEDIENTE.'),
                ]);   
        }


    }
    public function grabaSolicitud(Request $request)
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

        $ultimoRegistro = DB::table('movimiento_exp_cab')
        ->where('ano_mov', $anoActual)
        ->where('tipo_mov', 'SO')
        ->orderBy('ano_mov', 'desc')
            ->orderBy('nro_mov', 'desc')
            ->first();
        $nromov=0;
        if ($ultimoRegistro) {
            $nromov = $ultimoRegistro->nro_mov;
        }
        $nromov++;
        DB::table('movimiento_exp_cab')->insert([
            'nro_mov'                => $nromov,
            'ano_mov'                => $anoActual,
            'tipo_mov'               => 'SO',
            'id_usuario'             => Auth::user()->id_usuario,
            'fiscal'                 => $request->codfiscal,
            'fechahora_movimiento'   => $fechaHoraActualFormateada,
            'estado_mov'             => 'G',
            'activo'                 => 'S',
            'cantidad_exp'           => $itemsCount,
            'id_dependencia'         => Auth::user()->personal->id_dependencia,
            'despacho'               => Auth::user()->personal->despacho,
        ]);
        foreach ($scannedItems as $item) {
            $codbar = $item['codbarras'];
            $dep_exp=substr($codbar,0,11);
            $ano_exp=substr($codbar,11,4);
            $nro_exp=substr($codbar,15,6);
            $tip_exp=substr($codbar,21,4);
            $dep_exp = (int) $dep_exp; 
            $id_exp = $item['id_expediente'];
            
            DB::table('movimiento_exp_det')->insert([
                'nro_mov'         => $nromov,
                'ano_mov'         => $anoActual,
                'tipo_mov'        => 'SO',
                'id_expediente'   => $id_exp,
                'nro_expediente'  => $nro_exp,
                'ano_expediente'  => $ano_exp,
                'id_dependencia'  => $dep_exp,
                'id_tipo'         => $tip_exp,
                'observacion'     => '',
                'estado_mov'      => 'G',
            ]);            
        }
        return redirect()->route('solicitud.index')->with('success', 'SOLICITUD GENERADA DE FORMA SATISFACTORIA.');
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
        return redirect()->route('solicitud.index')->with('success', 'SOLICITUD DE CARPETAS ACTUALIZADA DE FORMA SATISFACTORIA.');
    }


    public function envioSolicitud(Request $request)
    {    
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);

        $tipo_mov = $request->input('tipo_mov');
        $ano_mov = $request->input('ano_mov');
        $nro_mov = $request->input('nro_mov');
        DB::table('movimiento_exp_cab')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'S',
            'fechahora_solicitud' => $fechaHoraActualFormateada
        ]);
        DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'S'
        ]);//estado_mov = S = Solicitado


        return response()->json([
            'success' => true,
            'redirect_url' => route('solicitud.index'),
            'message' => 'ENVIO REALIZADO CORRECTAMENTE.',
        ]);
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





    public function indexAtencionSolicitud()
    {
        $guiacab = DB::table('movimiento_exp_cab')
        ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
        ->where('tipo_mov','SO')
        ->where('estado_mov','<>','G')
        ->where('estado_mov', '<>', 'Z')
        ->select('movimiento_exp_cab.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres','dependencia.abreviado') 
        //->orderByRaw("FIELD(movimiento_exp_cab.estado_mov, 'E', 'G', 'R')")
        ->orderBy('ano_mov', 'desc') 
        ->orderBy('nro_mov', 'desc') 
        ->get();
        return view('expediente_movs.solicitudatencion',compact('guiacab'));
    }
    public function verifAtencionSolicitud($tipo_mov, $ano_mov, $nro_mov)
    {
        $guiacab = DB::table('movimiento_exp_cab')
        ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->select('movimiento_exp_cab.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres','dependencia.descripcion') // Puedes ajustar campos
        ->first();

        $segdetalle = DB::table('movimiento_exp_det')
            ->where('movimiento_exp_det.tipo_mov', $tipo_mov)
            ->where('movimiento_exp_det.ano_mov', $ano_mov)
            ->where('movimiento_exp_det.nro_mov', $nro_mov)
            ->where('ubicacion_exp.activo', 'S')
            ->leftJoin('expediente', 'movimiento_exp_det.id_expediente', '=', 'expediente.id_expediente')
            ->leftJoin('ubicacion_exp', 'movimiento_exp_det.id_expediente', '=', 'ubicacion_exp.id_expediente')
            ->select(
                'movimiento_exp_det.*',
                'expediente.codbarras',
                'ubicacion_exp.archivo',
                'ubicacion_exp.anaquel',
                'ubicacion_exp.nro_paquete',
                'ubicacion_exp.serie',
                'ubicacion_exp.tomo'
            )
            ->orderBy('id_movimiento', 'asc') 
            ->orderBy('tomo', 'asc') 
            ->get();


        return view('expediente_movs.solicitudenviocarpetas', compact('segdetalle','guiacab'));
    }

    
    public function grabaAtencionSolicitud(Request $request)
    {    
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);
        $tipo_mov = $request->input('tipo_mov');
        $ano_mov = $request->input('ano_mov');
        $nro_mov = $request->input('nro_mov');
        DB::table('movimiento_exp_cab')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'E',
            'fechahora_envio' => $fechaHoraActualFormateada,
            'activo' => 'S',
            'cantidad_exp_recep' => DB::raw('cantidad_exp')
        ]);

        $ultimoRegistro = DB::table('ubicacion_exp')
            ->where('ano_movimiento', $anoActual)
            ->orderBy('ano_movimiento', 'desc')
            ->orderBy('nro_movimiento', 'desc')
            ->first();
        $nromov=0;
        if ($ultimoRegistro) {
            $nromov = $ultimoRegistro->nro_movimiento;
        }


        $registrosOrigen = DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->where('estado_mov', 'S')        
        ->get();
        foreach ($registrosOrigen as $registro) {
            DB::table('expediente')
            ->where('id_expediente', $registro->id_expediente)
            ->update([
                'estado' => 'T'//TRANSITO
            ]);

            $reg_exp_tomo = DB::table('ubicacion_exp')
            ->where('id_expediente', $registro->id_expediente)
            ->where('activo', "S")
            ->select('tomo','archivo','anaquel','nro_paquete','nro_inventario','serie','acompanados','cuadernos')
            ->distinct()
            ->get();
            foreach ($reg_exp_tomo as $regtomo) {

                DB::table('ubicacion_exp')
                ->where('id_expediente', $registro->id_expediente)
                ->where('tomo', $regtomo->tomo)
                ->where('activo', "S")
                ->update([
                    'activo' => 'N',
                ]);
                $nromov++;
                DB::table('ubicacion_exp')->insert([
                    'nro_movimiento' => $nromov,
                    'ano_movimiento' => $anoActual,
                    'id_personal' => Auth::user()->id_personal,
                    'id_usuario' => Auth::user()->id_usuario,
                    'archivo' => $regtomo->archivo,
                    'anaquel' => $regtomo->anaquel,
                    'nro_paquete' => $regtomo->nro_paquete,
                    'nro_inventario' => $regtomo->nro_inventario,
                    'id_expediente' => $registro->id_expediente,
                    'nro_expediente' => $registro->nro_expediente,
                    'ano_expediente' => $registro->ano_expediente,
                    'id_dependencia' => $registro->id_dependencia,
                    'id_tipo' => $registro->id_tipo,
                    'tomo' => $regtomo->tomo,
                    'serie' => $regtomo->serie,
                    'acompanados' => $regtomo->acompanados,
                    'cuadernos' => $regtomo->cuadernos,
                    'ubicacion' => 'A',             // A=Archivo D=Despacho
                    'tipo_ubicacion' => 'T',        // I=Inventario T=Transito
                    'fecha_movimiento' => $fechaActual,
                    'hora_movimiento' => $horaActual,
                    'motivo_movimiento' => 'Solicitud',
                    'paq_dependencia' => Auth::user()->personal->id_dependencia,
                    'despacho' => Auth::user()->personal->despacho,
                    'activo' => 'S',
                    'estado' => 'E',//ENVIADO
                ]);
            }//filtro tomo
        }
        DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->where('estado_mov', 'S')        
        ->update([
            'estado_mov' => 'E'
        ]);

        //return response()->json(['success' => true]);
        return response()->json([
            'success' => true,
            'redirect_url' => route('solicitud.atencion'),
            'message' => 'ENVIO DE CARPETAS REALIZADA CORRECTAMENTE.',
        ]);
    }
    public function detalleRecepcionSolicitud(Request $request)
    {
        $tipo_mov = $request->input('tpmov');    
        $ano_mov = $request->input('anomov');    
        $nro_mov = $request->input('nromov');    

        $guiacab = DB::table('movimiento_exp_cab')
        ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->select('movimiento_exp_cab.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres','dependencia.descripcion') // Puedes ajustar campos
        ->first();

        $segdetalle = DB::table('movimiento_exp_det')
            ->where('movimiento_exp_det.tipo_mov', $tipo_mov)
            ->where('movimiento_exp_det.ano_mov', $ano_mov)
            ->where('movimiento_exp_det.nro_mov', $nro_mov)
            ->where('ubicacion_exp.activo', 'S')
            ->leftJoin('expediente', 'movimiento_exp_det.id_expediente', '=', 'expediente.id_expediente')
            ->leftJoin('ubicacion_exp', 'movimiento_exp_det.id_expediente', '=', 'ubicacion_exp.id_expediente')
            ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
            ->select(
                'movimiento_exp_det.*',
                'expediente.codbarras',
                'expediente.imputado',
                'expediente.agraviado',
                'delito.desc_delito',
                'expediente.nro_folios',
                'ubicacion_exp.tomo'
            )
            ->orderBy('id_movimiento', 'asc') 
            ->orderBy('tomo', 'asc') 
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
    public function grabaRecepcionCarpetasSolicitud(Request $request)
    {    
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);
        $tipo_mov = $request->input('tipo_mov');
        $ano_mov = $request->input('ano_mov');
        $nro_mov = $request->input('nro_mov');
        DB::table('movimiento_exp_cab')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'R',
            'fechahora_recepcion' => $fechaHoraActualFormateada,
            'activo' => 'S',
            'cantidad_exp_recep' => DB::raw('cantidad_exp')
        ]);

        $ultimoRegistro = DB::table('ubicacion_exp')
            ->where('ano_movimiento', $anoActual)
            ->orderBy('ano_movimiento', 'desc')
            ->orderBy('nro_movimiento', 'desc')
            ->first();
        $nromov=0;
        if ($ultimoRegistro) {
            $nromov = $ultimoRegistro->nro_movimiento;
        }


        $registrosOrigen = DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->where('estado_mov', 'E')        
        ->get();
        foreach ($registrosOrigen as $registro) {
            DB::table('expediente')
            ->where('id_expediente', $registro->id_expediente)
            ->update([
                'estado' => 'P'//PRESTADO
            ]);

            $reg_exp_tomo = DB::table('ubicacion_exp')
            ->where('id_expediente', $registro->id_expediente)
            ->where('activo', "S")
            ->select('tomo','archivo','anaquel','nro_paquete','nro_inventario','serie','acompanados','cuadernos')
            ->distinct()
            ->get();
            foreach ($reg_exp_tomo as $regtomo) {

                DB::table('ubicacion_exp')
                ->where('id_expediente', $registro->id_expediente)
                ->where('tomo', $regtomo->tomo)
                ->where('activo', "S")
                ->update([
                    'activo' => 'N',
                ]);
                $nromov++;
                DB::table('ubicacion_exp')->insert([
                    'nro_movimiento' => $nromov,
                    'ano_movimiento' => $anoActual,
                    'id_personal' => Auth::user()->id_personal,
                    'id_usuario' => Auth::user()->id_usuario,
                    'archivo' => $regtomo->archivo,
                    'anaquel' => $regtomo->anaquel,
                    'nro_paquete' => $regtomo->nro_paquete,
                    'nro_inventario' => $regtomo->nro_inventario,
                    'id_expediente' => $registro->id_expediente,
                    'nro_expediente' => $registro->nro_expediente,
                    'ano_expediente' => $registro->ano_expediente,
                    'id_dependencia' => $registro->id_dependencia,
                    'id_tipo' => $registro->id_tipo,
                    'tomo' => $regtomo->tomo,
                    'serie' => $regtomo->serie,
                    'acompanados' => $regtomo->acompanados,
                    'cuadernos' => $regtomo->cuadernos,                    
                    'ubicacion' => 'D',             // A=Archivo D=Despacho
                    'tipo_ubicacion' => 'T',        // I=Inventario T=Transito
                    'fecha_movimiento' => $fechaActual,
                    'hora_movimiento' => $horaActual,
                    'motivo_movimiento' => 'Solicitud',
                    'paq_dependencia' => Auth::user()->personal->id_dependencia,
                    'despacho' => Auth::user()->personal->despacho,
                    'activo' => 'S',
                    'estado' => 'P',//PRESTADO
                ]);
            }//filtro tomo
        }
        DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->where('estado_mov', 'E')        
        ->update([
            'estado_mov' => 'R'
        ]);

        //return response()->json(['success' => true]);
        return response()->json([
            'success' => true,
            'redirect_url' => route('solicitud.index'),
            'message' => 'RECEPCION DE CARPETAS REALIZADA CORRECTAMENTE.',
        ]);
    }






    public function indexDevolucion()
    {
        $guiacab = DB::table('movimiento_exp_cab')
        ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
        ->where('tipo_mov','DE')
        ->where(function($query) {
            $query->where('id_usuario', Auth::user()->id_usuario)
                ->orWhere('fiscal', Auth::user()->id_personal); 
        })
        ->select('movimiento_exp_cab.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres','dependencia.abreviado') 
        //->orderByRaw("FIELD(movimiento_exp_cab.estado_mov, 'G', 'Z', 'E', 'R')")
        ->orderBy('ano_mov', 'desc') 
        ->orderBy('nro_mov', 'desc') 
        ->get();
        return view('expediente_movs.indexdevolucion',compact('guiacab'));

    }
    public function createDevolucion()
    {
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
        return view('expediente_movs.devolucion', compact('personal','dependencia'));
    }
    public function editDevolucion($tipo_mov, $ano_mov, $nro_mov)
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
        return view('expediente_movs.devolucion', compact('regcab','regdet','obsmovimiento','personal','dependencia'));
    }

    public function buscaCarpetaDevolucion(Request $request)
    {
        if ($request->has('codbarras')) {        

            $request->validate([
                'codbarras' => 'required|string',
            ]);
            $codbar = $request->input('codbarras');
            $existe = DB::table('expediente')->where('codbarras', $codbar)
                ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
                ->first();

            if ($existe) {
    /*            $existe1 = DB::table('ubicacion_exp')
                ->where('id_expediente', $existe->id_expediente)
                ->where('activo', 'S')
                ->where('estado', 'D')
                ->first();*/


    //            if ($existe1) {
                $estado = $existe->estado; 
                if ($estado=="P") {//CARPETA FISCAL P=PRESTADO
                    $existe2 = DB::table('movimiento_exp_det')
                    ->where('id_expediente', $existe->id_expediente)
                    ->where('tipo_mov', "DE")
                    ->where(function($query) {
                        $query->where('estado_mov', 'G')
                            ->orWhere('estado_mov', 'E');
                    })
                    ->first();

                    if ($existe2) {
                        return response()->json([
                            'success' => false,
                            'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' SE ENCUENTRA EN OTRA DEVOLUCION.'),
                        ]);                            
                    } else {
                        return response()->json([
                            'success' => true,
                            'id_expediente' => $existe->id_expediente,
                            'imputado' => $existe->imputado,
                            'agraviado' => $existe->agraviado,
                            'desc_delito' => $existe->desc_delito,
                            'nro_folios' => $existe->nro_folios,
                            'message' => 'OK'
                        ]);        
                    }
                } else {

                    return response()->json([
                        'success' => false,
                        'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' NO SE ENCUENTRA EN DESPACHO.')
                    ]);

                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' NO HA SIDO REGISTRADO.'),
                ]);        
            }
            
        }
        // Si viene año y nro expediente
        elseif ($request->has('ano') && $request->has('nroexp')) {
            $ano_expediente = $request->input('ano');    
            $nro_expediente = $request->input('nroexp');    

            $query = DB::table('expediente')
                ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
                ->select('expediente.*','delito.desc_delito');
            if (!empty($ano_expediente)) {
                $query->where('expediente.ano_expediente', $ano_expediente);
            }
            if (!empty($nro_expediente)) {
                //$query->where('expediente.nro_expediente', 'like', "%{$nro_expediente}%");
                $query->where('expediente.nro_expediente', 'like', "{$nro_expediente}");
            }
            $query->where('expediente.estado', 'P');
            $segdetalle = $query
                ->orderBy('codbarras', 'asc')
                ->get();

            if ($segdetalle->isNotEmpty()) {
                $numeroRegistros = $segdetalle->count();
                $segdetalle->transform(function ($doc) {
                    $existe2 = DB::table('movimiento_exp_det')
                    ->where('id_expediente', $doc->id_expediente)
                    ->where('tipo_mov', "DE")
                    ->where(function($query) {
                        $query->where('estado_mov', 'G')
                            ->orWhere('estado_mov', 'E');
                    })
                    ->first();
                    $doc->otrasolicitud = $existe2; // true o false
                    return $doc;
                });
                return response()->json([
                    'success' => true,
                    'registros' => $segdetalle,
                    'nroregistros' => $numeroRegistros,
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'NO SE ENCONTRARON CARPETAS FISCALES PRESTADAS CON LOS DATOS PROPORCIONADOS.',
                ]);
            }

        }
        // Si no viene ninguno o vienen incompletos
        else {
                return response()->json([
                    'success' => false,
                    'message' => utf8_encode('DEBE INGRESAR UN CODIGO DE CARPETA FISCAL O EL AÑO Y NUMERO DE EXPEDIENTE.'),
                ]);   
        }


    }
    public function grabaDevolucion(Request $request)
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

        $ultimoRegistro = DB::table('movimiento_exp_cab')
        ->where('ano_mov', $anoActual)
        ->where('tipo_mov', 'DE')
        ->orderBy('ano_mov', 'desc')
            ->orderBy('nro_mov', 'desc')
            ->first();
        $nromov=0;
        if ($ultimoRegistro) {
            $nromov = $ultimoRegistro->nro_mov;
        }
        $nromov++;
        DB::table('movimiento_exp_cab')->insert([
            'nro_mov'                => $nromov,
            'ano_mov'                => $anoActual,
            'tipo_mov'               => 'DE',
            'id_usuario'             => Auth::user()->id_usuario,
            'fiscal'                 => $request->codfiscal,
            'fechahora_movimiento'   => $fechaHoraActualFormateada,
            'estado_mov'             => 'G',
            'activo'                 => 'S',
            'cantidad_exp'           => $itemsCount,
            'id_dependencia'         => Auth::user()->personal->id_dependencia,
            'despacho'               => Auth::user()->personal->despacho,
        ]);
        foreach ($scannedItems as $item) {
            $codbar = $item['codbarras'];
            $dep_exp=substr($codbar,0,11);
            $ano_exp=substr($codbar,11,4);
            $nro_exp=substr($codbar,15,6);
            $tip_exp=substr($codbar,21,4);
            $dep_exp = (int) $dep_exp; 
            $id_exp = $item['id_expediente'];
            
            DB::table('movimiento_exp_det')->insert([
                'nro_mov'         => $nromov,
                'ano_mov'         => $anoActual,
                'tipo_mov'        => 'DE',
                'id_expediente'   => $id_exp,
                'nro_expediente'  => $nro_exp,
                'ano_expediente'  => $ano_exp,
                'id_dependencia'  => $dep_exp,
                'id_tipo'         => $tip_exp,
                'observacion'     => '',
                'estado_mov'      => 'G',
            ]);            
        }
        return redirect()->route('devolucion.index')->with('success', 'MOVIMIENTO DE DEVOLUCION GENERADA DE FORMA SATISFACTORIA.');
    }
    public function updateDevolucion(Request $request, $tipo_mov, $ano_mov, $nro_mov)
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
        return redirect()->route('devolucion.index')->with('success', 'MOVIMIENTO DE DEVOLUCION DE CARPETAS ACTUALIZADA DE FORMA SATISFACTORIA.');
    }

    public function envioDevolucion(Request $request)
    {    
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);

        $tipo_mov = $request->input('tipo_mov');
        $ano_mov = $request->input('ano_mov');
        $nro_mov = $request->input('nro_mov');

        $ultimoRegistro = DB::table('ubicacion_exp')
            ->where('ano_movimiento', $anoActual)
            ->orderBy('ano_movimiento', 'desc')
            ->orderBy('nro_movimiento', 'desc')
            ->first();
        $nromov=0;
        if ($ultimoRegistro) {
            $nromov = $ultimoRegistro->nro_movimiento;
        }


        DB::table('movimiento_exp_cab')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'E',
            'fechahora_envio' => $fechaHoraActualFormateada
        ]);

        $registrosOrigen = DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->where('estado_mov', 'G')        
        ->get();
        foreach ($registrosOrigen as $registro) {
            DB::table('expediente')
            ->where('id_expediente', $registro->id_expediente)
            ->update([
                'estado' => 'T'//TRANSITO
            ]);

            $reg_exp_tomo = DB::table('ubicacion_exp')
            ->where('id_expediente', $registro->id_expediente)
            ->where('activo', "S")
            ->select('tomo','archivo','anaquel','nro_paquete','nro_inventario','serie','acompanados','cuadernos')
            ->distinct()
            ->get();
            foreach ($reg_exp_tomo as $regtomo) {

                DB::table('ubicacion_exp')
                ->where('id_expediente', $registro->id_expediente)
                ->where('tomo', $regtomo->tomo)
                ->where('activo', "S")
                ->update([
                    'activo' => 'N',
                ]);
                $nromov++;
                DB::table('ubicacion_exp')->insert([
                    'nro_movimiento' => $nromov,
                    'ano_movimiento' => $anoActual,
                    'id_personal' => Auth::user()->id_personal,
                    'id_usuario' => Auth::user()->id_usuario,
                    'archivo' => $regtomo->archivo,
                    'anaquel' => $regtomo->anaquel,
                    'nro_paquete' => $regtomo->nro_paquete,
                    'nro_inventario' => $regtomo->nro_inventario,
                    'id_expediente' => $registro->id_expediente,
                    'nro_expediente' => $registro->nro_expediente,
                    'ano_expediente' => $registro->ano_expediente,
                    'id_dependencia' => $registro->id_dependencia,
                    'id_tipo' => $registro->id_tipo,
                    'tomo' => $regtomo->tomo,
                    'serie' => $regtomo->serie,
                    'acompanados' => $regtomo->acompanados,
                    'cuadernos' => $regtomo->cuadernos,                    
                    'ubicacion' => 'D',             // A=Archivo D=Despacho
                    'tipo_ubicacion' => 'T',        // I=Inventario T=Transito
                    'fecha_movimiento' => $fechaActual,
                    'hora_movimiento' => $horaActual,
                    'motivo_movimiento' => 'Devolución',
                    'paq_dependencia' => Auth::user()->personal->id_dependencia,
                    'despacho' => Auth::user()->personal->despacho,
                    'activo' => 'S',
                    'estado' => 'D',//DEVUELTO
                ]);
            }//filtro tomo
        }

        DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'E'
        ]);//estado_mov = E = Enviado




        return response()->json([
            'success' => true,
            'redirect_url' => route('devolucion.index'),
            'message' => 'ENVIO REALIZADO CORRECTAMENTE.',
        ]);
    }

    public function indexAtencionDevolucion()
    {
        $guiacab = DB::table('movimiento_exp_cab')
        ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
        ->where('tipo_mov','DE')
        ->where('estado_mov','<>','G')
        ->where('estado_mov', '<>', 'Z')
        ->select('movimiento_exp_cab.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres','dependencia.abreviado') 
        //->orderByRaw("FIELD(movimiento_exp_cab.estado_mov, 'E', 'G', 'R')")
        ->orderBy('ano_mov', 'desc') 
        ->orderBy('nro_mov', 'desc') 
        ->get();
        return view('expediente_movs.devolucionatencion',compact('guiacab'));
    }
    public function verifAtencionDevolucion($tipo_mov, $ano_mov, $nro_mov)
    {
        $guiacab = DB::table('movimiento_exp_cab')
        ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->select('movimiento_exp_cab.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres','dependencia.descripcion') // Puedes ajustar campos
        ->first();

        $segdetalle = DB::table('movimiento_exp_det')
            ->where('movimiento_exp_det.tipo_mov', $tipo_mov)
            ->where('movimiento_exp_det.ano_mov', $ano_mov)
            ->where('movimiento_exp_det.nro_mov', $nro_mov)
            ->where('ubicacion_exp.activo', 'S')
            ->leftJoin('expediente', 'movimiento_exp_det.id_expediente', '=', 'expediente.id_expediente')
            ->leftJoin('ubicacion_exp', 'movimiento_exp_det.id_expediente', '=', 'ubicacion_exp.id_expediente')
            ->select(
                'movimiento_exp_det.*',
                'expediente.codbarras',
                'ubicacion_exp.archivo',
                'ubicacion_exp.anaquel',
                'ubicacion_exp.nro_paquete',
                'ubicacion_exp.serie',
                'ubicacion_exp.tomo'
            )
            ->orderBy('id_movimiento', 'asc') 
            ->orderBy('tomo', 'asc') 
            ->get();


        return view('expediente_movs.devolucioncarpetas', compact('segdetalle','guiacab'));
    }

    public function grabaRecepcionCarpetasDevolucion(Request $request)
    {    
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);
        $tipo_mov = $request->input('tipo_mov');
        $ano_mov = $request->input('ano_mov');
        $nro_mov = $request->input('nro_mov');
        DB::table('movimiento_exp_cab')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'D',//DEVUELTO
            'fechahora_recepcion' => $fechaHoraActualFormateada,
            'activo' => 'S',
            'cantidad_exp_recep' => DB::raw('cantidad_exp')
        ]);

        $ultimoRegistro = DB::table('ubicacion_exp')
            ->where('ano_movimiento', $anoActual)
            ->orderBy('ano_movimiento', 'desc')
            ->orderBy('nro_movimiento', 'desc')
            ->first();
        $nromov=0;
        if ($ultimoRegistro) {
            $nromov = $ultimoRegistro->nro_movimiento;
        }


        $registrosOrigen = DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->where('estado_mov', 'E')        
        ->get();
        foreach ($registrosOrigen as $registro) {
            DB::table('expediente')
            ->where('id_expediente', $registro->id_expediente)
            ->update([
                'estado' => 'I'
            ]);

            $reg_exp_tomo = DB::table('ubicacion_exp')
            ->where('id_expediente', $registro->id_expediente)
            ->where('activo', "S")
            ->select('tomo','archivo','anaquel','nro_paquete','nro_inventario','serie','acompanados','cuadernos')
            ->distinct()
            ->get();
            foreach ($reg_exp_tomo as $regtomo) {

                DB::table('ubicacion_exp')
                ->where('id_expediente', $registro->id_expediente)
                ->where('tomo', $regtomo->tomo)
                ->where('activo', "S")
                ->update([
                    'activo' => 'N',
                ]);
                $nromov++;
                DB::table('ubicacion_exp')->insert([
                    'nro_movimiento' => $nromov,
                    'ano_movimiento' => $anoActual,
                    'id_personal' => Auth::user()->id_personal,
                    'id_usuario' => Auth::user()->id_usuario,
                    'archivo' => $regtomo->archivo,
                    'anaquel' => $regtomo->anaquel,
                    'nro_paquete' => $regtomo->nro_paquete,
                    'nro_inventario' => $regtomo->nro_inventario,
                    'id_expediente' => $registro->id_expediente,
                    'nro_expediente' => $registro->nro_expediente,
                    'ano_expediente' => $registro->ano_expediente,
                    'id_dependencia' => $registro->id_dependencia,
                    'id_tipo' => $registro->id_tipo,
                    'tomo' => $regtomo->tomo,
                    'serie' => $regtomo->serie,
                    'acompanados' => $regtomo->acompanados,
                    'cuadernos' => $regtomo->cuadernos,                    
                    'ubicacion' => 'A',             // A=Archivo D=Despacho
                    'tipo_ubicacion' => 'I',        // I=Inventario T=Transito
                    'fecha_movimiento' => $fechaActual,
                    'hora_movimiento' => $horaActual,
                    'motivo_movimiento' => 'Devolución',
                    'paq_dependencia' => Auth::user()->personal->id_dependencia,
                    'despacho' => Auth::user()->personal->despacho,
                    'activo' => 'S',
                    'estado' => 'I',
                ]);
            }//filtro tomo
        }
        DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->where('estado_mov', 'E')        
        ->update([
            'estado_mov' => 'D'//DEVUELTO
        ]);

        //return response()->json(['success' => true]);
        return response()->json([
            'success' => true,
            'redirect_url' => route('devolucion.atencion'),
            'message' => 'RECEPCION DE CARPETAS REALIZADA CORRECTAMENTE.',
        ]);
    }











}
