<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RideController extends Controller
{
    // POST /api/rides
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|string',
                'route_id' => 'required|integer',
                'origin_address' => 'required|string',
                'destination_address' => 'required|string',
                'destination_latitude' => 'required|string',
                'destination_longitude' => 'required|string',
                'stop_number' => 'required|integer',
                'fare_paid' => 'required|string',
                'payment_status' => 'required|in:paid,pending,failed',
                'ride_time' => 'required|string',
                'status' => 'required|in:completed,cancelled,in_progress'
            ]);

            Log::info('Guardando nuevo viaje:', $validated);

            $rideId = DB::table('rides')->insertGetId([
                'user_id' => $validated['user_id'],
                'route_id' => $validated['route_id'],
                'origin_address' => $validated['origin_address'],
                'destination_address' => $validated['destination_address'],
                'destination_latitude' => $validated['destination_latitude'],
                'destination_longitude' => $validated['destination_longitude'],
                'stop_number' => $validated['stop_number'],
                'fare_paid' => $validated['fare_paid'],
                'payment_status' => $validated['payment_status'],
                'ride_time' => $validated['ride_time'],
                'status' => $validated['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $savedRide = DB::table('rides')->where('id', $rideId)->first();

            Log::info("Viaje guardado exitosamente con ID: {$rideId}");

            return response()->json([
                'success' => true,
                'message' => 'Viaje guardado correctamente',
                'ride' => $savedRide
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', $e->errors());
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error guardando viaje: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // GET /api/rides/{userId}
    public function getUserRides($userId)
    {
        try {
            $rides = DB::table('rides')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info("Recuperando viajes para usuario {$userId}: " . count($rides) . " encontrados");

            return response()->json($rides);

        } catch (\Exception $e) {
            Log::error("Error obteniendo viajes del usuario {$userId}: " . $e->getMessage());
            return response()->json([
                'error' => 'Error obteniendo viajes',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}||