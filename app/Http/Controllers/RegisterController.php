<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function show(){
        if(Auth::check()){
            return redirect('/home');
        }
        return view('auth.login_regis');
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        // Verifica si ya existe un usuario con ese email que vino de Clerk ;(
        $existingClerkUser = User::where('email', $request->email)
            ->whereNotNull('clerk_id')
            ->first();

        if ($existingClerkUser) {
            return redirect('/register')->withErrors([
                'email' => 'Este email ya está registrado desde la app móvil. Inicia sesión desde la app o contacta soporte.'
            ])->withInput();
        }
    
        if ($validator->fails()) {
            return redirect('/register')->withErrors($validator)->withInput();
        }
    
        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        
        
        $data['email_verified_at'] = now(); 
        $data['name'] = $data['username']; 
    
        User::create($data);
    
        return redirect('/login')->with('success', "Cuenta registrada exitosamente.");
    }
}