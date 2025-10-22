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

class MesaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $fecha = $request->input('fecharegistro', date('Y-m-d'));

        $libroescritos = DB::table('libroescritos')
        ->leftJoin('personal', 'libroescritos.id_fiscal', '=', 'personal.id_personal')
        ->leftJoin('dependencia', 'libroescritos.id_dependencia', '=', 'dependencia.id_dependencia')
        ->leftJoin('usuarios', 'libroescritos.id_usuario', '=', 'usuarios.id_usuario')
        ->select(
            //'libroescritos.anolibro',
            //'libroescritos.numero',
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
        ->wheredate('fecharegistro', $fecha)
//        ->wheredate('fecharegistro', date('Y-m-d', strtotime('-2 day')))
        ->orderBy('fecharegistro', 'desc') 
        ->get();

        return view('mesapartes.index', compact('libroescritos', 'fecha'));
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
    public function nuevoEscritoV() //nuevo escrito recepcionado virtualmente (por email)
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

        return view('mesapartes.registroescritosv', compact('fiscales','deppoli'));
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
    public function generarConsFiscalPDF($id_fiscal, $fechareg)
    {  
        $datosfiscal = DB::table('personal')
        ->select(
            'personal.id_personal',
            'personal.apellido_paterno',
            'personal.apellido_materno',
            'personal.nombres',
            'personal.id_dependencia',
            'personal.despacho',
        )
        ->where('id_personal', $id_fiscal)
        ->first();

        $segdetalle = DB::table('libroescritos')
            ->select('*')
            ->where('libroescritos.id_fiscal', $id_fiscal)
            ->whereDate('fecharegistro', '=', $fechareg)
            ->orderBy('fecharegistro', 'desc')
            ->get();


        if ($segdetalle->isEmpty()) {
            $html = '<h1 style="text-align:center; color:red;">NO HAY DATOS REGISTRADOS PARA EL FISCAL Y FECHA SELECCIONADA</h1>';
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
            $mpdf->WriteHTML($html);

            $pdfContent = $mpdf->Output('', 'S'); // 'S' = devuelve el contenido como string
            return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="escritos_'.$id_fiscal.'.pdf"');
        }

        $primerRegistro = $segdetalle->first(); // obtiene el primer registro de la colección
        $id_dependencia = $primerRegistro ? $primerRegistro->id_dependencia : null;
        $despacho = $primerRegistro ? $primerRegistro->despacho : null;
        $dependencias = DB::table('dependencia')
        ->select(
            'dependencia.descripcion',
            'dependencia.abreviado',
            'dependencia.datodistrito'
        )
        ->where('id_dependencia', $id_dependencia)
        ->first();
        $abreviado = $dependencias ? $dependencias->abreviado : null;
        $datodistrito = $dependencias ? $dependencias->datodistrito : null;


        $year = substr($fechareg,0,4);// now()->year;
        $nuevoNumero = null;

        $existe = DB::table('librocargos')
        ->where('fechacargo', $fechareg)
        ->where('id_fiscal', $id_fiscal)
        ->first();

        if ($existe) {
            $nuevoNumero = $existe->numero;
        } else {

            DB::transaction(function () use ($fechareg, $id_fiscal, $id_dependencia, $despacho, $datodistrito, $year, &$nuevoNumero) {
                // Buscar y bloquear la fila del año actual
                $consecutivo = DB::table('libroconsecutivos')
                    ->where('tipo', 'LC')
                    ->where('anolibro', $year)
                    ->lockForUpdate()
                    ->first();

                if (!$consecutivo) {
                    // Si no existe, insertar nueva fila para el año actual
                    DB::table('libroconsecutivos')->insert([
                        'tipo' => 'LC',
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
                        ->where('tipo', 'LC')
                        ->where('anolibro', $year)
                        ->update([
                            'ultimo_numero' => $nuevoNumero,
                            'updated_at' => now(),
                        ]);
                }

        $despachoFinal = ($despacho == 0) ? 'UU' : str_pad($despacho, 2, '0', STR_PAD_LEFT);
        $codcargo = 'MP' .  substr($year, -2) . $datodistrito . $despachoFinal . str_pad($nuevoNumero, 5, '0', STR_PAD_LEFT);

                // Insertar el nuevo documento
                DB::table('librocargos')->insert([
                    'fechacargo' => $fechareg,
                    'id_dependencia' => $id_dependencia,
                    'despacho' => $despacho,
                    'id_fiscal' => $id_fiscal,
                    'datodistrito' => $datodistrito,
                    'numero' => $nuevoNumero,
                    'codcargo' => $codcargo,
                ]);
            });

        }

        $despachoFinal = ($despacho == 0) ? 'UU' : str_pad($despacho, 2, '0', STR_PAD_LEFT);
        $barcodeData = 'MP' .  substr($year, -2) . $datodistrito . $despachoFinal . str_pad($nuevoNumero, 5, '0', STR_PAD_LEFT);
        // Usa el servicio BarcodeGenerator
        $barcodeService = new BarcodeGenerator();
        //$barcodePng = $barcodeService->generate('',"*".$barcodeData."*", 20, 'horizontal', 'code128', true,1);
        $barcodePng = $barcodeService->generate('',$barcodeData, 20, 'horizontal', 'code128', true,1);
        // Codifica en base64
        $barcode = base64_encode($barcodePng);




        $html = view('mesapartes.pdfconsultaescritosfiscal', compact('segdetalle','datosfiscal', 'fechareg', 'abreviado','despacho', 'barcode'))->render(); // Vista Blade

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

    public function consultarFiltros()
    {
        return view('mesapartes.consultafiltros');
    }
    public function consultarFiltrosdetalle(Request $request)
    {
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
        );
        if ($request->filled('codigo')) {
            $segdetalle->where('codescrito', 'like', '%' . $request->codigo . '%');
        }
        if ($request->filled('descripcion')) {
            $segdetalle->where('libroescritos.descripcion', 'like', '%' . $request->descripcion . '%');
        }
        if ($request->filled('remitente')) {
            $segdetalle->where('remitente', 'like', '%' . $request->remitente . '%');
        }
        if ($request->filled('dependenciapolicial')) {
            $segdetalle->where('dependenciapolicial', 'like', '%' . $request->dependenciapolicial . '%');
        }
        $segdetalle = $segdetalle
        ->orderBy('codescrito', 'asc') 
        ->get();
        $codigo = $request->codigo;
        $descripcion = $request->descripcion;
        $remitente = $request->remitente;
        $dependenciapolicial = $request->dependenciapolicial;
            
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

        return view('mesapartes.consultafiltros',compact('segdetalle', 'codigo', 'descripcion', 'remitente', 'dependenciapolicial'));

    }

    public function estadisticas()
    {
        $dependencias = DB::table('libroescritos')
            ->join('dependencia', 'libroescritos.id_dependencia', '=', 'dependencia.id_dependencia')
            ->select('dependencia.id_dependencia', 'dependencia.descripcion', 'dependencia.abreviado')
            ->distinct()
            ->orderBy('dependencia.descripcion')
            ->get();
        $fiscales = DB::table('libroescritos')
            ->join('personal', 'libroescritos.id_fiscal', '=', 'personal.id_personal')
            ->select('libroescritos.id_fiscal', 'personal.apellido_paterno', 'personal.apellido_materno', 'personal.nombres')
            ->distinct()
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->get();
        $operadores = DB::table('libroescritos')
            ->join('personal', 'libroescritos.id_personal', '=', 'personal.id_personal')
            ->select('libroescritos.id_personal', 'personal.apellido_paterno', 'personal.apellido_materno', 'personal.nombres')
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->distinct()
            ->get();            
        return view('mesapartes.estadisticas',compact('dependencias','fiscales','operadores'));
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
        $tipo = $request->tipo;
        $datoadd = $request->datoadd;
        
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
            $datos = DB::table('libroescritos')
                ->join('personal', 'libroescritos.id_fiscal', '=', 'personal.id_personal')
                ->selectRaw('DATE(fecharegistro) as fecha, libroescritos.id_fiscal, apellido_paterno, apellido_materno, nombres, COUNT(*) as total')
                ->whereBetween('fecharegistro', [$fechainicio, $fechafin])
                ->where('id_fiscal',$datoadd)
                ->groupBy('fecha', 'libroescritos.id_fiscal', 'apellido_paterno','apellido_materno','nombres')
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

/*
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
*/        
        


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







    public function showupload()
    {
        return view('mesapartes.upload');
    }
    public function checkExistingFiles(Request $request)
    {
        $fileNames = $request->input('files', []);
        if (empty($fileNames)) {
            return response()->json([]);
        }
        $existingFiles = [];
        foreach ($fileNames as $fileName) {
            $codigo = pathinfo($fileName, PATHINFO_FILENAME);
            $libroescritos = DB::table('libroescritos')->where('codescrito', $codigo)->first();
            if ($libroescritos) {
                $fecha = new \DateTime($libroescritos->fecharegistro);
                $anio = $fecha->format('Y');
                $mes  = $fecha->format('m');
                $ruta = storage_path("app/mesapartes/{$anio}/{$mes}/{$fileName}");
                if (file_exists($ruta)) {
                    $existingFiles[] = $fileName;
                }
            }
        }
        return response()->json($existingFiles);
    }    
    public function uploadChunk(Request $request)
    {
        if (!$request->hasFile('files')) {
            return response()->json(['message' => 'No se enviaron archivos'], 400);
        }

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();

            $codigo = pathinfo($originalName, PATHINFO_FILENAME);
//            if (!str_contains($codigo, '-')) {
//                continue; // O registra el error
//            }

//            [$ano, $numero] = explode('-', $codigo);
//            $numero = (int) ltrim($numero, '0');
//            $libroescritos = DB::table('libroescritos')
//            ->where('anolibro', $ano) 
//            ->where('numero', $numero) 
//            ->first();

            $libroescritos = DB::table('libroescritos')
            ->where('codescrito', $codigo) 
            ->first();


            if ($libroescritos) {
                $fecha = new \DateTime($libroescritos->fecharegistro);
                $anio = $fecha->format('Y');
                $mes  = $fecha->format('m');
                $directory = storage_path("app/mesapartes/$anio/$mes");
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }                
                //$path = $file->storeAs('uploads/pdfs', $originalName);  

                $ruta = storage_path("app/mesapartes/{$anio}/{$mes}/{$originalName}");
                if (!file_exists($ruta)) {
                    $path = $file->storeAs("mesapartes/$anio/$mes", $originalName);
                    chmod(storage_path("app/" . $path), 0777);
                }


            }     
            // Puedes guardar directamente o enviar a una job
            //$path = $file->store('uploads/pdfs'); // en storage/app/uploads/pdfs
            //ProcessPdfUpload::dispatch($path); // asíncrono, no bloquea
        }

        return response()->json(['message' => 'Chunk recibido correctamente']);
    }    

    public function compresionindex()
    {
        $fechasunicas = DB::table('libroescritos')
        ->selectRaw('DISTINCT YEAR(fecharegistro) AS anio, MONTH(fecharegistro) AS mes')
        ->where('pdf150dpi', 'S')
        ->orderBy('anio', 'desc')
        ->orderBy('mes', 'desc')
        ->get();
        return view('mesapartes.compresion',compact('fechasunicas'));
    }    
    public function verificarArchivos($anio, $mes)
    {
        $path = storage_path("app/mesapartes/{$anio}/{$mes}");
        if (!File::exists($path)) {
            return response()->json(['status' => 'error', 'message' => 'Directorio no encontrado'], 404);
        }
        $allPdfFiles = File::files($path);
        $codigosProcesados = DB::table('libroescritos')
            ->where('pdf150dpi', 'S')
            ->pluck('codescrito')
            ->toArray();
        $codigosProcesadosMap = array_flip($codigosProcesados);
        $pdfFiles = array_filter($allPdfFiles, function ($file) use ($codigosProcesadosMap) {
            $codescrito = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            return !isset($codigosProcesadosMap[$codescrito]);
        });
        usort($pdfFiles, fn($a, $b) => $a->getFilename() <=> $b->getFilename());
        $archivos = array_map(function ($file) {
            return pathinfo($file->getFilename(), PATHINFO_FILENAME);
        }, $pdfFiles);
        return response()->json(['status' => 'ok', 'archivos' => $archivos]);
    }
    public function comprimirArchivo(Request $request)
    {
        $anio = $request->input('anio');
        $mes = $request->input('mes');
        $filename = $request->input('archivo');
        if (!$anio || !$mes || !$filename) {
            return response()->json(['status' => 'error', 'message' => 'Datos incompletos'], 400);
        }
        $path = storage_path("app/mesapartes/{$anio}/{$mes}");
        $tmpDir = storage_path("app/mesapartes/{$anio}/tmp");
        if (!File::exists("{$path}/{$filename}.pdf")) {
            return response()->json(['status' => 'error', 'message' => 'Archivo no encontrado'], 404);
        }
        if (!File::exists($tmpDir)) {
            mkdir($tmpDir, 0777, true);
        }
        $inputPath = "{$path}/{$filename}.pdf";
        $outputPath = "{$tmpDir}/{$filename}.pdf";
        // Comando Ghostscript
/*
        $gsPath = 'C:\\Program Files\\gs\\gs10.06.0\\bin\\gswin64c.exe';
        $gsCommand = "\"$gsPath\" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen " .
            "-dNOPAUSE -dQUIET -dBATCH -dDownsampleColorImages=true " .
            "-dColorImageResolution=150 -dGrayImageResolution=150 -dMonoImageResolution=150 " .
            "-sOutputFile=" . escapeshellarg($outputPath) . " " . escapeshellarg($inputPath);
*/
        // Comando Ghostscript para comprimir a 150 DPI
        $gsCommand = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen " .
            "-dNOPAUSE -dQUIET -dBATCH -dDownsampleColorImages=true " .
            "-dColorImageResolution=150 -dGrayImageResolution=150 -dMonoImageResolution=150 " .
            "-sOutputFile=" . escapeshellarg($outputPath) . " " . escapeshellarg($inputPath);// . " " . escapeshellarg($pdfmarkPath);
            
        exec($gsCommand, $output, $result);
        chmod($outputPath, 0777);
        if ($result === 0 && File::exists($outputPath) && filesize($outputPath) > 0) {
            if ($this->isValidPdf($outputPath)) {
                DB::table('libroescritos')
                    ->where('codescrito', $filename)
                    ->update(['pdf150dpi' => 'S']);
                unlink($inputPath);
                rename($outputPath, $inputPath);
                return response()->json(['status' => 'ok', 'archivo' => $filename]);
            } else {
                unlink($outputPath);
                return response()->json(['status' => 'error', 'message' => 'PDF inválido'], 422);
            }
        } else {
            if (File::exists($outputPath)) {
                unlink($outputPath);
            }
            return response()->json(['status' => 'error', 'message' => 'Error al comprimir'], 500);
        }
    }

    public function verificarArchivox($anio, $mes)
    {
        ini_set('memory_limit', '1024M');

        $path = storage_path("app/mesapartes/{$anio}/{$mes}"); // ruta donde están tus PDFs

        $directory = storage_path("app/mesapartes/$anio/tmp");
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }                


        // 1. Obtener todos los archivos PDF del directorio
        $allPdfFiles = File::files($path);

        // 2. Obtener los códigos procesados desde la base de datos
        $codigosProcesados = DB::table('libroescritos')
            ->where('pdf150dpi', 'S')
            ->pluck('codescrito')
            ->toArray();

        // 3. Crear un mapa para acceso rápido
        $codigosProcesadosMap = array_flip($codigosProcesados);

        // 4. Filtrar los archivos que *no* han sido procesados
        $pdfFiles = array_filter($allPdfFiles, function ($file) use ($codigosProcesadosMap) {
            $codescrito = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            return !isset($codigosProcesadosMap[$codescrito]);
        });

        // 5. Ahora $pdfFiles solo tiene archivos NO procesados
//        $cant = count($pdfFiles);
//        echo "Archivos pendientes por procesar: $cant\n";
        usort($pdfFiles, fn($a, $b) => $a->getFilename() <=> $b->getFilename());

        foreach ($pdfFiles as $file) {
            $inputPath = $file->getRealPath();
            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $outputPath = storage_path("app/mesapartes/{$anio}/tmp/" . $filename . ".pdf");

            // Comando Ghostscript para comprimir a 150 DPI
            $gsCommand = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen " .
                    "-dNOPAUSE -dQUIET -dBATCH -dDownsampleColorImages=true " .
                    "-dColorImageResolution=150 -dGrayImageResolution=150 -dMonoImageResolution=150 " .
                    "-sOutputFile=" . escapeshellarg($outputPath) . " " . escapeshellarg($inputPath);// . " " . escapeshellarg($pdfmarkPath);

            // Ejecutar el comando
            exec($gsCommand, $output, $result);
            chmod($outputPath, 0777);

            //$timestamp = strtotime($creadate);
            //touch($outputPath, $timestamp, $timestamp);//le asigno la misma fecha de modificacion que el original

            if ($result === 0 && File::exists($outputPath) && filesize($outputPath) > 0) {
                if ($this->isValidPdf($outputPath)) {

                    DB::table('libroescritos')
                    ->where('codescrito', $filename)
                    ->update([
                        'pdf150dpi' => 'S',
                    ]);

                    // Reemplazar original con comprimido válido
                    unlink($inputPath);
                    rename($outputPath, $inputPath);
                    //echo "✅ Comprimido y reemplazado: $filename\n";
                } else {
                    //echo "❌ PDF inválido (posiblemente corrupto): $filename\n";
                    unlink($outputPath);
                }
            } else {
                //echo "❌ Error al comprimir (exit code o archivo vacío): $filename\n";
                if (File::exists($outputPath)) {
                    unlink($outputPath);
                }
            }

        }//endfor
echo "FIN";
    }
        
function isValidPdf(string $path): bool
{
    try {
        $parser = new Parser();
        $pdf = $parser->parseFile($path);
        return count($pdf->getPages()) > 0;
    } catch (\Exception $e) {
        return false;
    }
}


    public function uploadChunkCargos(Request $request)
    {
        if (!$request->hasFile('files')) {
            return response()->json(['message' => 'No se enviaron archivos'], 400);
        }

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();

            $codigo = pathinfo($originalName, PATHINFO_FILENAME);
            $librocargos = DB::table('librocargos')
            ->where('codcargo', $codigo) 
            ->first();


            if ($librocargos) {
                $fecha = new \DateTime($librocargos->fechacargo);
                $anio = $fecha->format('Y');
                $mes  = $fecha->format('m');
                $directory = storage_path("app/mesapartescargos/$anio/$mes");
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }                
                //$path = $file->storeAs('uploads/pdfs', $originalName);  

                $ruta = storage_path("app/mesapartescargos/{$anio}/{$mes}/{$originalName}");
                if (!file_exists($ruta)) {
                    $path = $file->storeAs("mesapartescargos/$anio/$mes", $originalName);
                    chmod(storage_path("app/" . $path), 0777);
                }


            }     
            // Puedes guardar directamente o enviar a una job
            //$path = $file->store('uploads/pdfs'); // en storage/app/uploads/pdfs
            //ProcessPdfUpload::dispatch($path); // asíncrono, no bloquea
        }

        return response()->json(['message' => 'Chunk recibido correctamente']);
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
    $codigo = strtoupper($request->input('codescrito'));

    // Verificar si ya existe el código
    $exists = DB::table('libroescritos')->where('codescrito', $codigo)->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->withErrors(['codescrito' => 'CÓDIGO YA SE ENCUENTRA REGISTRADO']);
    }
    
        try {

            DB::transaction(function () use ($request) {
                // Insertar el nuevo documento
                DB::table('libroescritos')->insert([
                    'codescrito' => strtoupper( $request->input('codescrito') ),
                    'tiporecepcion' => 'F',
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
                    'id_personal' => Auth::user()->id_personal,
                    'id_usuario' => Auth::user()->id_usuario,
                ]);
            });
            return redirect()->route('mesapartes.index')->with('success', 'INFORMACION REGISTRADA DE FORMA SATISFACTORIA.');

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

    public function storetpv(Request $request)//grabar escrito por recepcion virtual
    {
        $year = now()->year;
        $nuevoNumero = null;

        DB::transaction(function () use ($year, &$nuevoNumero, $request) {
            // Buscar y bloquear la fila del año actual
            $consecutivo = DB::table('libroconsecutivos')
                ->where('tipo', 'LE')
                ->where('anolibro', $year)
                ->lockForUpdate()
                ->first();

            if (!$consecutivo) {
                // Si no existe, insertar nueva fila para el año actual
                DB::table('libroconsecutivos')->insert([
                    'tipo' => 'LE',
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
                    ->where('tipo', 'LE')
                    ->where('anolibro', $year)
                    ->update([
                        'ultimo_numero' => $nuevoNumero,
                        'updated_at' => now(),
                    ]);
            }

            $codescrito = 'MP' .  substr($year, -2) ."V". str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);

            // Insertar el nuevo documento
            DB::table('libroescritos')->insert([
                'codescrito' => $codescrito,
                'tiporecepcion' => 'V',
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
                'id_personal' => Auth::user()->id_personal,
                'id_usuario' => Auth::user()->id_usuario,
            ]);
        });

        return redirect()->route('mesapartes.index')->with('success', 'INFORMACION REGISTRADA DE FORMA SATISFACTORIA.');
        
    }

    public function edit($codescrito)
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

        $libroescritos = DB::table('libroescritos')
        ->leftJoin('dependencia', 'libroescritos.id_dependencia', '=', 'dependencia.id_dependencia')
        ->select(
            'libroescritos.*',
            'dependencia.descripcion as descridependencia'
        )
            ->where('codescrito', $codescrito) 
            ->first();

        return view('mesapartes.editescritos', compact('fiscales','deppoli','libroescritos'));
    }

    public function update(Request $request, $codescrito)
    {
            DB::table('libroescritos')
            ->where('codescrito', $codescrito)
            ->update([
                'id_dependencia' => $request->input('id_dependencia'),
                'despacho' => $request->input('despacho'),
                'id_fiscal' => $request->input('fiscal'),
                'tipo' => $request->input('tipo'),
                'descripcion' => $request->input('descripcion'),
                'dependenciapolicial' => $request->input('deppolicial'),
                'remitente' => $request->input('remitente'),
                'carpetafiscal' => $request->input('carpetafiscal'),
                'folios' => $request->input('folios'),
                //'fecharegistro' => now(),
                //'id_personal' => Auth::user()->id_personal,
                //'id_usuario' => Auth::user()->id_usuario,
            ]);

        return redirect()->route('mesapartes.index')->with('success', 'INFORMACION ACTUALIZADA DE FORMA SATISFACTORIA.');
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





    public function nuevoCarpetasf()
    {
        $dependencias = DB::table('dependencia')
        ->orderBy('descripcion', 'asc') 
        ->get();
        return view('mesapartes.registrocarpetasf', compact('dependencias'));
    }
    public function buscaCarpetasf(Request $request)
    {
        $carpetasf = DB::table('mesacarpetasf')
        ->where('fecha', $request->input('fech'))
        ->where('id_dependencia', $request->input('depe'))
        ->where('despacho', $request->input('desp'))
        ->where('motivo', $request->input('moti'))
        ->orderBy('carpetafiscal', 'asc')
        ->get();

        return response()->json([
            'success' => true,
            'registros' => $carpetasf,
        ]);
    }
    public function buscaCarpeta(Request $request)
    {
        $carpetasf = DB::table('mesacarpetasf')
        ->where('carpetafiscal', $request->input('codbarras'))
        ->first();
        if (!$carpetasf) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "LA CARPETA YA FUE REGISTRADA",
            ]);
        }
    }
    public function grabaCarpeta(Request $request)
    {
        DB::table('mesacarpetasf')->insert([
            'fecha' => $request->input('fech'),
            'id_dependencia' => $request->input('depe'),
            'despacho' => $request->input('desp'),
            'motivo' => $request->input('moti'),
            'carpetafiscal' => $request->input('codi'),
            'id_personal' => Auth::user()->id_personal,
            //'id_usuario' => Auth::user()->id_usuario,
        ]);        

        $carpetasf = DB::table('mesacarpetasf')
        ->where('fecha', $request->input('fech'))
        ->where('id_dependencia', $request->input('depe'))
        ->where('despacho', $request->input('desp'))
        ->where('motivo', $request->input('moti'))
        ->orderBy('carpetafiscal', 'asc')
        ->get();



        $fecha = $request->input('fech'); // Ejemplo: "2023-10-22"
        $anio = date('Y', strtotime($fecha)); // Extrae el año: "2023"        
        $carpetasfcod = DB::table('mesacarpetasf_codbarras')
        ->where('fecha', $fecha)
        ->where('id_dependencia', $request->input('depe'))
        ->where('despacho', $request->input('desp'))
        ->where('motivo', $request->input('moti'))
        ->orderBy('numero', 'desc')
        ->first();
        if (!$carpetasfcod) {
            $carpetasfcod = DB::table('mesacarpetasf_codbarras')
            ->whereYear('fecha', $anio)
            ->where('id_dependencia', $request->input('depe'))
            ->where('despacho', $request->input('desp'))
            ->where('motivo', $request->input('moti'))
            ->orderBy('numero', 'desc')
            ->first();
            if (!$carpetasfcod) {
                $nuevoNumero = 1;
            } else {
                $nuevoNumero = $carpetasfcod->numero + 1;
            }
            $desp = str_pad($request->input('desp'), 2, '0', STR_PAD_LEFT);
            $nume = str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);

            $codigo="MP" . substr($anio,2,2) . "1" . $desp . $nume;
            DB::table('mesacarpetasf_codbarras')->insert([
                'fecha' => $request->input('fech'),
                'id_dependencia' => $request->input('depe'),
                'despacho' => $request->input('desp'),
                'motivo' => $request->input('moti'),
                'numero' => $nuevoNumero,
                'codigo' => $codigo,
                //'id_personal' => Auth::user()->id_personal,
                //'id_usuario' => Auth::user()->id_usuario,
            ]);  
        }
        



        return response()->json([
            'success' => true,
            'registros' => $carpetasf,
            'message' => "LA CARPETA FUE REGISTRADA SATISFACTORIAMENTE",
        ]);        
    }
    
}
