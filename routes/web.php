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
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\DepPoliController;
use App\Http\Controllers\SolicitudCarpetasController;
use App\Http\Controllers\CarpetasSGFController;
use App\Http\Controllers\TransporteController;
use App\Http\Controllers\AgendaController;

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

//mantenimiento
Route::get('/backup', [BackupController::class, 'mostrarBackupForm'])->name('backup');
Route::get('/backup/generar', [BackupController::class, 'generate'])->name('backup.generar');
Route::get('/backup/descargar/{filename}', function ($filename) {
    return redirect('backups/descargar.php?nomarchivo=' . urlencode($filename));
/*
    $path = storage_path("app/backups/{$filename}");
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->download($path);*/
})->name('backup.descargar');

Route::get('/mantenimiento/reactiva', [MantenimientoController::class, 'mostrarReactivacion'])->name('reactivainventario');
Route::post('/mantenimiento/reactivabuscar', [MantenimientoController::class, 'buscarPorCodigo'])->name('reactivainventariobuscar');
Route::post('/mantenimiento/grabareactiva', [MantenimientoController::class, 'grabaReactivacion'])->name('reactivainventariograbar');
Route::get('/mantenimiento/verdependencias', [MantenimientoController::class, 'mostrarDependencias'])->name('verdependencias');
Route::post('/mantenimiento/{id}/cambiaestadover', [MantenimientoController::class, 'cambiaEstadoDependencia'])->name('dependenciacambiaestado');
Route::get('/mantenimiento/verdependenciassgf', [MantenimientoController::class, 'mostrarDependenciasSGF'])->name('verdependenciassgf');
Route::post('/mantenimiento/{id}/cambiaestadoversgf', [MantenimientoController::class, 'cambiaEstadoDependenciaSGF'])->name('dependenciacambiaestadosgf');



Route::get('/principal', [MenuController::class, 'mostrarMenu'])->name('principal');

Route::get('/inventario', [MenuController::class, 'nuevoExpediente'])->name('inventario');
Route::post('/buscar-inventar', [MenuController::class, 'buscarPorCodigo'])->name('inventario.buscar');
Route::post('/expediente-lect', [MenuController::class, 'grabalecturaExpediente'])->name('expediente.lectura');
Route::post('/expediente-inve', [MenuController::class, 'grabainventarioExpediente'])->name('expediente.inventa');
Route::post('/eliminar-item', [MenuController::class, 'eliminarItem'])->name('elimina.item');
Route::get('/inventariov2', [MenuController::class, 'nuevoExpedientev2'])->name('inventariov2');
Route::get('/inventariov3', [MenuController::class, 'nuevoExpedientev3'])->name('inventariov3');


Route::get('/seguimiento', [MenuController::class, 'seguimientoInventario'])->name('seginventario');
//Route::get('/detalle/{nro_inv}', [MenuController::class, 'mostrarDetalle'])->name('seguimiento.detalle');
Route::post('/seguimiento', [MenuController::class, 'mostrarDetalle'])->name('seguimiento.detalle');
Route::get('/grafico', [InventarioController::class, 'mostrarVistaGrafico'])->name('grafico');
Route::get('/grafico-usuario', [InventarioController::class, 'graficoPorUsuario'])->name('grafico.usuario');
Route::get('/graficopie', [InventarioController::class, 'mostrarGraficoPie'])->name('graficopie');
Route::get('/graficopie-fecha', [InventarioController::class, 'graficoPieFecha'])->name('graficopie.fecha');

Route::get('/validainventario', [InventarioController::class, 'validaInventario'])->name('validainventario');
Route::post('/validaimprime', [InventarioController::class, 'validaImprime'])->name('validaimprime');




//Route::get('/mesapartes', [MesaController::class, 'index'])->name('mesapartes.index');
Route::match(['get', 'post'], '/mesapartes', [MesaController::class, 'index'])->name('mesapartes.index');

