<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            // Redirige si no está autenticado
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        if ($user->role !== 'admin') {
            // Si no es admin, aborta con 403
            abort(403, 'Acceso denegado. No tienes permisos de administrador.');
        }

        return $next($request);
    }
}
