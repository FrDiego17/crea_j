<?php

namespace App\Http\Controllers;

use App\Models\Conductore;
use App\Models\Ruta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ConductoreRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ConductoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $conductores = Conductore::paginate();

        return view('conductore.index', compact('conductores'))
            ->with('i', ($request->input('page', 1) - 1) * $conductores->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $conductore = new Conductore();
        $rutas = Ruta::all();

        return view('conductore.create', compact('conductore'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConductoreRequest $request): RedirectResponse
    {
        Conductore::create([
            'nombre'=>$request->nombre,
            'apellido'=>$request->apellido,
            'email'=>$request->email,
            'dui'=>$request->dui,
            'telefono'=>$request->telefono,
            'licencia'=>$request->licencia,
            'TipoVehiculo'=>$request->TipoVehiculo,
            'rutas_id'=> $request->rutas_id 
        ]);

        return Redirect::route('conductores.index')
            ->with('success', 'Conductore created successfully.');

        
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $conductore = Conductore::find($id);

        return view('conductore.show', compact('conductore'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $conductore = Conductore::find($id);

        return view('conductore.edit', compact('conductore'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConductoreRequest $request, Conductore $conductore): RedirectResponse
    {
        $conductore->update($request->validated());

        return Redirect::route('conductores.index')
            ->with('success', 'Conductore updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Conductore::find($id)->delete();

        return Redirect::route('conductores.index')
            ->with('success', 'Conductore deleted successfully');
    }
}
