<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        if ($validator->fails()) {
            return redirect('/register')->withErrors($validator)->withInput();
        }

        $user = User::create($validator->validated());

        return redirect('/login')->with('success', "Cuenta registrada exitosamente.");
    }
}
