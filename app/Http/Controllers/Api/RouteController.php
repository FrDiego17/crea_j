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
                    'r.route_data'
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
                    'stops' => [],
                    'horarios' => "Sin horarios definidos" 
                ];

                // Obtiene horarios reales de route_schedules
                $horariosDB = DB::table('route_schedules')
                    ->where('route_id', $ruta->id)
                    ->where('is_active', 1) // Solo horarios activos
                    ->orderBy('day_of_week', 'ASC')
                    ->orderBy('departure_time', 'ASC')
                    ->get();

                Log::info("Horarios encontrados para ruta {$ruta->id}: " . count($horariosDB));

                // Construir string de horarios
                if (count($horariosDB) > 0) {
                    $horariosArray = [];
                    
                    // Mapear números de día a nombres
                    $diasSemana = [
                        1 => 'Lunes',
                        2 => 'Martes', 
                        3 => 'Miércoles',
                        4 => 'Jueves',
                        5 => 'Viernes',
                        6 => 'Sábado',
                        7 => 'Domingo'
                    ];

                    foreach ($horariosDB as $horario) {
                        $nombreDia = $diasSemana[$horario->day_of_week] ?? "Día {$horario->day_of_week}";
                        
                        // Formatear hora 
                        $horaFormateada = substr($horario->departure_time, 0, 5); // HH:MM
                        
                        $horariosArray[] = "{$nombreDia} a las {$horaFormateada}";
                    }
                    
                    $rutaData['horarios'] = implode(', ', $horariosArray);
                    Log::info("Horarios procesados: {$rutaData['horarios']}");
                } else {
                    $rutaData['horarios'] = "No hay horarios programados";
                    Log::warning("No se encontraron horarios para ruta {$ruta->id}");
                }

                // Extraer coordenadas reales de route_data
                $coordenadasInicio = null;
                $coordenadasFinal = null;

                if (!empty($ruta->route_data)) {
                    try {
                        $routeDataJson = json_decode($ruta->route_data, true);
                        Log::info("Route data decodificado para ruta {$ruta->id}");

                        if ($routeDataJson && is_array($routeDataJson)) {
                            if (isset($routeDataJson['geocoded_waypoints']) && is_array($routeDataJson['geocoded_waypoints'])) {
                                $waypoints = $routeDataJson['geocoded_waypoints'];
                                Log::info("Encontrados " . count($waypoints) . " waypoints");
                                
                                if (count($waypoints) >= 2) {
                                    $primerWaypoint = $waypoints[0];
                                    if (isset($primerWaypoint['location']['lat']) && isset($primerWaypoint['location']['lng'])) {
                                        $coordenadasInicio = [
                                            'lat' => $primerWaypoint['location']['lat'],
                                            'lng' => $primerWaypoint['location']['lng']
                                        ];
                                        Log::info("Coordenadas inicio: " . json_encode($coordenadasInicio));
                                    }

                                    $ultimoWaypoint = $waypoints[count($waypoints) - 1];
                                    if (isset($ultimoWaypoint['location']['lat']) && isset($ultimoWaypoint['location']['lng'])) {
                                        $coordenadasFinal = [
                                            'lat' => $ultimoWaypoint['location']['lat'],
                                            'lng' => $ultimoWaypoint['location']['lng']
                                        ];
                                        Log::info("Coordenadas final: " . json_encode($coordenadasFinal));
                                    }
                                }
                            }

                            if (!$coordenadasInicio || !$coordenadasFinal) {
                                if (isset($routeDataJson['routes'][0]['legs']) && is_array($routeDataJson['routes'][0]['legs'])) {
                                    $legs = $routeDataJson['routes'][0]['legs'];
                                    Log::info("Buscando en " . count($legs) . " legs como fallback");
                                    
                                    if (count($legs) > 0) {
                                        $primerLeg = $legs[0];
                                        if (!$coordenadasInicio && isset($primerLeg['start_location']['lat']) && isset($primerLeg['start_location']['lng'])) {
                                            $coordenadasInicio = [
                                                'lat' => $primerLeg['start_location']['lat'],
                                                'lng' => $primerLeg['start_location']['lng']
                                            ];
                                            Log::info("Coordenadas inicio desde legs: " . json_encode($coordenadasInicio));
                                        }

                                        $ultimoLeg = $legs[count($legs) - 1];
                                        if (!$coordenadasFinal && isset($ultimoLeg['end_location']['lat']) && isset($ultimoLeg['end_location']['lng'])) {
                                            $coordenadasFinal = [
                                                'lat' => $ultimoLeg['end_location']['lat'],
                                                'lng' => $ultimoLeg['end_location']['lng']
                                            ];
                                            Log::info("Coordenadas final desde legs: " . json_encode($coordenadasFinal));
                                        }
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Error decodificando route_data para ruta {$ruta->id}: " . $e->getMessage());
                    }
                }

                if (!$coordenadasInicio) {
                    $coordenadasInicio = ['lat' => 13.8078, 'lng' => -89.1819];
                    Log::warning("Usando coordenadas inicio por defecto para ruta {$ruta->id}");
                }
                if (!$coordenadasFinal) {
                    $coordenadasFinal = ['lat' => 13.6929, 'lng' => -89.2182];
                    Log::warning("Usando coordenadas final por defecto para ruta {$ruta->id}");
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

                // Paradas INTERMEDIAS de bus_stops
                $paradasBD = DB::table('bus_stops')
                    ->where('route_id', $ruta->id)
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->orderBy('order', 'ASC')
                    ->get();

                Log::info("Paradas intermedias encontradas para ruta {$ruta->id}: " . count($paradasBD));

                $paradaCount = 2; 
                foreach ($paradasBD as $parada) {
                    $lat = trim($parada->latitude);
                    $lng = trim($parada->longitude);
                    
                    // Validar coordenadas
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

                Log::info("Ruta {$ruta->name} completada: {$totalStops} paradas totales, {$intermediateStops} intermedias");

                $datos[] = $rutaData;
            }

            Log::info('Procesamiento completado. Total rutas: ' . count($datos));
            return response()->json($datos);

        } catch (\Throwable $th) {
            Log::error('Error en API datosRutas: ' . $th->getMessage());
            Log::error('Stack trace: ' . $th->getTraceAsString());
            
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

            // Verificar que la ruta existe
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
            Log::error('Stack trace: ' . $th->getTraceAsString());
            
            return response()->json([
                'error' => 'Error interno al actualizar estado',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}