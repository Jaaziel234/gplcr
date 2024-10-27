<?php

use Illuminate\Support\Facades\Route;
//agregamos los siguientes controladores
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\MovimientoEquipoController;
use App\Http\Controllers\MovimientoEquipoDetalleController;
use App\Http\Controllers\MovimientoEquipoSalidaController;
use App\Http\Controllers\SucursalController;
use Illuminate\Support\Facades\Auth;

use App\Models\MovimientoEquipo;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Quitar este codigo para que pueda redireccionar a formulario de registro
Route::get('/register', function () {
    return redirect('/login'); // Redireccionar a la ruta de login
});

//y creamos un grupo de rutas protegidas para los controladores


Route::group(['middleware' => ['auth']], function () {
    Route::get('/listado', [App\Http\Controllers\DatatableController::class, 'index'])->name('listado'); //listar equipos
    Route::get('/search', [App\Http\Controllers\DatatableController::class, 'search'])->name('search'); //buscar equipos 
    Route::get('/home', [App\Http\Controllers\RolController::class, 'dashboard'])->name('dashboard'); //Mostrar contenido si es Admin y ocultar si no lo es
    Route::get('/equipos/ver-modal-equipo', [EquipoController::class, 'verModalEquipo']); //Muestra los modales al dar click en Ver en modulos> Equipos y Movimientos Sucursal
    
    Route::get('movimientos_equipos/create', [MovimientoEquipoController::class, 'create'])->name('movimientos_equipos.create');
    Route::post('movimientos_equipos', [MovimientoEquipoController::class, 'store'])->name('movimientos_equipos.store');
    Route::get('movimientos/{id}/aceptar', [MovimientoEquipoController::class, 'aceptarMovimiento'])->name('movimientos.aceptar');
    // Ruta para obtener los detalles de los equipos asociados a un movimiento y mostrarlo en el modal
    Route::get('/movimientos/{id}/equipos', [MovimientoEquipoController::class, 'getEquiposByMovimiento'])->name('movimientos.equipos');
    // Ruta para obtener la informacion sobre equipos en movimiento al momento de seleccionar un equipo 
    Route::get('/validar-equipo-movimiento', [MovimientoEquipoController::class, 'validarEquipoEnMovimiento'])->name('validar.equipo.movimiento');
    // Ruta que nos permite ejecutar getEstadoMovimiento en el controlador y verificar que el movimiento ha sido aceptado para ejecutar el modal de estados.
    Route::get('/movimientos/{id}/estado', [MovimientoEquipoController::class, 'getEstadoMovimiento']);
    // Ruta que nos permite ejecutar store para poder alamcenar los registros enviados desde los estados. 
    Route::post('/movimientos/detalle/store', [MovimientoEquipoDetalleController::class, 'store'])->name('detalle.store');

    //Ruta que nos permite ejecutar getDetalleMovimientoEstadoAsignado y buscar especificamente los registros de la funcion y mostrarlos en modal
    route::get('/detalleasignadomovimientoEstado', [MovimientoEquipoDetalleController::class, 'getDetalleMovimientoEstadoAsignado']);

    //Ruta que nos permite ejecutar getDetalleMovimientoEstadoReparacion y buscar especificamente los registros de la funcion y mostrarlos en modal
    Route::get('/detallereparacionmovimientoEstado', [MovimientoEquipoDetalleController::class, 'getDetalleMovimientoEstadoReparacion']);
    //Ruta que nos permite ejecutar getDetalleMovimientoEstadoPrueba y buscar especificamente los registros de la funcion y mostralos en modal
    Route::get('/detallepruebamovimientoEstado', [MovimientoEquipoDetalleController::class, 'getDetalleMovimientoEstadoPrueba']);
    //Ruta que nos permite ejecutar movimientoequiposalida y guardar los registros de salida de un movimiento. 
    Route::post('/movimientoequiposalida/store', [MovimientoEquipoSalidaController::class, 'store'])->name('movimientoequiposalida.store');
    //Ruta que al enviar los datos desde el modal de salida de equipos individual permite reidreccionar a la vista principal de movimientos. 
    Route::get('/movimientos_equipos', [MovimientoEquipoController::class, 'index'])->name('movimientos_equipos.index');
    //Ruta que permite ejecutar la funcion para obtener los detalles de salida del equipo a mostrarse en un modal 
    Route::get('/detalleSalidaEstado', [MovimientoEquipoSalidaController::class, 'getDetalleSalidaEstado']);
    //Ruta que permite ejecutar la funcion para actualizar directamrnte el estado, es decir, cuando acepten en la sucursal de origen la salida.
    Route::post('/update-estadosalidaasuc', [MovimientoEquipoSalidaController::class, 'updateEstadoSalidaASucursal'])->name('update.estado');

    Route::resource('roles', RolController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('equipos', EquipoController::class);
    Route::resource('sucursales', SucursalController::class);
    Route::resource('movimientos', MovimientoEquipoController::class);
});
