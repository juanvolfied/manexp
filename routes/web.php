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

/*
Route::get('/empleado/login', [EmpleadoLoginController::class, 'showLoginForm'])->name('empleado.login.form');
Route::post('/empleado/login', [EmpleadoLoginController::class, 'login'])->name('empleado.login');
Route::post('/empleado/logout', [EmpleadoLoginController::class, 'logout'])->name('empleado.logout');
Route::get('/dashboard', function () {
    return 'Bienvenido!';
})->middleware('auth');
*/
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


//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/principal', [MenuController::class, 'mostrarMenu']);
//Route::get('/principal', [MenuController::class, 'mostrarMenu'])->middleware('auth');

Route::get('/inventario', [MenuController::class, 'nuevoExpediente'])->name('inventario');

Route::post('/buscar-inventar', [MenuController::class, 'buscarPorCodigo'])->name('inventario.buscar');
Route::post('/expediente-lect', [MenuController::class, 'grabalecturaExpediente'])->name('expediente.lectura');
Route::post('/expediente-inve', [MenuController::class, 'grabainventarioExpediente'])->name('expediente.inventa');
Route::post('/eliminar-item', [MenuController::class, 'eliminarItem'])->name('elimina.item');

Route::get('/seguimiento', [MenuController::class, 'seguimientoInventario'])->name('seginventario');
//Route::get('/detalle/{nro_inv}', [MenuController::class, 'mostrarDetalle'])->name('seguimiento.detalle');
Route::post('/seguimiento', [MenuController::class, 'mostrarDetalle'])->name('seguimiento.detalle');
