<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\Dependencia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
    public function index()
    {
        //$personal = Personal::all();
        $personal = DB::table('personal')
        ->leftJoin('dependencia', 'personal.id_dependencia', '=', 'dependencia.id_dependencia')
        ->select(
            'personal.*',
            'dependencia.descripcion',
        )
        ->orderBy('apellido_paterno', 'asc')
        ->orderBy('apellido_materno', 'asc')
        ->orderBy('nombres', 'asc')
        ->get();

        return view('personal.index', compact('personal'));
    }

    public function create()
    {
        $dependencias = Dependencia::where('activo', 'S')
            ->orderBy('descripcion', 'asc')
            ->get();
        return view('personal.create', compact('dependencias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_personal' => 'required|size:8|unique:personal,id_personal',
            'apellido_paterno' => 'required|max:30',
            'apellido_materno' => 'required|max:30',
            'nombres' => 'required|max:30',
            'id_dependencia' => 'required|integer',
            'despacho' => 'required|integer',
            'activo' => 'required|in:S,N',
        ]);

        //Personal::create($request->all());
        DB::table('personal')->insert([
            'id_personal' => strtoupper( $request->input('id_personal') ),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'nombres' => $request->input('nombres'),
            'id_dependencia' => $request->input('id_dependencia'),
            'despacho' => $request->input('despacho'),
            'activo' => $request->input('activo'),
            'fiscal_asistente' => $request->input('tipocargo'),
            'cargo' => $request->input('cargo'),
        ]);

        return redirect()->route('personal.index')->with('success', 'Registro creado correctamente.');
    }

    public function show(Personal $personal)
    {
        return view('personal.show', compact('personal'));
    }

    public function edit(Personal $personal)
    {
        $dependencias = Dependencia::where('activo', 'S')
            ->orderBy('descripcion', 'asc')
            ->get();
        return view('personal.edit', compact('personal', 'dependencias'));
    }

    public function update(Request $request, Personal $personal)
    {
        $request->validate([
            'apellido_paterno' => 'required|max:30',
            'apellido_materno' => 'required|max:30',
            'nombres' => 'required|max:30',
            'id_dependencia' => 'required|integer',
            'despacho' => 'required|integer',
            'activo' => 'required|in:S,N',
        ]);

        //$personal->update($request->all());
        DB::table('personal')
        ->where('id_personal', $request->input('id_personal'))
        ->update([
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'nombres' => $request->input('nombres'),
            'id_dependencia' => $request->input('id_dependencia'),
            'despacho' => $request->input('despacho'),
            'activo' => $request->input('activo'),
            'fiscal_asistente' => $request->input('tipocargo'),
            'cargo' => $request->input('cargo'),
        ]);


        return redirect()->route('personal.index')->with('success', 'Registro actualizado.');
    }

    public function destroy(Personal $personal)
    {
        $personal->delete();
        return redirect()->route('personal.index')->with('success', 'Registro eliminado.');
    }
}
