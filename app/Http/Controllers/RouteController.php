<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\BusStop;
use App\Models\RouteSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::with(['busStops', 'schedules'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('routes.index', compact('routes'));
    }

    public function create()
    {
        return view('routes.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'route_data' => 'nullable|json',
            'route_polyline' => 'nullable|string',
            'route_bounds' => 'nullable|json',
            'total_distance' => 'nullable|integer',
            'total_duration' => 'nullable|integer',
            'stops' => 'required|array|min:1',
            'stops.*.name' => 'required|string|max:255',
            'stops.*.latitude' => 'required|numeric|between:-90,90',
            'stops.*.longitude' => 'required|numeric|between:-180,180',
            'stops.*.address' => 'nullable|string',
            'stops.*.order' => 'required|integer|min:0',
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'schedules.*.departure_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Crear la ruta
            $route = Route::create([
                'name' => $request->name,
                'description' => $request->description,
                'start_location' => $request->start_location,
                'end_location' => $request->end_location,
                'price' => $request->price,
                'color' => $request->color,
                'route_data' => $request->route_data ? json_decode($request->route_data, true) : null,
                'route_polyline' => $request->route_polyline,
                'route_bounds' => $request->route_bounds ? json_decode($request->route_bounds, true) : null,
                'total_distance' => $request->total_distance,
                'total_duration' => $request->total_duration,
                'is_active' => true,
            ]);

            // Crear las paradas
            foreach ($request->stops as $stopData) {
                BusStop::create([
                    'route_id' => $route->id,
                    'name' => $stopData['name'],
                    'latitude' => $stopData['latitude'],
                    'longitude' => $stopData['longitude'],
                    'address' => $stopData['address'] ?? null,
                    'order' => $stopData['order'],
                ]);
            }

            // Crear los horarios si existen
            if ($request->schedules) {
                foreach ($request->schedules as $scheduleData) {
                    RouteSchedule::create([
                        'route_id' => $route->id,
                        'day_of_week' => $scheduleData['day_of_week'],
                        'departure_time' => $scheduleData['departure_time'],
                    ]);
                }
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruta creada exitosamente',
                    'route' => $route->load(['busStops', 'schedules'])
                ]);
            }

            return redirect()->route('routes.show', $route)
                ->with('success', 'Ruta creada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al crear ruta: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la ruta: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->withErrors(['error' => 'Error al crear la ruta: ' . $e->getMessage()]);
        }
    }

    public function show(Route $route)
    {
        $route->load(['busStops', 'schedules']);
        
        return view('routes.show', compact('route'));
    }

    public function edit(Route $route)
    {
        $route->load(['busStops', 'schedules']);
        
        return view('routes.edit', compact('route'));
    }

    public function update(Request $request, Route $route)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'is_active' => 'nullable|boolean',
            'route_data' => 'nullable|json',
            'route_polyline' => 'nullable|string',
            'route_bounds' => 'nullable|json',
            'total_distance' => 'nullable|integer',
            'total_duration' => 'nullable|integer',
            'stops' => 'required|array|min:1',
            'stops.*.name' => 'required|string|max:255',
            'stops.*.latitude' => 'required|numeric|between:-90,90',
            'stops.*.longitude' => 'required|numeric|between:-180,180',
            'stops.*.address' => 'nullable|string',
            'stops.*.order' => 'required|integer|min:0',
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'schedules.*.departure_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Actualizar la ruta
            $route->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_location' => $request->start_location,
                'end_location' => $request->end_location,
                'price' => $request->price,
                'color' => $request->color,
                'is_active' => $request->has('is_active') ? (bool)$request->is_active : $route->is_active,
                'route_data' => $request->route_data ? json_decode($request->route_data, true) : null,
                'route_polyline' => $request->route_polyline,
                'route_bounds' => $request->route_bounds ? json_decode($request->route_bounds, true) : null,
                'total_distance' => $request->total_distance,
                'total_duration' => $request->total_duration,
            ]);

            // Eliminar paradas existentes y crear nuevas
            $route->busStops()->delete();
            foreach ($request->stops as $stopData) {
                BusStop::create([
                    'route_id' => $route->id,
                    'name' => $stopData['name'],
                    'latitude' => $stopData['latitude'],
                    'longitude' => $stopData['longitude'],
                    'address' => $stopData['address'] ?? null,
                    'order' => $stopData['order'],
                ]);
            }

            // Eliminar horarios existentes y crear nuevos
            $route->schedules()->delete();
            if ($request->schedules) {
                foreach ($request->schedules as $scheduleData) {
                    RouteSchedule::create([
                        'route_id' => $route->id,
                        'day_of_week' => $scheduleData['day_of_week'],
                        'departure_time' => $scheduleData['departure_time'],
                    ]);
                }
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruta actualizada exitosamente',
                    'route' => $route->load(['busStops', 'schedules'])
                ]);
            }

            return redirect()->route('routes.show', $route)
                ->with('success', 'Ruta actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al actualizar ruta ID ' . $route->id . ': ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la ruta: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->withErrors(['error' => 'Error al actualizar la ruta: ' . $e->getMessage()]);
        }
    }

    public function destroy(Route $route)
    {
        DB::beginTransaction();
        
        try {
            // Verificar si la ruta tiene buses asignados (si tienes esa relación)
            // if ($route->buses()->count() > 0) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'No se puede eliminar la ruta porque tiene buses asignados'
            //     ], 400);
            // }

            // Eliminar las paradas asociadas
            $route->busStops()->delete();
            
            // Eliminar los horarios asociados
            $route->schedules()->delete();
            
            // Eliminar la ruta
            $route->delete();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ruta eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al eliminar ruta ID ' . $route->id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la ruta: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para cambiar el estado activo/inactivo
    public function toggleStatus(Route $route)
    {
        try {
            // Calcular el nuevo estado
            $newStatus = !$route->is_active;
            
            // Actualizar directamente en la base de datos
            $updated = $route->update(['is_active' => $newStatus]);
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo actualizar el estado de la ruta'
                ], 500);
            }
            
            return response()->json([
                'success' => true,
                'message' => $newStatus ? 'Ruta activada exitosamente' : 'Ruta desactivada exitosamente',
                'is_active' => $newStatus
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de ruta ID ' . $route->id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la ruta: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener todas las rutas para la API móvil
    public function apiIndex()
    {
        $routes = Route::with(['busStops', 'schedules'])
            ->where('is_active', true)
            ->get()
            ->map(function ($route) {
                return [
                    'id' => $route->id,
                    'name' => $route->name,
                    'description' => $route->description,
                    'start_location' => $route->start_location,
                    'end_location' => $route->end_location,
                    'price' => $route->price,
                    'color' => $route->color,
                    'route_polyline' => $route->route_polyline,
                    'route_bounds' => $route->route_bounds,
                    'stops' => $route->busStops->map(function ($stop) {
                        return [
                            'id' => $stop->id,
                            'name' => $stop->name,
                            'latitude' => $stop->latitude,
                            'longitude' => $stop->longitude,
                            'address' => $stop->address,
                            'order' => $stop->order
                        ];
                    }),
                    'schedules' => $route->schedules->map(function ($schedule) {
                        return [
                            'id' => $schedule->id,
                            'day_of_week' => $schedule->day_of_week,
                            'departure_time' => $schedule->departure_time->format('H:i')
                        ];
                    })
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $routes
        ]);
    }

    // Método para obtener una ruta específica para la API móvil
    public function apiShow(Route $route)
    {
        $route->load(['busStops', 'schedules']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $route->id,
                'name' => $route->name,
                'description' => $route->description,
                'start_location' => $route->start_location,
                'end_location' => $route->end_location,
                'price' => $route->price,
                'color' => $route->color,
                'route_polyline' => $route->route_polyline,
                'route_bounds' => $route->route_bounds,
                'stops' => $route->busStops->map(function ($stop) {
                    return [
                        'id' => $stop->id,
                        'name' => $stop->name,
                        'latitude' => $stop->latitude,
                        'longitude' => $stop->longitude,
                        'address' => $stop->address,
                        'order' => $stop->order
                    ];
                }),
                'schedules' => $route->schedules->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'day_of_week' => $schedule->day_of_week,
                        'departure_time' => $schedule->departure_time->format('H:i')
                    ];
                }),
                'is_active' => $route->is_active
            ]
        ]);
    }
}