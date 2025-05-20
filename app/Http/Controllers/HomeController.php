<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

    public function perfil(){
        return view('home.usuario', ['user' => auth()->user()]);
    }

    public function recargar(Request $request)
    {
        $request->validate([
            'monto' => 'required|numeric|min:1'
        ]);

        $user = Auth::user();
        $user->increment('card_balance', $request->monto);

        return redirect()->back()->with('toast_success', 'Recarga exitosa')->with('nuevo_saldo', $user->card_balance);
    }

    public function compartir(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'monto' => 'required|numeric|min:1'
    ]);

    $emisor = Auth::user();
    $receptor = User::where('email', $request->email)->first();

    if ($emisor->id === $receptor->id) {
        return redirect()->back()->with('toast_error', 'No puedes enviarte dinero a ti mismo.');
    }

    if ($emisor->card_balance < $request->monto) {
        return redirect()->back()->with('toast_error', 'Saldo insuficiente.');
    }

    $emisor->decrement('card_balance', $request->monto);
    $receptor->increment('card_balance', $request->monto);

    return redirect()->back()->with('toast_success', 'Transferencia realizada con éxito')->with('nuevo_saldo', $emisor->card_balance);
}

}
