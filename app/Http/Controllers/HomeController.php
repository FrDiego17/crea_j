<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        
        return view('home.index');
    }

    public function horario(){
        return view('home.contactus');
    }

    public function apopa(){
        return view('home.apopa');
    }

    public function cojute(){
        return view('home.cojute');
    }
    public function ilobasco(){
        return view('home.ilobasco');
    }
    public function sanmartin(){
        return view('home.sanmartin');
    }
    public function lucia(){
        return view('home.lucia');
    }
    public function quezalte(){
        return view('home.quezalte');
    }
    
}
