<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClerkAuthController;
use App\Http\Controllers\Api\RouteController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user', [AuthController::class, 'store']);
    
Route::post('/user/verify', [AuthController::class, 'verify']);

Route::post('/clerk-login', [AuthController::class, 'clerkLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/check-email', [AuthController::class, 'checkEmail']);
Route::post('/check-username', [AuthController::class, 'checkUsername']);
Route::post('/auth/clerk-login', [AuthController::class, 'loginWithClerk']);

Route::post('/clerk/get-role', [ClerkAuthController::class, 'getRole']);



Route::get('/datosRutas', [RouteController::class, 'getDatosRutas']);   
Route::put('/routes/{id}/status', [RouteController::class, 'updateStatus']);


Route::post('/routes/{routeId}/driver-location', [RouteController::class, 'updateDriverLocation']);
Route::get('/routes/{routeId}/driver-location', [RouteController::class, 'getDriverLocation']);
Route::delete('/routes/{routeId}/driver-location', [RouteController::class, 'clearDriverLocation']);



Route::prefix('v1')->group(function () {
    
    // Rutas para la API móvil (Expo App)
    Route::get('routes', [RouteController::class, 'apiIndex'])
        ->name('api.routes.index');
    
    Route::get('routes/{route}', [RouteController::class, 'apiShow'])
        ->name('api.routes.show');
    
    // Rutas adicionales para funcionalidades específicas de la API
    Route::get('routes/{route}/next-departure', function(App\Models\Route $route) {
        return response()->json([
            'success' => true,
            'data' => $route->getNextDeparture()
        ]);
    })->name('api.routes.next-departure');
    
    Route::get('routes/{route}/schedules', function(App\Models\Route $route) {
        return response()->json([
            'success' => true,
            'data' => $route->getSchedulesByDay()
        ]);
    })->name('api.routes.schedules');
    
    Route::get('routes/{route}/stops', function(App\Models\Route $route) {
        $route->load('busStops');
        return response()->json([
            'success' => true,
            'data' => $route->busStops->map(function ($stop) {
                return $stop->toGoogleMapsFormat();
            })
        ]);
    })->name('api.routes.stops');
});