Route::get('/mesapartes/registrolibros', [MesaController::class, 'nuevoEscrito'])->name('mesapartes.libroescritos');
Route::get('/mesapartes/registrolibrosv', [MesaController::class, 'nuevoEscritoV'])->name('mesapartes.libroescritosv');
Route::get('/mesapartes/consultafiscal', [MesaController::class, 'consultarFiscal'])->name('mesapartes.consulta');
Route::post('/mesapartes/consultafiscaldetalle', [MesaController::class, 'consultarFiscaldetalle'])->name('mesapartes.consultadetalle');
Route::post('/mesapartes/store', [MesaController::class, 'store'])->name('mesapartes.store');
Route::post('/mesapartes/storev', [MesaController::class, 'storetpv'])->name('mesapartes.storev');
Route::get('/mesapartes/consultaintervalofechas', [MesaController::class, 'consultarIntervalo'])->name('mesapartes.consultaintervalo');
Route::post('/mesapartes/consultaintervalodetalle', [MesaController::class, 'consultarIntervalodetalle'])->name('mesapartes.consultaintervalodetalle');
Route::post('/mesapartes/anular/{codescrito}', [MesaController::class, 'anularEscrito'])->name('mesapartes.anular');

Route::get('/mesapartes/consultafiltros', [MesaController::class, 'consultarFiltros'])->name('mesapartes.consultafiltros');
Route::get('/mesapartes/calendarcargos', [MesaController::class, 'calendarCargos']);

Route::post('/mesapartes/consultafiltrosdetalle', [MesaController::class, 'consultarFiltrosdetalle'])->name('mesapartes.consultafiltrosdetalle');

Route::get('/mesapartes/estadisticas', [MesaController::class, 'estadisticas'])->name('mesapartes.estadisticas');
Route::get('/mesapartes/estadisticasdetalle', [MesaController::class, 'estadisticasdetalle'])->name('mesapartes.estadisticasdetalle');

Route::get('/mesapartes/creacarpetasf', [MesaController::class, 'nuevoCarpetasf'])->name('mesapartes.registrocarpetasf');
Route::post('/mesapartes/buscacarpetasf', [MesaController::class, 'buscaCarpetasf'])->name('mesapartes.buscacarpetasf');
Route::post('/mesapartes/buscatcerro', [MesaController::class, 'buscaTCerroenviado'])->name('mesapartes.buscatcerro');
Route::post('/mesapartes/buscacarpeta', [MesaController::class, 'buscaCarpeta'])->name('mesapartes.buscacarpeta');
Route::post('/mesapartes/grabacarpeta', [MesaController::class, 'grabaCarpeta'])->name('mesapartes.grabacarpeta');
//Route::get('/mesapartes/{codigocf}/pdf', [MesaController::class, 'imprimirCarpetasf'])->name('mesapartes.imprimecarpetasf');
Route::get('/mesapartes/imprimecarpetaspdf', [MesaController::class, 'imprimirCarpetasf'])->name('mesapartes.imprimecarpetasf');
Route::get('/mesapartes/reportecarpetasf01', [MesaController::class, 'reporteCarpetasf01'])->name('mesapartes.reportecarpetasf01');
Route::get('/mesapartes/reportecarpetasf02', [MesaController::class, 'reporteCarpetasf02'])->name('mesapartes.reportecarpetasf02');
Route::post('/mesapartes/imprimecarpetasfec', [MesaController::class, 'imprimirCarpetasfec'])->name('mesapartes.imprimecarpetasfec');


//Route::get('/mesapartes/{anolibro}/{numero}/edit', [MesaController::class, 'edit'])->name('mesapartes.edit');
//Route::put('/mesapartes/{anolibro}/{numero}', [MesaController::class, 'update'])->name('mesapartes.update');
Route::get('/mesapartes/{codescrito}/edit', [MesaController::class, 'edit'])->name('mesapartes.edit');
Route::put('/mesapartes/{codescrito}', [MesaController::class, 'update'])->name('mesapartes.update');

Route::get('/mesapartes/{fiscal}/{fecha}/pdf', [MesaController::class, 'generarConsFiscalPDF'])->name('escritosfiscal.pdf');
Route::get('/mesapartes/{codigogenerar}/pdf', [MesaController::class, 'generarCodigoBarrasPDF'])->name('mesapartescodbarras.pdf');

Route::get('/mesapartes/consultaescritos', [MesaController::class, 'consultarEscritos'])->name('mesapartes.consultaescritos');
Route::post('/mesapartes/consultaescritosdet', [MesaController::class, 'consultarEscritosdetalle'])->name('mesapartes.consultaescritosdetalle');

Route::get('/mesapartes/comprimeescritospdf', [MesaController::class, 'compresionindex'])->name('mesapartes.comprimeindex');
Route::get('/mesapartes/{anio}/{mes}', [MesaController::class, 'verificarArchivos']);
Route::post('/mesapartes/comprimir', [MesaController::class, 'comprimirArchivo'])->name('mesapartes.comprime');

