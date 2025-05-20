<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Ruta para obtener el usuario autenticado, usando middleware auth:sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas de registro y login
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Rutas de logout
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);


//Rutas de las actualizaciones xd
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/updateUser', [AuthController::class, 'updateUser']);
    Route::put('/updatePassword', [AuthController::class, 'updatePassword']);
    Route::delete('/deleteAccount', [AuthController::class, 'deleteAccount']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->post('/uploadImage', [UserController::class, 'uploadImage']);


