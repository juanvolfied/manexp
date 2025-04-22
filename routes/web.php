<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Http\Controllers\MenuController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\UsuarioLoginController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PerfilUsuarioController;


Route::get('/login', [UsuarioLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UsuarioLoginController::class, 'login'])->name('usuario.login');
Route::post('/logout', [UsuarioLoginController::class, 'logout'])->name('usuario.logout');
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/principal');
    }
    return redirect('/login');    
    //return 'Bienvenido!';
})->middleware('auth');

Route::get('/principal', [MenuController::class, 'mostrarMenu']);

Route::get('/inventario', [MenuController::class, 'nuevoExpediente'])->name('inventario');
Route::post('/buscar-inventar', [MenuController::class, 'buscarPorCodigo'])->name('inventario.buscar');
Route::post('/expediente-lect', [MenuController::class, 'grabalecturaExpediente'])->name('expediente.lectura');
Route::post('/expediente-inve', [MenuController::class, 'grabainventarioExpediente'])->name('expediente.inventa');
Route::post('/eliminar-item', [MenuController::class, 'eliminarItem'])->name('elimina.item');

Route::get('/seguimiento', [MenuController::class, 'seguimientoInventario'])->name('seginventario');
//Route::get('/detalle/{nro_inv}', [MenuController::class, 'mostrarDetalle'])->name('seguimiento.detalle');
Route::post('/seguimiento', [MenuController::class, 'mostrarDetalle'])->name('seguimiento.detalle');


// Ruta para listar
Route::get('/personal', [PersonalController::class, 'index'])->name('personal.index');
// Ruta para formulario de creación
Route::get('/personal/create', [PersonalController::class, 'create'])->name('personal.create');
// Ruta para guardar nuevo personal
Route::post('/personal', [PersonalController::class, 'store'])->name('personal.store');
// Ruta para mostrar un registro (opcional)
Route::get('/personal/{personal}', [PersonalController::class, 'show'])->name('personal.show');
// Ruta para formulario de edición
Route::get('/personal/{personal}/edit', [PersonalController::class, 'edit'])->name('personal.edit');
// Ruta para actualizar un registro existente
Route::put('/personal/{personal}', [PersonalController::class, 'update'])->name('personal.update');
// Ruta para eliminar un registro
Route::delete('/personal/{personal}', [PersonalController::class, 'destroy'])->name('personal.destroy');


// Ruta para listar
Route::get('/usuarios', [UsuarioLoginController::class, 'index'])->name('usuarios.index');
// Ruta para formulario de creación
Route::get('/usuarios/create', [UsuarioLoginController::class, 'create'])->name('usuarios.create');
// Ruta para guardar nuevo personal
Route::post('/usuarios', [UsuarioLoginController::class, 'store'])->name('usuarios.store');
// Ruta para mostrar un registro (opcional)
Route::get('/usuarios/{usuarios}', [UsuarioLoginController::class, 'show'])->name('usuarios.show');
// Ruta para formulario de edición
Route::get('/usuarios/{usuarios}/edit', [UsuarioLoginController::class, 'edit'])->name('usuarios.edit');
// Ruta para actualizar un registro existente
Route::put('/usuarios/{usuarios}', [UsuarioLoginController::class, 'update'])->name('usuarios.update');
// Ruta para eliminar un registro
Route::delete('/usuarios/{usuarios}', [UsuarioLoginController::class, 'destroy'])->name('usuarios.destroy');


// Ruta para listar
Route::get('/perfilusuario', [PerfilUsuarioController::class, 'index'])->name('perfilusuario.index');
// Ruta para formulario de creación
Route::get('/perfilusuario/create', [PerfilUsuarioController::class, 'create'])->name('perfilusuario.create');
// Ruta para guardar nuevo personal
Route::post('/perfilusuario', [PerfilUsuarioController::class, 'store'])->name('perfilusuario.store');
// Ruta para mostrar un registro (opcional)
Route::get('/perfilusuario/{id_usuario}/{id_perfil}', [PerfilUsuarioController::class, 'show'])->name('perfilusuario.show');
// Ruta para formulario de edición
Route::get('/perfilusuario/{id_usuario}/{id_perfil}/edit', [PerfilUsuarioController::class, 'edit'])->name('perfilusuario.edit');
// Ruta para actualizar un registro existente
Route::put('/perfilusuario/{id_usuario}/{id_perfil}', [PerfilUsuarioController::class, 'update'])->name('perfilusuario.update');
// Ruta para eliminar un registro
Route::delete('/perfilusuario/{id_usuario}/{id_perfil}', [PerfilUsuarioController::class, 'destroy'])->name('perfilusuario.destroy');