Route::get('/mesapartes/upload', [MesaController::class, 'showupload'])->name('mesapartes.showupload');
Route::post('/mesapartes/uploadchunk', [MesaController::class, 'uploadChunk'])->name('upload.chunk');
Route::post('/mesapartes/uploadchunkcargos', [MesaController::class, 'uploadChunkCargos'])->name('upload.chunkcargos');
Route::post('/mesapartes/check-existing', [MesaController::class, 'checkExistingFiles'])->name('upload.checkExisting');



// Ruta para listar
Route::get('/deppolicial', [DepPoliController::class, 'index'])->name('deppolicial.index');
// Ruta para formulario de creaci�n
Route::get('/deppolicial/create', [DepPoliController::class, 'create'])->name('deppolicial.create');
// Ruta para guardar nuevo 
Route::post('/deppolicial', [DepPoliController::class, 'store'])->name('deppolicial.store');
// Ruta para mostrar un registro (opcional)
Route::get('/deppolicial/{deppoli}', [DepPoliController::class, 'show'])->name('deppolicial.show');
// Ruta para formulario de edici�n
Route::get('/deppolicial/{deppoli}/edit', [DepPoliController::class, 'edit'])->name('deppolicial.edit');
// Ruta para actualizar un registro existente
Route::put('/deppolicial/{deppoli}', [DepPoliController::class, 'update'])->name('deppolicial.update');
// Ruta para eliminar un registro
Route::delete('/deppolicial/{deppoli}', [DepPoliController::class, 'destroy'])->name('deppolicial.destroy');

// Ruta para listar
Route::get('/personal', [PersonalController::class, 'index'])->name('personal.index');
// Ruta para formulario de creaci�n
Route::get('/personal/create', [PersonalController::class, 'create'])->name('personal.create');
// Ruta para guardar nuevo personal
Route::post('/personal', [PersonalController::class, 'store'])->name('personal.store');
// Ruta para mostrar un registro (opcional)
Route::get('/personal/{personal}', [PersonalController::class, 'show'])->name('personal.show');
// Ruta para formulario de edici�n
Route::get('/personal/{personal}/edit', [PersonalController::class, 'edit'])->name('personal.edit');
// Ruta para actualizar un registro existente
Route::put('/personal/{personal}', [PersonalController::class, 'update'])->name('personal.update');
// Ruta para eliminar un registro
Route::delete('/personal/{personal}', [PersonalController::class, 'destroy'])->name('personal.destroy');


// Ruta para listar
Route::get('/usuarios', [UsuarioLoginController::class, 'index'])->name('usuarios.index');
// Ruta para formulario de creaci�n
Route::get('/usuarios/create', [UsuarioLoginController::class, 'create'])->name('usuarios.create');
// Ruta para guardar nuevo personal
Route::post('/usuarios', [UsuarioLoginController::class, 'store'])->name('usuarios.store');
// Ruta para mostrar un registro (opcional)
Route::get('/usuarios/{usuarios}', [UsuarioLoginController::class, 'show'])->name('usuarios.show');
// Ruta para formulario de edici�n
Route::get('/usuarios/{usuarios}/edit', [UsuarioLoginController::class, 'edit'])->name('usuarios.edit');
// Ruta para actualizar un registro existente
Route::put('/usuarios/{usuarios}', [UsuarioLoginController::class, 'update'])->name('usuarios.update');
// Ruta para eliminar un registro
Route::delete('/usuarios/{usuarios}', [UsuarioLoginController::class, 'destroy'])->name('usuarios.destroy');


// Ruta para listar
Route::get('/perfilusuario', [PerfilUsuarioController::class, 'index'])->name('perfilusuario.index');
// Ruta para formulario de creaci�n
Route::get('/perfilusuario/create', [PerfilUsuarioController::class, 'create'])->name('perfilusuario.create');
// Ruta para guardar nuevo personal
Route::post('/perfilusuario', [PerfilUsuarioController::class, 'store'])->name('perfilusuario.store');
// Ruta para mostrar un registro (opcional)
Route::get('/perfilusuario/{id_usuario}/{id_perfil}', [PerfilUsuarioController::class, 'show'])->name('perfilusuario.show');
// Ruta para formulario de edici�n
Route::get('/perfilusuario/{id_usuario}/{id_perfil}/edit', [PerfilUsuarioController::class, 'edit'])->name('perfilusuario.edit');
// Ruta para actualizar un registro existente
Route::put('/perfilusuario/{id_usuario}/{id_perfil}', [PerfilUsuarioController::class, 'update'])->name('perfilusuario.update');
// Ruta para eliminar un registro
Route::delete('/perfilusuario/{id_usuario}/{id_perfil}', [PerfilUsuarioController::class, 'destroy'])->name('perfilusuario.destroy');

