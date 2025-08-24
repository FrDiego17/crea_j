<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class SetPasswordController extends Controller
{
    public function show(Request $request)
    {
        $email = $request->get('email');
        
        if (!$email) {
            return redirect('/login')->withErrors('Email requerido');
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect('/login')->withErrors('Usuario no encontrado');
        }

        // Verificar que realmente es un usuario de Clerk sin password web
        if (!$user->clerk_id || $user->web_password_set) {
            return redirect('/login')->withErrors('Esta cuenta no requiere configuración de contraseña');
        }

        return view('auth.set-password', compact('email'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'email.required' => 'El email es requerido',
            'email.exists' => 'Usuario no encontrado',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Verificar nuevamente que es usuario de Clerk
        if (!$user->clerk_id || $user->web_password_set) {
            return response()->json([
                'success' => false,
                'message' => 'Esta cuenta no puede establecer contraseña web'
            ], 400);
        }

        // Establecer la contraseña y marcar como configurada
        $user->update([
            'password' => Hash::make($request->password),
            'web_password_set' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contraseña establecida correctamente. Ya puedes iniciar sesión.'
        ]);
    }
}