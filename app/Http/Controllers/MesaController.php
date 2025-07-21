<?php

namespace App\Http\Controllers;

use App\Models\Expedientes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Mpdf\Mpdf;
use App\Services\BarcodeGenerator;

class MesaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $libroescritos = DB::table('libroescritos')
        ->leftJoin('personal', 'libroescritos.id_fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'libroescritos.id_dependencia', '=', 'dependencia.id_dependencia')
        ->select(
            'libroescritos.anolibro',
            'libroescritos.numero',
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
            'libroescritos.folios'
        )
        ->wheredate('fecharegistro', date('Y-m-d'))
//        ->wheredate('fecharegistro', date('Y-m-d', strtotime('-2 day')))
        ->orderBy('fecharegistro', 'desc') 
        ->get();

        return view('mesapartes.index', compact('libroescritos'));
    }
    public function nuevoEscrito()
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

        $deppoli = DB::table('dependenciapolicial')
            ->orderBy('descripciondep', 'asc') 
            ->get();

        return view('mesapartes.registroescritos', compact('fiscales','deppoli'));
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
            ->orderBy('numero', 'desc')
            ->get();

        if ($segdetalle->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'registros' => $segdetalle,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'NO SE ENCONTRARON CARPETAS FISCALES CON LOS DATOS PROPORCIONADOS.',
            ]);
        }

    }
    public function generarConsFiscalPDF($id_fiscal, $fechareg)
    {  
        $datosfiscal = DB::table('personal')
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
        ->where('id_personal', $id_fiscal)
        ->first();

        $query = DB::table('libroescritos')
            ->select('*')
            ->where('libroescritos.id_fiscal', $id_fiscal)
            ->whereDate('fecharegistro', '=', $fechareg);        
        $segdetalle = $query
            ->orderBy('numero', 'desc')
            ->get();
        
        $html = view('mesapartes.pdfconsultaescritosfiscal', compact('segdetalle','datosfiscal', 'fechareg'))->render(); // Vista Blade

        $mpdf = new Mpdf([
            'mode' => 'c',
            'format' => 'A4-P',
            'default_font_size' => 10,
            'default_font' => 'Arial',
            'margin_left' => 10,
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
        ->header('Content-Disposition', 'inline; filename="escritos_'.$id_fiscal.'.pdf"');

    }
    public function generarCodigoBarrasPDF($codigogenerar)
    {
        
        //$barcodeData = str_pad($nro_mov, 5, '0', STR_PAD_LEFT) ."-".$ano_mov."-". ( $tipo_mov == 'GI' ? 'I' : $tipo_mov );
        $barcodeData = $codigogenerar;

        // Usa el servicio BarcodeGenerator
        $barcodeService = new BarcodeGenerator();
        //$barcodePng = $barcodeService->generate('',"*".$barcodeData."*", 20, 'horizontal', 'code128', true,2);
        $barcodePng = $barcodeService->generate('',"*".$barcodeData."*", 20, 'vertical', 'code128', true,1);

        // Codifica en base64
        $barcode = base64_encode($barcodePng);
        //$html = view('expediente_movs.pdfguiainternamiento', compact('regcab','regdet','barcode'))->render(); // Vista Blade
        $html = view('mesapartes.pdfcodigobarras', compact('barcode'))->render(); // Vista Blade

        $mpdf = new Mpdf([
            'mode' => 'c',
            'format' => 'A4-P',
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
        ->header('Content-Disposition', 'inline; filename="pdfcodbar'. (Auth::user()->id_personal) .'.pdf"');

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
            ->where('libroescritos.id_dependencia', Auth::user()->personal->id_dependencia)       
            ->where('libroescritos.despacho', Auth::user()->personal->despacho);        
        if (Auth::user()->personal->fiscal_asistente==="F") {
            $query->where('libroescritos.id_fiscal', Auth::user()->personal->id_personal);
        }
        $segdetalle = $query
            ->orderBy('numero', 'desc')
            ->get();

        if ($segdetalle->isNotEmpty()) {
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






    public function create()
    {
        $delitos = DB::table('delito')
            ->orderBy('desc_delito', 'asc') 
            ->get();

        return view('expediente.create', compact('delitos'));
    }

    public function store(Request $request)
    {
        /*
        $request->validate([
            'codbarras' => 'required|max:25',
            'imputado' => 'required|max:100',
            'agraviado' => 'required|max:100',
            'delito' => 'required|numeric',
            'nro_folios' => 'required|max:5',
        ], [
            'imputado.required' => 'El imputado es obligatorio.',
            'agraviado.required' => 'El agraviado es obligatorio.',
            'delito.required' => 'El delito es obligatorio.',
            'nro_folios.required' => 'El nro de folios es obligatorio.',
        ]);*/
            
        $year = now()->year;
        $nuevoNumero = null;

        DB::transaction(function () use ($year, &$nuevoNumero, $request) {
            // Buscar y bloquear la fila del año actual
            $consecutivo = DB::table('libroconsecutivos')
                ->where('anolibro', $year)
                ->lockForUpdate()
                ->first();

            if (!$consecutivo) {
                // Si no existe, insertar nueva fila para el año actual
                DB::table('libroconsecutivos')->insert([
                    'anolibro' => $year,
                    'ultimo_numero' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $nuevoNumero = 1;
            } else {
                // Si existe, incrementar el último número
                $nuevoNumero = $consecutivo->ultimo_numero + 1;

                DB::table('libroconsecutivos')
                    ->where('anolibro', $year)
                    ->update([
                        'ultimo_numero' => $nuevoNumero,
                        'updated_at' => now(),
                    ]);
            }

            // Insertar el nuevo documento
            DB::table('libroescritos')->insert([
                'anolibro' => $year,
                'numero' => $nuevoNumero,
                'id_dependencia' => $request->input('id_dependencia'),
                'despacho' => $request->input('despacho'),
                'id_fiscal' => $request->input('fiscal'),
                'tipo' => $request->input('tipo'),
                'descripcion' => $request->input('descripcion'),
                'dependenciapolicial' => $request->input('deppolicial'),
                'remitente' => $request->input('remitente'),
                'carpetafiscal' => $request->input('carpetafiscal'),
                'folios' => $request->input('folios'),
                'fecharegistro' => now(),
            ]);
        });
/*
        return response()->json([
            'message' => 'Documento creado con éxito',
            'anodocumento' => $year,
            'numero' => $nuevoNumero,
        ]);
*/
        //return redirect()->route('expediente.index')->with('success', 'EXPEDIENTE REGISTRADO DE FORMA SATISFACTORIA.');
        return redirect()->route('mesapartes.index')->with('success', 'INFORMACION REGISTRADA DE FORMA SATISFACTORIA.');
        
    }

    public function show(Expedientes $expediente)
    {
        return view('expediente.show', compact('expediente'));
    }

    public function edit(Expedientes $expediente)
    {
        $delitos = DB::table('delito')
            ->orderBy('desc_delito', 'asc') 
            ->get();
        return view('expediente.edit', compact('expediente','delitos'));
    }

    public function update(Request $request, Expedientes $expediente)
    {
        $request->validate([
            'imputado' => 'required|max:100',
            'agraviado' => 'required|max:100',
            'delito' => 'required|numeric',
            'nro_oficio' => 'nullable|max:100',
            'nro_folios' => 'required|max:5',
        ], [
            'imputado.required' => 'El imputado es obligatorio.',
            'agraviado.required' => 'El agraviado es obligatorio.',
            'delito.required' => 'El delito es obligatorio.',
            'nro_folios.required' => 'El nro de folios es obligatorio.',
        ]);

        $expediente->update($request->all());

        return redirect()->route('expediente.index')->with('success', 'EXPEDIENTE ACTUALIZADO.');
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

    public function buscaExpediente(Request $request)
    {
        $request->validate([
            'codbarras' => 'required|string',
        ]);
        $codbar = $request->input('codbarras');
        $dep_exp=substr($codbar,0,11);
        $ano_exp=substr($codbar,11,4);
        $nro_exp=substr($codbar,15,6);
        $tip_exp=substr($codbar,21,4);
        $dep_exp = (int) $dep_exp;
        $nro_exp = (int) $nro_exp; 
        $existe = Expedientes::where('id_dependencia', $dep_exp)
            ->where('ano_expediente', $ano_exp)
            ->where('nro_expediente', $nro_exp)
            ->where('id_tipo', $tip_exp)
            ->first();
        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' YA SE ENCUENTRA REGISTRADO.')
            ]);        
        } else {
            return response()->json([
                'success' => true,
                'message' => utf8_encode('OK'),
            ]);        
	    }
    }
    public function seguimiento()
    {
        return view('expediente.seguimiento');
    }
    public function buscaseguimiento(Request $request)
    {
        $ano_expediente = $request->input('ano_expediente');    
        $nro_expediente = $request->input('nro_expediente');    

        $query = DB::table('expediente')
            ->select('expediente.*');
        if (!empty($ano_expediente)) {
            $query->where('expediente.ano_expediente', $ano_expediente);
        }
        if (!empty($nro_expediente)) {
            $query->where('expediente.nro_expediente', 'like', "%{$nro_expediente}%");
        }
        $segdetalle = $query
            ->orderBy('codbarras', 'asc')
            ->get();

        if ($segdetalle->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'registros' => $segdetalle,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'NO SE ENCONTRARON CARPETAS FISCALES CON LOS DATOS PROPORCIONADOS.',
            ]);
        }

    }
    public function detalleseguimiento(Request $request)
    {
        $id_expediente = $request->input('id_expediente');    

        $segdetalle = DB::table('ubicacion_exp')
            ->where('ubicacion_exp.id_expediente', $id_expediente)
            ->leftJoin('dependencia', 'ubicacion_exp.paq_dependencia', '=', 'dependencia.id_dependencia')
            ->select('ubicacion_exp.*','dependencia.abreviado')
            ->orderBy('fecha_movimiento', 'desc') 
            ->orderBy('hora_movimiento', 'desc') 
            ->get();

        if ($segdetalle->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'registros' => $segdetalle,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'NO SE ENCONTRARON MOVIMIENTOS DEL NRO EXPEDIENTE PROPORCIONADO.',
            ]);
        }

    }






    public function indexinternamiento()
    {
        //$guiacab = MovimientoExp_Cab::all();
        $guiacab = DB::table('movimiento_exp_cab')
        ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
        ->where('tipo_mov','GI')
        ->where(function($query) {
            $query->where('id_usuario', Auth::user()->id_usuario)
                ->orWhere('fiscal', Auth::user()->id_personal); 
        })
        ->select('movimiento_exp_cab.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres','dependencia.abreviado') 
        ->orderByRaw("FIELD(movimiento_exp_cab.estado_mov, 'G', 'Z', 'E', 'R')")
        ->orderBy('fechahora_movimiento', 'desc') 
        ->orderBy('ano_mov', 'desc') 
        ->orderBy('nro_mov', 'desc') 
        ->get();
        return view('expediente_movs.index',compact('guiacab'));
    }
    public function internamiento()
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
        return view('expediente_movs.internamiento', compact('personal','dependencia'));
    }
