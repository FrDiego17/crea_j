<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SetPasswordController extends Controller
{

    public function showForm(Request $request)
    {
        $email = $request->get('email');
        
        if (!$email) {
            return redirect('/login')->withErrors('Email requerido');
        }

        $user = User::where('email', $email)->whereNotNull('clerk_id')->first();
        
        if (!$user) {
            return redirect('/login')->withErrors('Usuario no encontrado');
        }

        return view('auth.set-password', compact('email'));
    }

    public function setPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)
                   ->whereNotNull('clerk_id')
                   ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado']);
        }

        // Actualizar password directamente y marcar como establecido
        $user->update([
            'password' => Hash::make($request->password),
            'web_password_set' => true
        ]);

        return redirect('/login')->with('success', 'Password establecido exitosamente. Ya puedes iniciar sesión con tu email y nueva contraseña.');
    }
}