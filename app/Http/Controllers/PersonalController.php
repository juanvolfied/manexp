<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    public function index()
    {
        $personal = Personal::all();
        return view('personal.index', compact('personal'));
    }

    public function create()
    {
        return view('personal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_personal' => 'required|size:8|unique:personal,id_personal',
            'apellido_paterno' => 'required|max:30',
            'apellido_materno' => 'required|max:30',
            'nombres' => 'required|max:30',
            'activo' => 'required|in:S,N',
        ]);

        Personal::create($request->all());
        return redirect()->route('personal.index')->with('success', 'Registro creado correctamente.');
    }

    public function show(Personal $personal)
    {
        return view('personal.show', compact('personal'));
    }

    public function edit(Personal $personal)
    {
        return view('personal.edit', compact('personal'));
    }

    public function update(Request $request, Personal $personal)
    {
        $request->validate([
            'apellido_paterno' => 'required|max:30',
            'apellido_materno' => 'required|max:30',
            'nombres' => 'required|max:30',
            'activo' => 'required|in:S,N',
        ]);

        $personal->update($request->all());
        return redirect()->route('personal.index')->with('success', 'Registro actualizado.');
    }

    public function destroy(Personal $personal)
    {
        $personal->delete();
        return redirect()->route('personal.index')->with('success', 'Registro eliminado.');
    }
}
