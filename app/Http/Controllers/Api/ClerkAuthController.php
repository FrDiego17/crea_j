<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ClerkAuthController extends Controller
{
    // Endpoint para obtener rol por clerk_id
    public function getRole(Request $request)
    {
        $request->validate([
            'clerk_id' => 'required|string',
        ]);

        $user = User::where('clerk_id', $request->clerk_id)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json([
            'role' => $user->role,
            'username' => $user->username,
            'email' => $user->email,
        ]);
    }
}
