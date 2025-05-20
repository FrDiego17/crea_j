<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{


    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/home')->with('error', 'No tienes permisos para acceder.');
        }else{
            return view('admin-index');
        }
        
    }

    public function usuarios()
    {
        return view('admin-usuarios');
    }

    public function rutas()
    {
        return view('/admin-rutas');
    }

    public function conductores()
    {
        return view('admin-conductores');
    }
}