// Ruta para listar
Route::get('/expediente', [ExpedienteController::class, 'index'])->name('expediente.index');

Route::get('/expediente/importa', [ExpedienteController::class, 'indexImporta'])->name('expediente.importa');
Route::post('/expediente/buscapaq', [ExpedienteController::class, 'importaBuscaPaq'])->name('expediente.buscapaqtransfe');
Route::post('/expediente/grabaimporta', [ExpedienteController::class, 'grabaImporta'])->name('expediente.grabaimporta');


// Ruta para formulario de creaci�n
Route::get('/expediente/create', [ExpedienteController::class, 'create'])->name('expediente.create');
// Ruta para guardar nuevo personal
Route::post('/expediente', [ExpedienteController::class, 'store'])->name('expediente.store');
// Ruta para mostrar un registro (opcional)
Route::get('/expediente/{expediente}', [ExpedienteController::class, 'show'])->name('expediente.show');
// Ruta para formulario de edici�n
Route::get('/expediente/{expediente}/edit', [ExpedienteController::class, 'edit'])->name('expediente.edit');
// Ruta para actualizar un registro existente
Route::put('/expediente/{expediente}', [ExpedienteController::class, 'update'])->name('expediente.update');
// Ruta para eliminar un registro
Route::delete('/expediente/{expediente}', [ExpedienteController::class, 'destroy'])->name('expediente.destroy');

Route::post('/expediente-busca', [ExpedienteController::class, 'buscaExpediente'])->name('expediente.busca');
Route::get('/expediente-seg', [ExpedienteController::class, 'seguimiento'])->name('expediente.seguimiento');
Route::post('/expediente-bus', [ExpedienteController::class, 'buscaseguimiento'])->name('expediente.buscacarpetas');
Route::post('/expediente-det', [ExpedienteController::class, 'detalleseguimiento'])->name('expediente.segdetalle');



Route::get('/internamiento-lista', [ExpedienteController::class, 'indexinternamiento'])->name('internamiento.index');
Route::get('/internamiento-exp', [ExpedienteController::class, 'internamiento'])->name('internamiento.create');
Route::post('/expedientemov-busca', [ExpedienteController::class, 'buscaExpedienteMov'])->name('expedientemov.busca');
Route::post('/internamiento-graba', [ExpedienteController::class, 'guardaInternamiento'])->name('internamiento.graba');
Route::post('/internamiento-envio', [ExpedienteController::class, 'envioInternamiento'])->name('internamiento.envio');
Route::get('/internamiento-recep', [ExpedienteController::class, 'indexrecepinternamiento'])->name('internamiento.recepcion');
Route::get('/internamiento-recep/{tipo_mov}/{ano_mov}/{nro_mov}/ver', [ExpedienteController::class, 'verifrecepinternamiento'])->name('internamiento.ver');
Route::get('/internamiento-recep/{tipo_mov}/{ano_mov}/{nro_mov}/det', [ExpedienteController::class, 'detallerecepinternamiento'])->name('internamiento.det');
Route::post('/internamiento-recepciona', [ExpedienteController::class, 'grabarecepcionInternamiento'])->name('internamiento.grabarecepcion');
Route::post('/internamiento-recepcionacodigo', [ExpedienteController::class, 'grabarecepcioncodigoInternamiento'])->name('internamiento.grabarecepcioncodigo');
Route::get('/internamiento/{tipo_mov}/{ano_mov}/{nro_mov}/edit', [ExpedienteController::class, 'editInternamiento'])->name('internamiento.edit');
Route::put('/internamiento/{tipo_mov}/{ano_mov}/{nro_mov}', [ExpedienteController::class, 'updateInternamiento'])->name('internamiento.update');
Route::get('/internamiento/{tipo_mov}/{ano_mov}/{nro_mov}/pdf', [ExpedienteController::class, 'generarGuiaIntPDF'])->name('internamiento.pdf');
Route::post('/internamiento-rechaza', [ExpedienteController::class, 'rechazarecepcionInternamiento'])->name('internamiento.rechazarecepcion');


