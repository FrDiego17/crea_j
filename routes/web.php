<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('/home.index');
});

Route::get('/register', [RegisterController::class, 'show']);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'show']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/home', [HomeController::class, 'index']);
Route::get('/logout', [LogoutController::class, 'logout']);

Route::get('/admin', function () {
    return view('admin-index');
});

Route::get('/admin-usuarios', function () {
    return view('admin-usuarios');
});

Route::get('/admin-rutas', function () {
    return view('admin-rutas');
});

Route::get('/admin-conductores', function () {
    return view('admin-conductores');
});