//    public function editInternamiento(Expedientes $expediente)
    public function editInternamiento($tipo_mov, $ano_mov, $nro_mov)
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
        return view('expediente_movs.internamiento', compact('regcab','regdet','obsmovimiento','personal','dependencia'));
    }
    public function buscaExpedienteMov(Request $request)
    {
        $request->validate([
            'codbarras' => 'required|string',
        ]);
        $codbar = $request->input('codbarras');
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

        if ($existe) {
            $estado = $existe->estado; 
            if ($estado=="I") {
                return response()->json([
                    'success' => false,
                    'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' SE ENCUENTRA INTERNADO EN ARCHIVO.')
                ]);        
            } else {
                $existe2 = MovimientoExp_Det::where('id_expediente', $existe->id_expediente)
                    ->first();
                if ($existe2) {
                    return response()->json([
                        'success' => false,
                        'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' SE ENCUENTRA EN OTRA GUIA DE INTERNAMIENTO.'),
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
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' NO HA SIDO REGISTRADO.'),
            ]);        
	    }
    }
    public function guardaInternamiento(Request $request)
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

        $ultimoRegistro = MovimientoExp_Cab::where('ano_mov', $anoActual)
        ->where('tipo_mov', 'GI')
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
            'tipo_mov'               => 'GI',
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
                'tipo_mov'        => 'GI',
                'id_expediente'   => $id_exp,
                'nro_expediente'  => $nro_exp,
                'ano_expediente'  => $ano_exp,
                'id_dependencia'  => $dep_exp,
                'id_tipo'         => $tip_exp,
                'observacion'     => '',
                'estado_mov'      => 'G',
            ]);            
        }
