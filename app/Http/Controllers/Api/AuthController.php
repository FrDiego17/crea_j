<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validar los datos que vienen de tu app React Native
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email',
                'clerkId' => 'required|string|unique:users,clerk_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear el usuario en tu base de datos SIN password
            // porque la autenticación la maneja Clerk
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'clerk_id' => $request->clerkId,
                'name' => $request->username,
                'password' => null, // Explícitamente null porque usas Clerk
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente en Laravel',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'clerk_id' => $user->clerk_id,
                ]
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error creando usuario desde Clerk: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para autenticar usuarios que vienen de Clerk
    public function loginWithClerk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clerkId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Clerk ID es requerido',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('clerk_id', $request->clerkId)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Usuario autenticado exitosamente',
            'user' => $user
        ], 200);
    }
}