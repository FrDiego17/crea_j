<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function show(){
        if(Auth::check()){
            return redirect('/home');
        }
        return view('auth.login_regis');
    }

    public function register(registerRequest $request){
        $user = user::create($request->validated());
        return redirect ('/login')->with('Success', ' Account created succesfully')->fails();
    }
}
