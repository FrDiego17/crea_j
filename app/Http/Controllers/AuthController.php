<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ClerkService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $clerkService;

    public function __construct(ClerkService $clerkService)
    {
        $this->clerkService = $clerkService;
    }


    public function showAuthForms()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
        
        return view('auth.login_regis');
    }

 
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|min:3|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
        ], [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.min' => 'El nombre de usuario debe tener al menos 3 caracteres.',
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El formato del email no es válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos: una mayúscula, una minúscula, un número y un carácter especial (@$!%*?&).',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_register_errors', true);
        }

        DB::beginTransaction();
        
        try {
            //Crea usuario en Clerk primero
            $clerkResponse = $this->clerkService->createUser([
                'email' => $request->email,
                'password' => $request->password,
                'username' => $request->username,
                'first_name' => $request->username,
                'last_name' => '',
            ]);

            // Si Clerk falla, no continuar con el registro
            if (!$clerkResponse['success']) {
                throw new \Exception($clerkResponse['error']);
            }

            // Solo si en Clerk fue exitoso, crea en Laravel
            $user = User::create([
                'name' => $request->username,
                'username' => $request->username,  
                'first_name' => $request->username,
                'last_name' => '',
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasajero',
                'clerk_id' => $clerkResponse['data']['id'], 
                'web_password_set' => true, 
            ]);

            DB::commit();

            return back()->with('success', 
                'Usuario registrado exitosamente. Puedes iniciar sesión ahora.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()
                ->withErrors(['register_error' => 'Error al registrar usuario: ' . $e->getMessage()])
                ->withInput()
                ->with('show_register_errors', true);
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|email',
            'password' => 'required',
        ], [
            'username.required' => 'El email es obligatorio.',
            'username.email' => 'Debe ser un email válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_login_errors', true);
        }

        $email = $request->username; 
        $password = $request->password;

        // Buscar usuario por email
        $user = User::where('email', $email)->first();

        \Log::info('Login attempt:', [
            'email' => $email,
            'user_found' => $user ? 'Yes' : 'No',
            'user_id' => $user ? $user->id : 'N/A',
            'has_clerk_id' => $user && $user->clerk_id ? 'Yes' : 'No',
            'web_password_set' => $user && $user->web_password_set ? 'Yes' : 'No',
            'password_is_null' => $user && is_null($user->password) ? 'Yes' : 'No',
            'user_role' => $user ? $user->role : 'N/A',
        ]);

        if (!$user) {
            return back()
                ->withErrors(['login_error' => 'Email no encontrado'])
                ->withInput()
                ->with('show_login_errors', true);
        }

        // Caso especial: Usuario de Clerk sin contraseña web establecida
        if ($user->clerk_id && is_null($user->password)) {
            return back()
                ->withErrors([
                    'clerk_user' => 'Esta cuenta fue creada desde la app móvil.',
                    'show_password_setup' => true,
                    'user_email' => $user->email
                ])
                ->withInput()
                ->with('show_login_errors', true);
        }

        // Intentar autenticación
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            $welcomeMessage = 'Bienvenido, ' . $user->name;
            
            switch ($user->role) {
                case 'admin':
                    return redirect('/admin')->with('success', $welcomeMessage);
                case 'conductor':
                    return redirect('/home')->with('success', $welcomeMessage);
                case 'pasajero':
                default:
                    return redirect('/home')->with('success', $welcomeMessage);
            }
        }

        // Si llegamos aquí, las credenciales son incorrectas
        return back()
            ->withErrors(['login_error' => 'Las credenciales no coinciden con nuestros registros'])
            ->withInput()
            ->with('show_login_errors', true);
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Sesión cerrada exitosamente.');
    }


    public function syncClerkUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clerk_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'clerk_id es requerido'], 400);
        }

        try {
            $existingUser = User::where('clerk_id', $request->clerk_id)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario ya sincronizado',
                    'user' => $existingUser
                ]);
            }

            $clerkResponse = $this->clerkService->getUser($request->clerk_id);
            
            if (!$clerkResponse['success']) {
                return response()->json(['error' => 'Usuario no encontrado en Clerk'], 404);
            }

            $clerkUser = $clerkResponse['data'];
            
            $user = User::create([
                'name' => $clerkUser['first_name'] . ' ' . $clerkUser['last_name'],
                'username' => $clerkUser['username'] ?? explode('@', $clerkUser['email_addresses'][0]['email_address'])[0],
                'first_name' => $clerkUser['first_name'] ?? '',
                'last_name' => $clerkUser['last_name'] ?? '',
                'email' => $clerkUser['email_addresses'][0]['email_address'],
                'password' => null, 
                'role' => 'pasajero',
                'clerk_id' => $request->clerk_id,
                'web_password_set' => false, 
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario sincronizado exitosamente',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}