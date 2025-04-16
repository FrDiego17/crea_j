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

    /*public function index(Request $request): View
    {
        $usuarios = User::paginate();

        return view('usuario.index', compact('user'))
            ->with('i' ($request->input('page', 1) - 1) * $usuarios->perPage());
    }

    public function create(): View
    {
        $usuarios = new User();
        return view('usuario.create', compact('user'));
    }

    public function store(UsuarioRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return Redirect::route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function showA($id): View
    {
        $usuarios = User::findOrFail($id);

        return view('usuarios.show', compact('user'));
    }

    public function edit($id): View 
    {
        $usuarios = User::findOrdail($id);

        return view('usuarios.edit', compact('user'));
    }

    public function update(UsuarioRequest $request, User $usuarios): RedirectResponse
    {
        $data = $request->validated();

        // Solo actualizar la contraseña si se proporcionó
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $usuario->update($data);

        return Redirect::route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    
    public function destroy($id): RedirectResponse
    {
        User::findOrFail($id)->delete();

        return Redirect::route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }*/
}