//        return redirect()->back()->with('messageOK', 'GUIA DE INTERNAMIENTO GENERADA DE FORMA SATISFACTORIA.');
        return redirect()->route('internamiento.index')->with('success', 'GUIA DE INTERNAMIENTO GENERADA DE FORMA SATISFACTORIA.');
    }
    public function updateInternamiento(Request $request, $tipo_mov, $ano_mov, $nro_mov)
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
        return redirect()->route('internamiento.index')->with('success', 'GUIA DE INTERNAMIENTO ACTUALIZADA DE FORMA SATISFACTORIA.');
    }


    public function envioInternamiento(Request $request)
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
            'fechahora_envio' => $fechaHoraActualFormateada
        ]);
        DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'E'
        ]);

        $ultimoRegistro = UbicacionExp::where('ano_movimiento', $anoActual)
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
        ->get();
        foreach ($registrosOrigen as $registro) {
            $nromov++;
            DB::table('expediente')
            ->where('id_expediente', $registro->id_expediente)
            ->update([
                'estado' => 'T'
            ]);

            DB::table('ubicacion_exp')->insert([
                'nro_movimiento'     => $nromov,
                'ano_movimiento'     => $anoActual,

                'id_personal'        => Auth::user()->id_personal,
                'id_usuario'         => Auth::user()->id_usuario,
                'id_expediente'      => $registro->id_expediente,

                'nro_expediente'     => $registro->nro_expediente,
                'ano_expediente'     => $registro->ano_expediente,
                'id_dependencia'     => $registro->id_dependencia,
                'id_tipo'            => $registro->id_tipo,

                'ubicacion'          => 'D', // A=Archivo, D=Despacho
                'tipo_ubicacion'     => 'T', // I=Inventario, T=Transito
                'fecha_movimiento'   => $fechaActual,
                'hora_movimiento'    => $horaActual,
                'motivo_movimiento'  => 'Internamiento',

                'paq_dependencia'    => Auth::user()->personal->id_dependencia,
                'despacho'           => Auth::user()->personal->despacho,

                'activo'             => 'S',
                'estado'             => 'E', // E = Enviado
            ]);            
        }
        //return response()->json(['success' => true]);
        return response()->json([
            'success' => true,
            'redirect_url' => route('internamiento.index'),
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
        $barcodePng = $barcodeService->generate('',"*".$barcodeData."*", 20, 'vertical', 'code128', true,1);

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





    public function indexrecepinternamiento()
    {
        $guiacab = DB::table('movimiento_exp_cab')
        ->leftJoin('personal', 'movimiento_exp_cab.fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'movimiento_exp_cab.id_dependencia', '=', 'dependencia.id_dependencia')
        ->where('tipo_mov','GI')
        ->where('estado_mov','<>','G')
        ->where('estado_mov', '<>', 'Z')
        ->select('movimiento_exp_cab.*', 'personal.apellido_paterno','personal.apellido_materno','personal.nombres','dependencia.abreviado') 
        ->orderByRaw("FIELD(movimiento_exp_cab.estado_mov, 'E', 'G', 'R')")
        ->orderBy('ano_mov', 'desc') 
        ->orderBy('nro_mov', 'desc') 
        ->get();
        return view('expediente_movs.recepcion',compact('guiacab'));
    }
    public function verifrecepinternamiento($tipo_mov, $ano_mov, $nro_mov)
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
            ->leftJoin('expediente', 'movimiento_exp_det.id_expediente', '=', 'expediente.id_expediente')
            ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
            ->select(
                'movimiento_exp_det.*',
                'expediente.codbarras',
                'expediente.imputado',
                'expediente.agraviado',
                'expediente.delito',
                'expediente.nro_oficio',
                'expediente.nro_folios',
                'movimiento_exp_det.id_expediente',
                'delito.desc_delito'
            )
            ->get();
        return view('expediente_movs.recepcionguiainternamiento', compact('segdetalle','guiacab'));
    }
    public function detallerecepinternamiento($tipo_mov, $ano_mov, $nro_mov)
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
            ->where('tipo_mov', $tipo_mov)
            ->where('ano_mov', $ano_mov)
            ->where('nro_mov', $nro_mov)
            ->leftJoin('expediente', 'movimiento_exp_det.id_expediente', '=', 'expediente.id_expediente')
            ->leftJoin('delito', 'expediente.delito', '=', 'delito.id_delito')
            ->select(
                'movimiento_exp_det.*',
                'expediente.codbarras',
                'expediente.imputado',
                'expediente.agraviado',
                'expediente.delito',
                'expediente.nro_oficio',
                'expediente.nro_folios',
                'movimiento_exp_det.id_expediente',
                'delito.desc_delito'
            )
            ->get();

        return view('expediente_movs.recepciondetalle', compact('segdetalle','guiacab'));
    }
    public function grabarecepcionInternamiento(Request $request)
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
            'activo' => 'N',
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
                'estado' => 'A'
            ]);

            DB::table('ubicacion_exp')
            ->where('id_expediente', $registro->id_expediente)
            ->update([
                'activo' => 'N',
            ]);
            $nromov++;
            DB::table('ubicacion_exp')->insert([
                'nro_movimiento' => $nromov,
                'ano_movimiento' => $anoActual,
                'id_personal' => Auth::user()->id_personal,
                'id_usuario' => Auth::user()->id_usuario,
                // 'archivo' => $request->archivo,
                // 'anaquel' => $request->anaquel,
                // 'nro_paquete' => $request->nropaquete,
                // 'nro_inventario' => $request->nroinventario,
                'id_expediente' => $registro->id_expediente,
                'nro_expediente' => $registro->nro_expediente,
                'ano_expediente' => $registro->ano_expediente,
                'id_dependencia' => $registro->id_dependencia,
                'id_tipo' => $registro->id_tipo,
                'ubicacion' => 'A',             // A=Archivo D=Despacho
                'tipo_ubicacion' => 'I',        // I=Inventario T=Transito
                'fecha_movimiento' => $fechaActual,
                'hora_movimiento' => $horaActual,
                'motivo_movimiento' => 'Internamiento',
                'paq_dependencia' => Auth::user()->personal->id_dependencia,
                'despacho' => Auth::user()->personal->despacho,
                'activo' => 'S',
                'estado' => 'A',
            ]);

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
            'redirect_url' => route('internamiento.recepcion'),
            'message' => 'RECEPCION REALIZADA CORRECTAMENTE.',
        ]);
    }


    public function grabarecepcioncodigoInternamiento(Request $request)
    {
        $request->validate([
            'tipo_mov' => 'required|string',
            'ano_mov' => 'required|string',
            'nro_mov' => 'required|string',
            'codbarras' => 'required|string',
        ]);

        $tipo_mov = $request->input('tipo_mov');
        $ano_mov = $request->input('ano_mov');
        $nro_mov = $request->input('nro_mov');
        $codbar = $request->input('codbarras');
        $dep_exp=substr($codbar,0,11);
        $ano_exp=substr($codbar,11,4);
        $nro_exp=substr($codbar,15,6);
        $tip_exp=substr($codbar,21,4);
        $dep_exp = (int) $dep_exp; 

        $movexpediente = DB::table('movimiento_exp_det')
        ->where('nro_expediente', $nro_exp)
        ->where('ano_expediente', $ano_exp)
        ->where('id_dependencia', $dep_exp)
        ->where('id_tipo', $tip_exp)
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->first();
            
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);

        if ($movexpediente) {
            $estado_mov = $movexpediente->estado_mov;
            if ($estado_mov=="R") {
                return response()->json([
                    'success' => false,
                    'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' YA SE ENCUENTRA RECEPCIONADO.')
                ]);        
            } else {
                DB::table('expediente')
                ->where('id_expediente', $movexpediente->id_expediente)
                ->update([
                    'estado' => 'A'
                ]);

                DB::table('ubicacion_exp')
                ->where('id_expediente', $movexpediente->id_expediente)
                ->update([
                    'activo' => 'N',
                ]);

                $ultimoRegistro = DB::table('ubicacion_exp')
                    ->where('ano_movimiento', $anoActual)
                    ->orderBy('nro_movimiento', 'desc')
                    ->first();
                $nromov=0;
                if ($ultimoRegistro) {
                    $nromov = $ultimoRegistro->nro_movimiento;
                }
                $nromov++;

                DB::table('ubicacion_exp')->insert([
                    'nro_movimiento' => $nromov,
                    'ano_movimiento' => $anoActual,
                    'id_personal' => Auth::user()->id_personal,
                    'id_usuario' => Auth::user()->id_usuario,
                    //'archivo' => $request->archivo,
                    //'anaquel' => $request->anaquel,
                    //'nro_paquete' => $request->nropaquete,
                    //'nro_inventario' => $request->nroinventario,
                    'id_expediente' => $movexpediente->id_expediente,
        
                    'nro_expediente' => $nro_exp,
                    'ano_expediente' => $ano_exp,
                    'id_dependencia' => $dep_exp,
                    'id_tipo' => $tip_exp,
                    'ubicacion' => 'A',             //A=Archivo D=Despacho
                    'tipo_ubicacion' => 'I',        //I=Inventario T=Transito
                    'fecha_movimiento' => $fechaActual,
                    'hora_movimiento' => $horaActual,
                    'motivo_movimiento' => 'Internamiento',                
                    'paq_dependencia' => Auth::user()->personal->id_dependencia,
                    'despacho' => Auth::user()->personal->despacho,
                    'activo' => 'S',
                    'estado' => 'A',
                ]);
                DB::table('movimiento_exp_det')
                ->where('nro_expediente', $nro_exp)
                ->where('ano_expediente', $ano_exp)
                ->where('id_dependencia', $dep_exp)
                ->where('id_tipo', $tip_exp)
                ->where('tipo_mov', $tipo_mov)
                ->where('ano_mov', $ano_mov)
                ->where('nro_mov', $nro_mov)        
                ->update([
                    'estado_mov' => 'R',
                ]);
                $existe = DB::table('movimiento_exp_det')
                ->where('tipo_mov', $tipo_mov)
                ->where('ano_mov', $ano_mov)
                ->where('nro_mov', $nro_mov)
                ->where('estado_mov', '<>', 'R')
                ->first();
                if (!$existe) {
                    DB::table('movimiento_exp_cab')
                    ->where('tipo_mov', $tipo_mov)
                    ->where('ano_mov', $ano_mov)
                    ->where('nro_mov', $nro_mov)        
                    ->update([
                        'estado_mov' => 'R',
                        'fechahora_recepcion' => $fechaHoraActualFormateada,
                        'activo' => 'N',
                        'cantidad_exp_recep' => DB::raw('COALESCE(cantidad_exp_recep, 0) + 1')
                    ]);
                } else {
                    DB::table('movimiento_exp_cab')
                        ->where('tipo_mov', $tipo_mov)
                        ->where('ano_mov', $ano_mov)
                        ->where('nro_mov', $nro_mov)
                        ->update([
                            'cantidad_exp_recep' => DB::raw('COALESCE(cantidad_exp_recep, 0) + 1')
                        ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'EXPEDIENTE ' . $codbar . ' RECEPCIONADO.'
                ]);
            }

        } else {
            $existe = DB::table('expediente')
                ->where('id_dependencia', $dep_exp)
                ->where('ano_expediente', $ano_exp)
                ->where('nro_expediente', $nro_exp)
                ->where('id_tipo', $tip_exp)
                ->first();
            if ($existe) {
                return response()->json([
                    'success' => false,
                    'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' NO ESTA ASIGNADA EN LA GUIA DE INTERNAMIENTO.')
                ]);        
            } else {
                return response()->json([
                    'success' => false,
                    'message' => utf8_encode('EL EXPEDIENTE ' . $codbar . ' NO ESTA REGISTRADO EN LA BASE DE DATOS.')
                ]);        
            }
	    }
        //return redirect()->back()->with('messageOK', 'Registros guardados exitosamente');
    }


    public function rechazarecepcionInternamiento(Request $request)
    {    
        $fechaHoraActualFormateada = now()->format('Y-m-d H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $fechaActual = now()->format('Y-m-d');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $horaActual = now()->format('H:i:s');  // Formato 'YYYY-MM-DD HH:mm:ss'
        $anoActual = substr($fechaActual,0,4);
        $tipo_mov = $request->input('tipo_mov');
        $ano_mov = $request->input('ano_mov');
        $nro_mov = $request->input('nro_mov');
    	if (trim($request->observacion) !="" ) {
            DB::table('observacion_movimiento')->insert([
                'nro_mov'         => $nro_mov,
                'ano_mov'         => $ano_mov,
                'tipo_mov'        => $tipo_mov,
                'observacion'     => $request->observacion,
            ]);            
        }
        DB::table('movimiento_exp_cab')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'Z',
            'fechahora_recepcion' => null,
            'activo' => 'S',
            'cantidad_exp_recep' => 0
        ]);

        $registrosOrigen = DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        //->where('estado_mov', 'E')        
        ->get();
        foreach ($registrosOrigen as $registro) {
            DB::table('expediente')
            ->where('id_expediente', $registro->id_expediente)
            ->update([
                'estado' => ''
            ]);
            DB::table('ubicacion_exp')
                ->where('id_expediente', $registro->id_expediente)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('ubicacion', 'A')->where('tipo_ubicacion', 'I')->where('activo', 'S')->where('estado', 'A');
                    })->orWhere(function ($q) {
                        $q->where('ubicacion', 'D')->where('tipo_ubicacion', 'T')->where('estado', 'E');
                    });
                })
                ->delete();
        }
        DB::table('movimiento_exp_det')
        ->where('tipo_mov', $tipo_mov)
        ->where('ano_mov', $ano_mov)
        ->where('nro_mov', $nro_mov)
        ->update([
            'estado_mov' => 'G'
        ]);

        //return response()->json(['success' => true]);
        return response()->json([
            'success' => true,
            'redirect_url' => route('internamiento.recepcion'),
            'message' => 'LA GUIA DE INTERNAMIENTO FUE RECHAZADA A DESPACHO.',
        ]);
    }



}
