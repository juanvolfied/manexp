<?php

namespace App\Http\Controllers;

use App\Models\DepPolicial;
use Illuminate\Http\Request;

class DepPoliController extends Controller
{
    public function index()
    {
        $deppoli = DepPolicial::all();
        return view('dependenciapolicial.index', compact('deppoli'));
    }

    public function create()
    {
        return view('dependenciapolicial.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripciondep' => 'required|max:25',
        ]);

        DepPolicial::create($request->all());
        return redirect()->route('deppolicial.index')->with('success', 'Registro creado correctamente.');
    }

    public function show(DepPolicial $deppoli)
    {
        return view('dependenciapolicial.show', compact('deppoli'));
    }

    public function edit(DepPolicial $deppoli)
    {
        return view('dependenciapolicial.edit', compact('deppoli'));
    }

    public function update(Request $request, DepPolicial $deppoli)
    {
        $request->validate([
            'descripciondep' => 'required|max:25',
        ]);

        $deppoli->update($request->all());
        return redirect()->route('deppolicial.index')->with('success', 'Registro actualizado.');
    }

    public function destroy(DepPolicial $deppoli)
    {
        $deppoli->delete();
        return redirect()->route('deppolicial.index')->with('success', 'Registro eliminado.');
    }
}
