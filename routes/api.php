<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClerkAuthController;


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