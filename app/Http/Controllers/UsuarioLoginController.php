<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;

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
        //dd('login xxx');
            Auth::login($usuario); // Usar login con session
            return redirect()->intended('/principal');
        }

        return back()->withErrors(['usuario' => 'Usuario o contraseña incorrectos.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
