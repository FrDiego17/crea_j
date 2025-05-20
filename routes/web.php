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
    Route::get('/apopa', [HomeController::class, 'apopa']);
    Route::get('/cojute', [HomeController::class, 'cojute']);
    Route::get('/ilobasco', [HomeController::class, 'ilobasco']);
    Route::get('/lucia', [HomeController::class, 'lucia']);
    Route::get('/quezalte', [HomeController::class, 'quezalte']);
    Route::get('/sanmartin', [HomeController::class, 'sanmartin']);
});

<<<<<<< Updated upstream
Route::get('/admin-usuarios', function () {
    return view('admin-usuarios');
});

Route::get('/admin-rutas', function () {
    return view('admin-rutas');
});

Route::get('/admin-conductores', function () {
    return view('admin-conductores');
});

Route::get('/horario', [HomeController::class, 'horario']);
Route::get('/usuario', [HomeController::class, 'perfil']);
Route::get('/apopa', [HomeController::class, 'apopa']);
Route::get('/cojute', [HomeController::class, 'cojute']);
Route::get('/ilobasco', [HomeController::class, 'ilobasco']);
Route::get('/lucia', [HomeController::class, 'lucia']);
Route::get('/quezalte', [HomeController::class, 'quezalte']);
Route::get('/sanmartin', [HomeController::class, 'sanmartin']);
Route::post('/recargar', [HomeController::class, 'recargar'])->name('home.recargar');
Route::post('/compartir', [HomeController::class, 'compartir'])->name('home.compartir');
=======
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin-usuarios', [AdminController::class, 'usuarios']);
    Route::get('/admin-rutas', [AdminController::class, 'rutas']);
    Route::get('/admin-conductores', [AdminController::class, 'conductores']);
});
>>>>>>> Stashed changes