Route::get('/solicitud', [SolicitudCarpetasController::class, 'indexSolicitud'])->name('solicitud.index');
Route::get('/solicitud/create', [SolicitudCarpetasController::class, 'createSolicitud'])->name('solicitud.create');
Route::post('/solicitud/busca', [SolicitudCarpetasController::class, 'buscaCarpetaSolicitud'])->name('solicitud.buscacarpeta');
Route::post('/solicitud/graba', [SolicitudCarpetasController::class, 'grabaSolicitud'])->name('solicitud.graba');
Route::post('/solicitud/envio', [SolicitudCarpetasController::class, 'envioSolicitud'])->name('solicitud.envio');
Route::get('/solicitud/atencion', [SolicitudCarpetasController::class, 'indexAtencionSolicitud'])->name('solicitud.atencion');
Route::post('/solicitud/atenciongraba', [SolicitudCarpetasController::class, 'grabaAtencionSolicitud'])->name('solicitud.grabaatencion');
Route::post('/solicitud/recepcion/det', [SolicitudCarpetasController::class, 'detalleRecepcionSolicitud'])->name('solicitud.recepcion');
Route::post('/solicitud/recepciongraba', [SolicitudCarpetasController::class, 'grabaRecepcionCarpetasSolicitud'])->name('solicitud.grabarecepcion');

Route::get('/solicitud/atencion/{tipo_mov}/{ano_mov}/{nro_mov}/ver', [SolicitudCarpetasController::class, 'verifAtencionSolicitud'])->name('solicitud.ver');
Route::get('/solicitud/{tipo_mov}/{ano_mov}/{nro_mov}/edit', [SolicitudCarpetasController::class, 'editSolicitud'])->name('solicitud.edit');
Route::put('/solicitud/{tipo_mov}/{ano_mov}/{nro_mov}', [SolicitudCarpetasController::class, 'updateSolicitud'])->name('solicitud.update');

Route::get('/devolucion', [SolicitudCarpetasController::class, 'indexDevolucion'])->name('devolucion.index');
Route::get('/devolucion/create', [SolicitudCarpetasController::class, 'createDevolucion'])->name('devolucion.create');
Route::post('/devolucion/busca', [SolicitudCarpetasController::class, 'buscaCarpetaDevolucion'])->name('devolucion.buscacarpeta');
Route::post('/devolucion/graba', [SolicitudCarpetasController::class, 'grabaDevolucion'])->name('devolucion.graba');
Route::post('/devolucion/envio', [SolicitudCarpetasController::class, 'envioDevolucion'])->name('devolucion.envio');
Route::get('/devolucion/atencion', [SolicitudCarpetasController::class, 'indexAtencionDevolucion'])->name('devolucion.atencion');
Route::get('/devolucion/atencion/{tipo_mov}/{ano_mov}/{nro_mov}/ver', [SolicitudCarpetasController::class, 'verifAtencionDevolucion'])->name('devolucion.ver');
Route::put('/devolucion/{tipo_mov}/{ano_mov}/{nro_mov}', [SolicitudCarpetasController::class, 'updateDevolucion'])->name('devolucion.update');
Route::get('/devolucion/{tipo_mov}/{ano_mov}/{nro_mov}/edit', [SolicitudCarpetasController::class, 'editDevolucion'])->name('devolucion.edit');
Route::post('/devolucion/recepciongraba', [SolicitudCarpetasController::class, 'grabaRecepcionCarpetasDevolucion'])->name('devolucion.grabarecepcion');



Route::match(['get', 'post'], '/carpetassgf', [CarpetasSGFController::class, 'index'])->name('carpetassgf.carpetassgfindex');
Route::get('/carpetassgf/registro', [CarpetasSGFController::class, 'nuevoCarpetaSGF'])->name('carpetassgf.registrocarpetassgf');
Route::post('/carpetassgf/store', [CarpetasSGFController::class, 'store'])->name('carpetassgf.store');
Route::post('/carpetassgf/busca', [CarpetasSGFController::class, 'buscaCarpeta'])->name('carpetassgf.buscacarpeta');
Route::get('/carpetassgf/{id_generado}/edit', [CarpetasSGFController::class, 'edit'])->name('carpetassgf.edit');
Route::put('/carpetassgf/{id_generado}', [CarpetasSGFController::class, 'update'])->name('carpetassgf.update');


Route::get('/carpetassgf/reporteavance', [CarpetasSGFController::class, 'mostrarGraficoDatos'])->name('carpetassgf.carpetassgreporte');
Route::get('/carpetassgf/grafico', [CarpetasSGFController::class, 'graficoPorIntervalo'])->name('carpetassgf.grafico');



