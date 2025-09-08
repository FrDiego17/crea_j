<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    // GET /api/datosRutas
    public function getDatosRutas()
    {
        try {
            Log::info('Cargando rutas con coordenadas reales y horarios de BD...');
            
            $rutas = DB::table('routes as r')
                ->select([
                    'r.id as id', 
                    'r.name',
                    'r.description',
                    'r.start_location',
                    'r.end_location',
                    'r.color',
                    'r.is_active',
                    'r.route_data',
                    'r.price',
                    'r.ubiDriver'  // AGREGADO: incluir ubicación del conductor
                ])
                ->get();

            $datos = [];

            foreach ($rutas as $ruta) {
                Log::info("Procesando ruta: {$ruta->name}");
                
                $rutaData = [
                    'id' => $ruta->id,
                    'name' => $ruta->name,
                    'description' => $ruta->description,
                    'start_location' => $ruta->start_location,
                    'end_location' => $ruta->end_location,
                    'color' => $ruta->color,
                    'is_active' => $ruta->is_active,
                    'price' => $ruta->price,
                    'ubiDriver' => $ruta->ubiDriver, // AGREGADO: incluir en respuesta
                    'stops' => [],
                    'horarios' => "Sin horarios definidos" 
                ];

                // ... resto de tu código existente para horarios y paradas ...
                // (mantén todo el código que ya tienes)

                // Obtiene horarios reales de route_schedules
                $horariosDB = DB::table('route_schedules')
                    ->where('route_id', $ruta->id)
                    ->where('is_active', 1)
                    ->orderBy('day_of_week', 'ASC')
                    ->orderBy('departure_time', 'ASC')
                    ->get();

                Log::info("Horarios encontrados para ruta {$ruta->id}: " . count($horariosDB));

                // Construir string de horarios
                if (count($horariosDB) > 0) {
                    $horariosArray = [];
                    
                    $diasSemana = [
                        1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
                        4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'
                    ];

                    foreach ($horariosDB as $horario) {
                        $nombreDia = $diasSemana[$horario->day_of_week] ?? "Día {$horario->day_of_week}";
                        $horaFormateada = substr($horario->departure_time, 0, 5);
                        $horariosArray[] = "{$nombreDia} a las {$horaFormateada}";
                    }
                    
                    $rutaData['horarios'] = implode(', ', $horariosArray);
                } else {
                    $rutaData['horarios'] = "No hay horarios programados";
                }

                // Extraer coordenadas reales de route_data
                $coordenadasInicio = null;
                $coordenadasFinal = null;

                if (!empty($ruta->route_data)) {
                    try {
                        $routeDataJson = json_decode($ruta->route_data, true);
                        // ... tu código existente para procesar coordenadas ...
                    } catch (\Exception $e) {
                        Log::error("Error decodificando route_data para ruta {$ruta->id}: " . $e->getMessage());
                    }
                }

                if (!$coordenadasInicio) {
                    $coordenadasInicio = ['lat' => 13.8078, 'lng' => -89.1819];
                }
                if (!$coordenadasFinal) {
                    $coordenadasFinal = ['lat' => 13.6929, 'lng' => -89.2182];
                }

                // Construir paradas - INICIO
                $rutaData['stops'][] = [
                    "coordenadas" => [
                        "latitude" => (string)$coordenadasInicio['lat'],
                        "longitude" => (string)$coordenadasInicio['lng']
                    ],
                    "order" => 1,
                    "name" => $ruta->start_location ?: "Punto de Inicio",
                    "display_name" => "INICIO: " . ($ruta->start_location ?: "Punto de Inicio"),
                    "address" => "Punto de partida de la ruta",
                    "is_start" => true,
                    "is_end" => false,
                    "type" => "start",
                    "position_in_route" => 1,
                    "intermediate_stops" => false
                ];

                // Paradas INTERMEDIAS
                $paradasBD = DB::table('bus_stops')
                    ->where('route_id', $ruta->id)
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->orderBy('order', 'ASC')
                    ->get();

                $paradaCount = 2; 
                foreach ($paradasBD as $parada) {
                    $lat = trim($parada->latitude);
                    $lng = trim($parada->longitude);
                    
                    if (!empty($lat) && !empty($lng) && 
                        is_numeric($lat) && is_numeric($lng) &&
                        $lat >= -90 && $lat <= 90 && 
                        $lng >= -180 && $lng <= 180) {
                        
                        $rutaData['stops'][] = [
                            "coordenadas" => [
                                "latitude" => $lat,
                                "longitude" => $lng
                            ],
                            "order" => $paradaCount,
                            "name" => $parada->name ?: "Parada {$paradaCount}",
                            "display_name" => $parada->name ?: "Parada {$paradaCount}",
                            "address" => $parada->address ?: "Dirección no especificada",
                            "is_start" => false,
                            "is_end" => false,
                            "type" => "stop",
                            "position_in_route" => $paradaCount,
                            "intermediate_stops" => true
                        ];
                        $paradaCount++;
                    }
                }

                // Parada FINAL
                $rutaData['stops'][] = [
                    "coordenadas" => [
                        "latitude" => (string)$coordenadasFinal['lat'],
                        "longitude" => (string)$coordenadasFinal['lng']
                    ],
                    "order" => $paradaCount,
                    "name" => $ruta->end_location ?: "Punto Final",
                    "display_name" => "FINAL: " . ($ruta->end_location ?: "Punto Final"),
                    "address" => "Destino final de la ruta",
                    "is_start" => false,
                    "is_end" => true,
                    "type" => "end",
                    "position_in_route" => $paradaCount,
                    "intermediate_stops" => false
                ];

                // Estadísticas
                $totalStops = count($rutaData['stops']);
                $intermediateStops = count($paradasBD);

                $rutaData['stops_summary'] = [
                    'total_stops' => $totalStops,
                    'intermediate_stops' => $intermediateStops,
                    'has_start' => true,
                    'has_end' => true,
                    'description' => "Inicio + {$intermediateStops} paradas + Final = {$totalStops} total"
                ];

                $datos[] = $rutaData;
            }

            return response()->json($datos);

        } catch (\Throwable $th) {
            Log::error('Error en API datosRutas: ' . $th->getMessage());
            return response()->json([
                'error' => 'Error al obtener datos de rutas',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // PUT /api/routes/{id}/status
    public function updateStatus(Request $request, $id)
    {
        try {
            $isActive = $request->input('is_active');
            
            Log::info("Actualizando estado de ruta {$id} a {$isActive}");
            
            if (!in_array($isActive, [0, 1], true)) {
                return response()->json(['error' => 'Valor inválido para is_active. Debe ser 0 o 1'], 400);
            }

            $ruta = DB::table('routes')->where('id', $id)->first();
            if (!$ruta) {
                Log::error("Ruta {$id} no encontrada");
                return response()->json(['error' => 'Ruta no encontrada'], 404);
            }

            $updated = DB::table('routes')
                ->where('id', $id)
                ->update([
                    'is_active' => $isActive,
                    'updated_at' => now()
                ]);

            if ($updated) {
                Log::info("Ruta {$id} actualizada exitosamente a estado {$isActive}");
                
                return response()->json([
                    'success' => true,
                    'message' => 'Estado de ruta actualizado correctamente',
                    'route_id' => (int)$id,
                    'is_active' => (int)$isActive,
                    'route_name' => $ruta->name
                ]);
            } else {
                Log::error("No se pudo actualizar la ruta {$id}");
                return response()->json(['error' => 'No se pudo actualizar el estado'], 500);
            }

        } catch (\Throwable $th) {
            Log::error('Error actualizando estado de ruta: ' . $th->getMessage());
            return response()->json([
                'error' => 'Error interno al actualizar estado',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // ubicacion del conductor

    // POST /api/routes/{routeId}/driver-location
    public function updateDriverLocation(Request $request, $routeId)
    {
        try {
            $validated = $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'speed' => 'nullable|numeric|min:0',
                'heading' => 'nullable|numeric|between:0,360',
                'current_stop_index' => 'nullable|integer|min:0'
            ]);

            // Verificar que la ruta existe
            $route = DB::table('routes')->where('id', $routeId)->first();
            if (!$route) {
                return response()->json(['error' => 'Route not found'], 404);
            }

            // Crear objeto de ubicación con timestamp
            $locationData = [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'speed' => $validated['speed'] ?? 0,
                'heading' => $validated['heading'] ?? 0,
                'current_stop_index' => $validated['current_stop_index'] ?? 0,
                'last_update' => now()->toISOString()
            ];

            // Actualizar la columna ubiDriver con JSON
            $updated = DB::table('routes')
                ->where('id', $routeId)
                ->update([
                    'ubiDriver' => json_encode($locationData),
                    'updated_at' => now()
                ]);

            if ($updated) {
                Log::info("Driver location updated for route {$routeId}");
                
                return response()->json([
                    'success' => true,
                    'message' => 'Driver location updated successfully',
                    'route_id' => (int)$routeId,
                    'location' => $locationData
                ]);
            } else {
                return response()->json(['error' => 'Failed to update location'], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating driver location: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update location',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // GET /api/routes/{routeId}/driver-location
    public function getDriverLocation($routeId)
    {
        try {
            $route = DB::table('routes')
                ->select('id', 'name', 'color', 'ubiDriver', 'updated_at')
                ->where('id', $routeId)
                ->first();

            if (!$route) {
                return response()->json(['error' => 'Route not found'], 404);
            }

            // Verificar si hay ubicación del conductor
            if (!$route->ubiDriver) {
                return response()->json([
                    'driver_active' => false,
                    'message' => 'No driver location available',
                    'route' => [
                        'id' => $route->id,
                        'name' => $route->name,
                        'color' => $route->color
                    ]
                ]);
            }

            // Decodificar ubicación JSON
            $locationData = json_decode($route->ubiDriver, true);
            
            if (!$locationData) {
                return response()->json([
                    'driver_active' => false,
                    'message' => 'Invalid driver location data'
                ]);
            }

            // Verificar si la ubicación es reciente (menos de 5 minutos)
            $lastUpdate = \Carbon\Carbon::parse($locationData['last_update']);
            $minutesAgo = $lastUpdate->diffInMinutes(now());
            $isActive = $minutesAgo < 5;

            return response()->json([
                'driver_active' => $isActive,
                'location' => [
                    'latitude' => (float) $locationData['latitude'],
                    'longitude' => (float) $locationData['longitude'],
                    'speed' => (float) ($locationData['speed'] ?? 0),
                    'heading' => (float) ($locationData['heading'] ?? 0),
                    'current_stop_index' => (int) ($locationData['current_stop_index'] ?? 0),
                    'last_update' => $locationData['last_update'],
                    'minutes_ago' => $minutesAgo
                ],
                'route' => [
                    'id' => $route->id,
                    'name' => $route->name,
                    'color' => $route->color
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error getting driver location for route {$routeId}: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get driver location',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /api/routes/{routeId}/driver-location
    public function clearDriverLocation($routeId)
    {
        try {
            $updated = DB::table('routes')
                ->where('id', $routeId)
                ->update([
                    'ubiDriver' => null,
                    'updated_at' => now()
                ]);

            if ($updated) {
                Log::info("Driver location cleared for route {$routeId}");
                return response()->json([
                    'success' => true,
                    'message' => 'Driver location cleared successfully'
                ]);
            } else {
                return response()->json(['error' => 'Route not found'], 404);
            }

        } catch (\Exception $e) {
            Log::error("Error clearing driver location for route {$routeId}: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to clear location',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}