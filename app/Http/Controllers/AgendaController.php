<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }    

    public function agendaVehicular()
    {
        $personal = DB::table('personal')
            ->where('fiscal_asistente','F')
            ->where('activo','S')            
            ->orderBy('apellido_paterno', 'asc') 
            ->orderBy('apellido_materno', 'asc') 
            ->orderBy('nombres', 'asc') 
            ->get();
        $conductores = DB::table('tra_conductores')
            ->where('activo','S')            
            ->orderBy('apellido_paterno', 'asc') 
            ->orderBy('apellido_materno', 'asc') 
            ->orderBy('nombres', 'asc') 
            ->get();
        $agenda = DB::table('agenda')
            ->leftJoin('personal', 'agenda.id_fiscal', '=', 'personal.id_personal')
            ->leftJoin('tra_conductores', 'agenda.id_conductor', '=', 'tra_conductores.id_conductor')
            ->select('agenda.*', 'personal.apellido_paterno', 'personal.apellido_materno', 'personal.nombres', 
            DB::raw("CONCAT(tra_conductores.apellido_paterno, ' ', tra_conductores.apellido_materno, ' ', tra_conductores.nombres) AS conductor"),
            'tra_conductores.nrocelular'
            )
            ->where('agenda.tipo','V')
            ->orderBy('fechahora_inicia', 'desc') 
            ->get();
        return view('agenda.agendavehicular', compact('personal','conductores','agenda'));
    }
    public function grabarAgendaVehicular(Request $request) {
        $fecha = $request->input('start');
        $hora  = $request->input('hstart');
        $fechaHora = $hora
            ? $fecha . ' ' . $hora
            : $fecha . ' 00:00:00';

        $fechaf = $request->input('end');
        $horaf  = $request->input('hend');
        if (empty($fechaf)) {
            $fechaHoraf = null; 
        } elseif (empty($hora)) {
            $fechaHoraf = $fechaf . ' 00:00:00';
        } else {
            $fechaHoraf = $fechaf . ' ' . $horaf;
        }

        $personal = DB::table('personal')
            ->select('id_dependencia', 'despacho')
            ->where('id_personal', $request->fiscal)
            ->first();
        $iddep = $personal->id_dependencia ?? 0;
        $despa = $personal->despacho ?? 0;

        $event = DB::table('agenda')->insert([
            'tipo' => $request->input('tipo') ,
            'id_fiscal' => $request->input('fiscal') ,
            'id_conductor' => $request->input('conductor') ,
            'id_dependencia' => $iddep,
            'despacho' => $despa,
            'asunto' => $request->input('asunto') ,
            'detalle' => $request->input('detalle') ,
            'fechahora_inicia' => $fechaHora,
            'fechahora_termina' => $fechaHoraf,
            'estado' => "A",
            'id_operador' => Auth::user()->id_personal,
        ]);
        return response()->json([
            'success' => true,
        ]);        
    }
    public function grabarAprueba(Request $request) {
        $idevento = $request->input('idevento');
        $idconductor = $request->input('idconductor');
        $event = DB::table('agenda')
        ->where('id_evento',$idevento)
        ->update([
            'estado' => "A",
            'id_conductor' => $idconductor,
        ]);
        return response()->json([
            'success' => true,
        ]);        
    }

    public function index(Request $request)
    {
        // Tomar el parámetro tipo, si no llega será null
        $tipo = $request->query('tipo'); // GET /events?tipo=V
        $query = DB::table('agenda')
        ->leftJoin('personal', 'agenda.id_fiscal', '=', 'personal.id_personal')
        ->where('estado', 'A')
        ->select(
            'agenda.*',
            'personal.apellido_paterno',
            'personal.apellido_materno',
            'personal.nombres',
        );
        if ($tipo) {
            $query->where('tipo', $tipo);
        }
        $events = $query->get();    

        return response()->json(
            $events->map(function ($e) {
                return [
                    'id'    => $e->id_evento,
                    'title' => $e->asunto,
                    'start' => $e->fechahora_inicia,
                    'end'   => $e->fechahora_termina,
                    'tipo'  => $e->tipo,
                    'detalle'  => $e->detalle,
                    'fiscal'  => $e->apellido_paterno . ' ' . $e->apellido_materno . ' ' . $e->nombres,
                ];
            })
        );
    }

    public function store(Request $request) {
        $event = DB::table('agenda')->insert([
            'tipo' => $request->input('tipo') ,
            'asunto' => $request->input('asunto') ,
            'fechahora_inicia' => $request->input('start'),
            'fechahora_termina' => $request->input('end'),
        ]);
        //$event = Event::create($request->all());
        return response()->json($event);
    }

    public function update(Request $request, Event $event) {
        $event = DB::table('agenda')
        ->where('id_evento', $request->input('id'))
        ->update([
            'asunto' => $request->input('title') ,
            'fechahora_inicia' => $request->input('start'),
            'fechahora_termina' => $request->input('end'),
        ]);
        //$event->update($request->all());
        return response()->json($event);
    }

    public function destroy(Event $event) {
        $event->delete();
        return response()->json(['status' => 'ok']);
    }



    public function solicitarVehiculo()
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
        $agenda = DB::table('agenda')
            ->leftJoin('personal', 'agenda.id_fiscal', '=', 'personal.id_personal')
            ->leftJoin('tra_conductores', 'agenda.id_conductor', '=', 'tra_conductores.id_conductor')
            ->select('agenda.*', 'personal.apellido_paterno', 'personal.apellido_materno', 'personal.nombres', 
            DB::raw("CONCAT(tra_conductores.apellido_paterno, ' ', tra_conductores.apellido_materno, ' ', tra_conductores.nombres) AS conductor"),
            'tra_conductores.nrocelular'
            )
            ->where('agenda.id_dependencia',Auth::user()->personal->id_dependencia)
            ->where('agenda.despacho',Auth::user()->personal->despacho)
            ->orderBy('fechahora_inicia', 'desc') 
            ->get();
        return view('agenda.solicitarvehiculo', compact('personal','agenda'));
    }
    public function grabarSolicitud(Request $request) {
        $fecha = $request->input('start');
        $hora  = $request->input('hstart');
        $fechaHora = $hora
            ? $fecha . ' ' . $hora
            : $fecha . ' 00:00:00';

        $fechaf = $request->input('end');
        $horaf  = $request->input('hend');
        if (empty($fechaf)) {
            $fechaHoraf = null; 
        } elseif (empty($hora)) {
            $fechaHoraf = $fechaf . ' 00:00:00';
        } else {
            $fechaHoraf = $fechaf . ' ' . $horaf;
        }


        $event = DB::table('agenda')->insert([
            'tipo' => $request->input('tipo') ,
            'id_fiscal' => $request->input('fiscal') ,
            'id_dependencia' => Auth::user()->personal->id_dependencia,
            'despacho' => Auth::user()->personal->despacho,
            'asunto' => $request->input('asunto') ,
            'detalle' => $request->input('detalle') ,
            'fechahora_inicia' => $fechaHora,
            'fechahora_termina' => $fechaHoraf,
            'estado' => "S",
            'id_operador' => Auth::user()->id_personal,
        ]);
        //$event = Event::create($request->all());
        //return response()->json($event);
        return response()->json([
            'success' => true,
        ]);        
    }



}
