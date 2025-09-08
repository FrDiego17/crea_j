<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ruta;
use App\Models\Conductore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ConductoreRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;  
use Illuminate\Support\Facades\Auth;

class ConductoreController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'No tienes permisos para acceder.');
        }
                
        $conductores = Conductore::paginate();

        return view('conductore.index', compact('conductores'))
            ->with('i', ($request->input('page', 1) - 1) * $conductores->perPage());
    }

    public function create(): View
    {
        $conductore = new Conductore();
        $rutas = Ruta::all();

        return view('conductore.create', compact('conductore', 'rutas'));
    }

    public function store(ConductoreRequest $request): RedirectResponse
    {
        Conductore::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'dui' => $request->dui,
            'telefono' => $request->telefono,
            'licencia' => $request->licencia,
            'TipoVehiculo' => $request->TipoVehiculo,
            'rutas_id' => $request->rutas_id,
        ]);

        return Redirect::route('conductores.index')
            ->with('success', 'Conductor creado correctamente.');
    }

    public function show($id): View
    {
        $conductore = Conductore::find($id);
        return view('conductore.show', compact('conductore'));
    }

    public function edit($id): View
    {
        $conductore = Conductore::find($id);
        $rutas = Ruta::all();
        return view('conductore.edit', compact('conductore', 'rutas'));
    }

    public function update(ConductoreRequest $request, Conductore $conductore): RedirectResponse
    {
        $conductore->update($request->validated());

        return Redirect::route('conductores.index')
            ->with('success', 'Conductor actualizado correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Conductore::find($id)->delete();

        return Redirect::route('conductores.index')
            ->with('success', 'Conductor eliminado correctamente.');
    }
}
