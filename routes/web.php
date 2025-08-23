<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TarjetaController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\ConductoreController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdminRole;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SetPasswordController;

use Illuminate\Support\Facades\DB;
    
 
// Rutas sin validacion
Route::get('/', fn() => view('home.index'));
Route::get('/home', [HomeController::class, 'index']);
Route::get('/register', [RegisterController::class, 'show']);
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Rutas con  
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LogoutController::class, 'logout']);
    Route::resource('conductores', ConductoreController::class);
    Route::resource('rutas', RutaController::class);
    Route::resource('users', UserController::class);
    Route::get('/horario', [HomeController::class, 'horario']);
    Route::get('/usuario', [HomeController::class, 'perfil']);
    Route::post('/recargar', [HomeController::class, 'recargar'])->name('home.recargar');
    Route::post('/compartir', [HomeController::class, 'compartir'])->name('home.compartir');
    Route::get('/apopa', [HomeController::class, 'apopa']);
    Route::get('/cojute', [HomeController::class, 'cojute']);
    Route::get('/ilobasco', [HomeController::class, 'ilobasco']);
    Route::get('/lucia', [HomeController::class, 'lucia']);
    Route::get('/quezalte', [HomeController::class, 'quezalte']);
    Route::get('/sanmartin', [HomeController::class, 'sanmartin']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin-usuarios', [AdminController::class, 'usuarios']);
    Route::get('/admin-rutas', [AdminController::class, 'rutas']);
    Route::get('/admin-conductores', [AdminController::class, 'conductores']);
});

Route::get('/datosRutas', function() {
    try {
        //code...
        //$datos = DB::table('rutas')->select('id', 'origen')->get();
        $datos = DB::table('conductores as cond')->leftjoin('rutas as r', 'cond.rutas_id', '=', 'r.id')->select(['r.id', 'r.origen', 'cond.id as conductor_id'])->get();
        return json_decode($datos); 
    } catch (\Throwable $th) {
        dd($th);
    }
    // ->map(function($item){
    //     $item->schedule = json_decode($item->schedule, true);
    //     return $item;
    // });   
});

// Rutas para establecer password web 
Route::get('/set-password', [SetPasswordController::class, 'showForm'])->name('set-password.form');
Route::post('/set-password', [SetPasswordController::class, 'setPassword'])->name('set-password.store');