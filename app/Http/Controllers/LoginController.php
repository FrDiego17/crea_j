<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class LoginController extends Controller
{
    public function show(){
        if(Auth::check()){
            return redirect('/home');
        }
        return view('auth.login_regis');
    }

    public function login(LoginRequest $request){
        $credentials = $request->getCredentials();
        
        $user = User::where('email', $credentials['email'] ?? $credentials['username'])->first();

        if (!$user) {
            return redirect()->to('/login')->withErrors('Email no encontrado');
        }

        if (Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            return $this->authenticated($request, $user);
        }

        // Si la autenticación falló Y es usuario de Clerk Y nunca ha establecido password web
        if ($user->clerk_id && !$user->web_password_set) {
            return redirect()->to('/login')->withErrors([
                'clerk_user' => 'Esta cuenta fue creada desde la app móvil.',
                'show_password_setup' => true,
                'user_email' => $user->email
            ]);
        }

        return redirect()->to('/login')->withErrors('Credenciales incorrectas');
    }

    public function authenticated(Request $request, $user){
        return redirect('/home');
    }


}