//TRANSPORTE
// Ruta para listar
Route::get('/transportec/conductor', [TransporteController::class, 'indexConductor'])->name('transporte.indexconductor');
// Ruta para formulario de creaci�n
Route::get('/transportec/createconductor', [TransporteController::class, 'createConductor'])->name('transporte.createconductor');
// Ruta para guardar nuevo personal
Route::post('/transportec/conductor', [TransporteController::class, 'storeConductor'])->name('transporte.storeconductor');
// Ruta para mostrar un registro (opcional)
Route::get('/transportec/{conductor}', [TransporteController::class, 'showConductor'])->name('transporte.showconductor');
// Ruta para formulario de edici�n
Route::get('/transportec/{conductor}/edit', [TransporteController::class, 'editConductor'])->name('transporte.editconductor');
// Ruta para actualizar un registro existente
Route::put('/transportec/{conductor}', [TransporteController::class, 'updateConductor'])->name('transporte.updateconductor');
// Ruta para eliminar un registro
Route::delete('/transportec/{conductor}', [TransporteController::class, 'destroyConductor'])->name('transporte.destroyconductor');

// Ruta para listar
Route::get('/transportev/vehiculo', [TransporteController::class, 'indexVehiculo'])->name('transporte.indexvehiculo');
// Ruta para formulario de creaci�n
Route::get('/transportev/createvehiculo', [TransporteController::class, 'createVehiculo'])->name('transporte.createvehiculo');
// Ruta para guardar nuevo personal
Route::post('/transportev/vehiculo', [TransporteController::class, 'storeVehiculo'])->name('transporte.storevehiculo');
// Ruta para mostrar un registro (opcional)
Route::get('/transportev/{nroplaca}', [TransporteController::class, 'showVehiculo'])->name('transporte.showvehiculo');
// Ruta para formulario de edici�n
Route::get('/transportev/{nroplaca}/edit', [TransporteController::class, 'editVehiculo'])->name('transporte.editvehiculo');
// Ruta para actualizar un registro existente
Route::put('/transportev/{nroplaca}', [TransporteController::class, 'updateVehiculo'])->name('transporte.updatevehiculo');
// Ruta para eliminar un registro
Route::delete('/transportev/{nroplaca}', [TransporteController::class, 'destroyVehiculo'])->name('transporte.destroyvehiculo');


Route::get('/transporte/movimiento', [TransporteController::class, 'controlMovimiento'])->name('transporte.movimiento');
Route::post('/transporte/grabamovimiento', [TransporteController::class, 'grabaMovimiento'])->name('transporte.grabamovimiento');
Route::post('/transporte/valida', [TransporteController::class, 'validaIDPlaca'])->name('transporte.valida');
Route::get('/transporte/consultaintervalofechas', [TransporteController::class, 'consultarIntervalo'])->name('transporte.consultaintervalo');
Route::post('/transporte/consultaintervalodetalle', [TransporteController::class, 'consultarIntervalodetalle'])->name('transporte.consultaintervalodetalle');

/*
Route::get('/calendar', function () {
    return view('agenda/calendar');
})->name('agenda.calendar');*/
Route::get('/agendavehicular', [AgendaController::class, 'agendaVehicular'])->name('agenda.agendavehicular');
Route::post('/grabaragendavehicular', [AgendaController::class, 'grabaragendaVehicular'])->name('agenda.grabaragendavehicular');
Route::post('/grabaraprueba', [AgendaController::class, 'grabarAprueba'])->name('agenda.grabaraprueba');

Route::get('/events', [AgendaController::class, 'index']);
Route::post('/events', [AgendaController::class, 'store']);

//Route::get('/admin/agenda', [EventController::class, 'index']);
//Route::post('/admin/agenda/events', [EventController::class, 'store']);
Route::put('/admin/agenda/events/{event}', [AgendaController::class, 'update']);
Route::delete('/admin/agenda/events/{event}', [AgendaController::class, 'destroy']);

/*
Route::get('/solicitarvehiculo', function () {
    return view('agenda/solicitarvehiculo');
})->name('agenda.solicitarvehiculo');
*/
Route::get('/solicitarvehiculo', [AgendaController::class, 'solicitarVehiculo'])->name('agenda.solicitarvehiculo');
Route::post('/grabarsolicitud', [AgendaController::class, 'grabarSolicitud'])->name('agenda.grabarsolicitud');
