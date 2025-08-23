<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function store(Request $request)
    {
        \Log::info('=== INICIO REQUEST ===');
        \Log::info('Datos recibidos:', $request->all());
        
        try {
            // Valida los datos recibidos de Expo
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'clerkId' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                \Log::error('Validación falló:', $validator->errors()->toArray());
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $validator->errors()
                ], 400);
            }
            
            \Log::info('Validación exitosa');

            // Verificar si ya existe un usuario con este clerk_id
            $existingUser = User::where('clerk_id', $request->clerkId)->first();
            \Log::info('Usuario existente:', ['exists' => $existingUser ? 'SÍ' : 'NO']);

            if ($existingUser) {
                // Si el Usuario ya existe, actualiza los datos...
                $existingUser->update([
                    'username' => $request->username,
                    'email' => $request->email,
                    'email_verified_at' => now(),
                ]);

                return response()->json([
                    'data' => $existingUser,
                    'message' => 'Usuario actualizado exitosamente'
                ], 200);
            }

            // Verificar si ya existe el email (de usuarios web)
            $emailExists = User::where('email', $request->email)->whereNull('clerk_id')->first();
            \Log::info('Email existe:', ['exists' => $emailExists ? 'SÍ' : 'NO']);
            
            if ($emailExists) {
                return response()->json([
                    'error' => 'Email already exists',
                    'message' => 'Este email ya está registrado en la plataforma web'
                ], 400);
            }

            \Log::info('Intentando crear usuario...');
            
            $user = User::create([
                'name' => $request->username,
                'username' => $request->username,
                'email' => $request->email,
                'clerk_id' => $request->clerkId,
                'email_verified_at' => now(),
                'password' => \Str::random(32), 
                'role' => null,
                'id_admin' => null,
            ]);
            
            \Log::info('Usuario creado exitosamente:', ['id' => $user->id]);

            return response()->json([
                'data' => $user,
                'message' => 'Usuario creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            \Log::error('ERROR COMPLETO:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'No se pudo crear el usuario',
                'debug' => $e->getMessage() 
            ], 500);
        }
    }
}