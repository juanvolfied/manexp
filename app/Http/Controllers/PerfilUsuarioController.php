<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;
use App\Models\Personal; 
use Illuminate\Support\Facades\DB;
use App\Models\Perfil; 
use App\Models\PerfilUsuario; 

class PerfilUsuarioController extends Controller
{

    public function index()
    {
            $perfilusuario = DB::table('perfil_usuario')
            ->leftJoin('perfil', 'perfil_usuario.id_perfil', '=', 'perfil.id_perfil')
            ->leftJoin('usuarios', 'perfil_usuario.id_usuario', '=', 'usuarios.id_usuario')
            ->leftJoin('personal', 'usuarios.id_personal', '=', 'personal.id_personal')
            ->select('perfil_usuario.id_usuario', 'apellido_paterno', 'apellido_materno', 'nombres', 'usuario', 'perfil_usuario.id_perfil', 'perfil.descri_perfil', 'perfil_usuario.activo')
            ->orderBy('apellido_paterno', 'asc') 
            ->orderBy('apellido_materno', 'asc') 
            ->orderBy('nombres', 'asc') 
            ->get();
        
        return view('perfilusuario.index', compact('perfilusuario'));
    }

    public function create()
    {
        $usuarios = DB::table('usuarios')
            ->leftJoin('personal', 'usuarios.id_personal', '=', 'personal.id_personal')
            ->select('id_usuario', 'apellido_paterno', 'apellido_materno', 'nombres', 'usuario')
            ->orderBy('apellido_paterno', 'asc') 
            ->orderBy('apellido_materno', 'asc') 
            ->orderBy('nombres', 'asc') 
            ->get();
        $perfiles = Perfil::all();

        return view('perfilusuario.create', compact('usuarios', 'perfiles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|integer',
            'id_perfil' => 'required|integer',
            'activo' => 'required|in:S,N',
        ]);

        PerfilUsuario::create($request->all());
        return redirect()->route('perfilusuario.index')->with('success', 'Registro creado correctamente.');
    }

    public function show(PerfilUsuario $perfilusuario)
    {
        return view('perfilusuario.show', compact('perfilusuario'));
    }

    public function edit(PerfilUsuario $perfilusuario)
    {
        $usuarios = DB::table('usuarios')
            ->leftJoin('personal', 'usuarios.id_personal', '=', 'personal.id_personal')
            ->select('id_usuario', 'apellido_paterno', 'apellido_materno', 'nombres', 'usuario', 'perfil_usuario.activo')
            ->orderBy('apellido_paterno', 'asc') 
            ->orderBy('apellido_materno', 'asc') 
            ->orderBy('nombres', 'asc') 
            ->get();
        $perfiles = Perfil::all();

        return view('perfilusuario.edit', compact('usuarios', 'perfiles'));
    }

    public function update(Request $request, PerfilUsuario $perfilusuario)
    {
        $request->validate([
            'id_personal' => 'required|size:8',
            'usuario' => 'required|max:20',
            'password' => 'required|max:20',
            'activo' => 'required|in:S,N',
        ]);

        $usuarios->update($request->all());
        return redirect()->route('perfilusuario.index')->with('success', 'Registro actualizado.');
    }

    public function destroy($id_usuario, $id_perfil)
    {
        $deleted = PerfilUsuario::where('id_usuario', $id_usuario)
                            ->where('id_perfil', $id_perfil)
                            ->delete();

        if ($deleted) {
            return redirect()->route('perfilusuario.index')->with('success', 'Registro eliminado correctamente.');
        } else {
            return redirect()->route('perfilusuario.index')->with('error', 'No se encontró el registro a eliminar.');
        }
    }

}
