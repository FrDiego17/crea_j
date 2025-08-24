<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function showAuthForms()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
        
        return view('auth.login_regis');
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'Este nombre de usuario ya está en uso',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'email.email' => 'El email debe tener un formato válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_register_errors', true);
        }

        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'clerk_id' => 'web_' . uniqid(), 
                'web_password_set' => true,
            ]);

            return redirect()->back()
                ->with('success', 'Registro exitoso. Ya puedes iniciar sesión.')
                ->with('show_login_form', true);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['register_error' => 'Error al crear la cuenta. Intenta nuevamente.'])
                ->withInput()
                ->with('show_register_errors', true);
        }
    }

 
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|email',
            'password' => 'required',
        ], [
            'username.required' => 'El email es obligatorio',
            'username.email' => 'Debe ser un email válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        $email = $request->username; 
        $password = $request->password;

        $user = User::where('email', $email)->first();

        \Log::info('Login attempt:', [
            'email' => $email,
            'user_found' => $user ? 'Yes' : 'No',
            'user_id' => $user ? $user->id : 'N/A',
            'has_clerk_id' => $user && $user->clerk_id ? 'Yes' : 'No',
            'web_password_set' => $user && $user->web_password_set ? 'Yes' : 'No',
            'password_is_null' => $user && is_null($user->password) ? 'Yes' : 'No',
        ]);

        if (!$user) {
            return redirect()->back()
                ->withErrors(['login_error' => 'Email no encontrado'])
                ->withInput()
                ->with('show_login_errors', true);
        }

        if ($user->clerk_id && is_null($user->password)) {
            return redirect()->back()
                ->withErrors([
                    'clerk_user' => 'Esta cuenta fue creada desde la app móvil.',
                    'show_password_setup' => true,
                    'user_email' => $user->email
                ])
                ->withInput()
                ->with('show_login_errors', true);
        }

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return redirect()->back()
            ->withErrors(['login_error' => 'Las credenciales no coinciden con nuestros registros'])
            ->withInput()
            ->with('show_login_errors', true);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Sesión cerrada correctamente');
    }
}