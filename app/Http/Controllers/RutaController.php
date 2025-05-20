<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\RutaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;  
use Illuminate\Support\Facades\Auth;

class RutaController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'No tienes permisos para acceder.');
        }

        $rutas = Ruta::paginate();

        return view('ruta.index', compact('rutas'))
            ->with('i', ($request->input('page', 1) - 1) * $rutas->perPage());
    }

    public function create(): View
    {
        $ruta = new Ruta();
        return view('ruta.create', compact('ruta'));
    }

    public function store(RutaRequest $request): RedirectResponse
    {
        Ruta::create($request->validated());
        return Redirect::route('rutas.index')->with('success', 'Ruta creada correctamente.');
    }

    public function show($id): View
    {
        $ruta = Ruta::find($id);
        return view('ruta.show', compact('ruta'));
    }

    public function edit($id): View
    {
        $ruta = Ruta::find($id);
        return view('ruta.edit', compact('ruta'));
    }

    public function update(RutaRequest $request, Ruta $ruta): RedirectResponse
    {
        $ruta->update($request->validated());
        return Redirect::route('rutas.index')->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Ruta::find($id)->delete();
        return Redirect::route('rutas.index')->with('success', 'Ruta eliminada correctamente.');
    }
}
