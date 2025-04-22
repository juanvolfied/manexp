<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;
use App\Models\Personal; 
use Illuminate\Support\Facades\DB;

class UsuarioLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('usuario.login');
    }

    public function login(Request $request)
    {
    //dd($request->all()); 
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);
 
        $usuario = Usuarios::where('usuario', $request->usuario)->first();
        if ($usuario && Hash::check($request->password, bcrypt($usuario->password) )) {
            Auth::login($usuario); // Usar login con session
            

    $user = auth()->user();
    if (!$user->perfil) {
        auth()->logout();
        return redirect()->route('login')->withErrors([
            'perfil' => 'El usuario no tiene un perfil asignado.'
        ]);
    }
            
            return redirect()->intended('/principal');
        }

        //return back()->withErrors(['usuario' => 'Usuario o contraseña incorrectos.']);
        return redirect()->route('login')->withErrors([
            'usuario' => utf8_encode('Usuario o contraseña incorrectos.')
        ]);        
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }









    public function index()
    {
        $usuarios = Usuarios::all();
            $usuarios = DB::table('usuarios')
            ->leftJoin('personal', 'usuarios.id_personal', '=', 'personal.id_personal')
            ->select('id_usuario', 'apellido_paterno', 'apellido_materno', 'nombres', 'usuario', 'password', 'usuarios.activo')
            ->orderBy('apellido_paterno', 'asc') 
            ->orderBy('apellido_materno', 'asc') 
            ->orderBy('nombres', 'asc') 
            ->get();
        
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $personal = Personal::orderBy('apellido_paterno', 'asc')
            ->orderBy('apellido_materno', 'asc')
            ->orderBy('nombres', 'asc')
            ->get();
        return view('usuarios.create', compact('personal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_personal' => 'required|size:8',
            'usuario' => 'required|max:20',
            'password' => 'required|max:20',
            'activo' => 'required|in:S,N',
        ]);

        Usuarios::create($request->all());
        return redirect()->route('usuarios.index')->with('success', 'Registro creado correctamente.');
    }

    public function show(Usuarios $usuarios)
    {
        return view('usuarios.show', compact('usuarios'));
    }

    public function edit(Usuarios $usuarios)
    {
        $personal = Personal::orderBy('apellido_paterno', 'asc')
            ->orderBy('apellido_materno', 'asc')
            ->orderBy('nombres', 'asc')
            ->get();
        return view('usuarios.edit', compact('usuarios', 'personal'));
    }

    public function update(Request $request, Usuarios $usuarios)
    {
        $request->validate([
            'id_personal' => 'required|size:8',
            'usuario' => 'required|max:20',
            'password' => 'required|max:20',
            'activo' => 'required|in:S,N',
        ]);

        $usuarios->update($request->all());
        return redirect()->route('usuarios.index')->with('success', 'Registro actualizado.');
    }

    public function destroy(Usuarios $usuarios)
    {
        $usuarios->delete();
        return redirect()->route('usuarios.index')->with('success', 'Registro eliminado.');
    }

}
