@extends('layouts.app')

@section('title', 'Ver Ruta - ' . $route->name)

@php
    $pageTitle = $route->name;
    $pageDescription = $route->description ?: 'Detalles completos de la ruta';
    $breadcrumbs = [
        ['title' => 'Inicio', 'url' => route('routes.index')],
        ['title' => 'Rutas', 'url' => route('routes.index')],
        ['title' => $route->name, 'url' => '']
    ];

    // ⚙️ Preparamos el payload para @json para evitar errores de parseo de corchetes/llaves
    $stopsPayload = $route->busStops->map(function ($stop) {
        return [
            'id' => $stop->id,
            'name' => $stop->name,
            'lat' => (float) $stop->latitude,
            'lng' => (float) $stop->longitude,
            'address' => $stop->address,
            'order' => $stop->order,
        ];
    })->values()->toArray();

    $routePayload = [
        'id' => $route->id,
        'name' => $route->name,
        'color' => $route->color,
        'route_data' => $route->route_data, // puede ser array u objeto (ideal) o JSON string
        'route_polyline' => $route->route_polyline,
        'route_bounds' => $route->route_bounds,
        'stops' => $stopsPayload,
        'is_active' => (bool) $route->is_active,
    ];
@endphp

@push('styles')
<style>
    .route-detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .status-active {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    #map {
        width: 100%;
        height: 500px;
        border-radius: 8px;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.125rem;
    }
    
    .stop-marker {
        background: #3b82f6;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
        margin-right: 1rem;
    }
    
    .schedule-day {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
    }
    
    .time-badge {
        background: #eff6ff;
        color: #1e40af;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Header con acciones -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white text-xl font-bold"
                 style="background-color: {{ $route->color }}">
                <i class="fas fa-bus"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $route->name }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="status-badge {{ $route->is_active ? 'status-active' : 'status-inactive' }}">
                        <i class="fas {{ $route->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                        {{ $route->is_active ? 'Activa' : 'Inactiva' }}
                    </span>
                    <span class="text-sm text-gray-500">
                        Creada: {{ $route->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('routes.edit', $route) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            
            <button onclick="toggleRouteStatus({{ $route->id }})" 
                    class="{{ $route->is_active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition-colors">
                <i class="fas {{ $route->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                {{ $route->is_active ? 'Desactivar' : 'Activar' }}
            </button>
            
            <button onclick="exportRoute()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition-colors">
                <i class="fas fa-download mr-2"></i>
                Exportar
            </button>
            
            <button onclick="deleteRoute({{ $route->id }})" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition-colors">
                <i class="fas fa-trash mr-2"></i>
                Eliminar
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Mapa -->
            <div class="route-detail-card">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-map text-green-600 mr-2"></i>
                        Mapa de la Ruta
                    </h2>
                </div>
                <div class="p-6">
                    <div id="map"></div>
                </div>
            </div>

            <!-- Lista de paradas -->
            <div class="route-detail-card">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                        Paradas de la Ruta ({{ $route->busStops->count() }})
                    </h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($route->busStops as $stop)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start space-x-3">
                                <div class="stop-marker">
                                    {{ $stop->order + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $stop->name }}</h3>
                                    @if($stop->address)
                                        <p class="text-sm text-gray-600 mt-1">{{ $stop->address }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-map-pin mr-1"></i>
                                        {{ number_format($stop->latitude, 6) }}, {{ number_format($stop->longitude, 6) }}
                                    </p>
                                </div>
                                <button onclick="focusOnStop({{ $stop->latitude }}, {{ $stop->longitude }})"
                                        class="text-blue-600 hover:text-blue-800 p-2 rounded-md hover:bg-blue-50 transition-colors"
                                        title="Ver en el mapa">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-map-marker-alt text-4xl mb-3 opacity-50"></i>
                            <p>No hay paradas registradas para esta ruta</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Panel lateral con información -->
        <div class="space-y-6">
            <!-- Información básica -->
            <div class="route-detail-card">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Información
                    </h2>
                </div>
                <div class="divide-y divide-gray-200">
                    <div class="info-item">
                        <div class="info-icon bg-green-100 text-green-600">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Punto de Inicio</p>
                            <p class="text-sm text-gray-600 truncate">{{ $route->start_location }}</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon bg-red-100 text-red-600">
                            <i class="fas fa-stop"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Punto Final</p>
                            <p class="text-sm text-gray-600 truncate">{{ $route->end_location }}</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon bg-purple-100 text-purple-600">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Color de Ruta</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <div class="w-6 h-6 rounded border border-gray-300" 
                                     style="background-color: {{ $route->color }}"></div>
                                <span class="text-sm text-gray-600">{{ $route->color }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon bg-blue-100 text-blue-600">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Total de Paradas</p>
                            <p class="text-sm text-gray-600">{{ $route->busStops->count() }} paradas</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Total de Horarios</p>
                            <p class="text-sm text-gray-600">{{ $route->schedules->count() }} horarios</p>
                        </div>
                    </div>

                    @if($route->description)
                    <div class="info-item">
                        <div class="info-icon bg-gray-100 text-gray-600">
                            <i class="fas fa-file-text"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Descripción</p>
                            <p class="text-sm text-gray-600">{{ $route->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Próximo horario -->
            @php
                $nextDeparture = $route->getNextDeparture();
            @endphp
            
            @if($nextDeparture)
                <div class="route-detail-card">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">
                            <i class="fas fa-clock text-purple-600 mr-2"></i>
                            Próximo Horario
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600 mb-2">
                                {{ $nextDeparture['time'] }}
                            </div>
                            <div class="text-lg text-gray-700 capitalize mb-1">
                                {{ $nextDeparture['day'] }}
                            </div>
                            @if($nextDeparture['is_today'])
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-calendar-day mr-1"></i>
                                    Hoy
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Próximo
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Estadísticas rápidas -->
            <div class="route-detail-card">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-chart-bar text-indigo-600 mr-2"></i>
                        Estadísticas
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Estado</span>
                        <span class="text-sm font-semibold {{ $route->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $route->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Días con servicio</span>
                        <span class="text-sm font-semibold text-gray-900">
                            {{ $route->getSchedulesByDay()->count() }}/7 días
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Última actualización</span>
                        <span class="text-sm font-semibold text-gray-900">
                            {{ $route->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Horarios detallados -->
    @if($route->schedules->count() > 0)
        <div class="route-detail-card">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                    Horarios Completos
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @php
                        $schedulesByDay = $route->getSchedulesByDay();
                        $dayNames = [
                            'lunes' => 'Lunes',
                            'martes' => 'Martes',
                            'miercoles' => 'Miércoles',
                            'jueves' => 'Jueves',
                            'viernes' => 'Viernes',
                            'sabado' => 'Sábado',
                            'domingo' => 'Domingo',
                        ];
                    @endphp

                    @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $day)
                        <div class="schedule-day">
                            <h4 class="font-semibold text-gray-900 mb-3">{{ $dayNames[$day] }}</h4>
                            <div class="space-y-1">
                                @if($schedulesByDay->has($day) && $schedulesByDay[$day]->count() > 0)
                                    @foreach($schedulesByDay[$day] as $time)
                                        <span class="time-badge">{{ $time }}</span>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500 italic">Sin servicio</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="route-detail-card">
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-clock text-4xl mb-3 opacity-50"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay horarios definidos</h3>
                <p class="text-gray-500 mb-4">Esta ruta no tiene horarios de servicio configurados</p>
                <a href="{{ route('routes.edit', $route) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Agregar Horarios
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key', 'AIzaSyAXWZfin7I7WXH62ZnHV-TRoC2XtMkMHDo') }}&libraries=places&callback=initMap"></script>

<script>
    let map;
    let directionsRenderer;

    // ✅ Datos de la ruta (sanitizados para evitar el error de corchetes)
    const routeData = @json($routePayload);

    function parseDirections(data) {
        // route_data puede venir como objeto/array (ideal) o como string JSON; lo manejamos seguro
        if (!data) return null;
        if (typeof data === 'string') {
            try { return JSON.parse(data); } catch (_) { return null; }
        }
        return data;
    }

    function initMap() {
        // Configuración inicial del mapa
        const defaultLocation = { lat: 13.6929, lng: -89.2182 }; // San Salvador
        
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: defaultLocation,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
        });

        // Configurar el renderizador de direcciones
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            polylineOptions: {
                strokeColor: routeData.color,
                strokeWeight: 6,
                strokeOpacity: 0.8,
            },
            suppressMarkers: true, // Suprimir marcadores por defecto
        });

        // Cargar la ruta si existe
        const directions = parseDirections(routeData.route_data);
        if (directions) {
            directionsRenderer.setDirections(directions);
        }

        // Agregar marcadores de paradas
        (routeData.stops || []).forEach(function (stop) {
            const marker = new google.maps.Marker({
                position: { lat: stop.lat, lng: stop.lng },
                map: map,
                title: stop.name,
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/bus.png',
                    scaledSize: new google.maps.Size(32, 32),
                },
            });

            // Ventana de información
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="p-3">
                        <h4 class="font-semibold text-gray-900 mb-2">${stop.name}</h4>
                        <p class="text-sm text-gray-600 mb-1">Parada #${(stop.order ?? 0) + 1}</p>
                        ${stop.address ? `<p class="text-xs text-gray-500">${stop.address}</p>` : ''}
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-map-pin mr-1"></i>
                            ${Number(stop.lat).toFixed(6)}, ${Number(stop.lng).toFixed(6)}
                        </p>
                    </div>
                `,
            });

            marker.addListener('click', function () {
                infoWindow.open(map, marker);
            });
        });

        // Ajustar el zoom para mostrar toda la ruta (usamos paradas como bounds)
        if ((routeData.stops || []).length) {
            const bounds = new google.maps.LatLngBounds();
            routeData.stops.forEach(function (stop) {
                bounds.extend({ lat: stop.lat, lng: stop.lng });
            });
            map.fitBounds(bounds);
        }
    }

    // Enfocar en una parada específica
    function focusOnStop(lat, lng) {
        map.setCenter({ lat: lat, lng: lng });
        map.setZoom(16);
        
        // Destacar temporalmente la parada
        const marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png',
                scaledSize: new google.maps.Size(40, 40),
            },
            animation: google.maps.Animation.BOUNCE,
        });

        // Remover el marcador después de 3 segundos
        setTimeout(() => {
            marker.setMap(null);
        }, 3000);
    }

    // Función para cambiar el estado de la ruta
    async function toggleRouteStatus(routeId) {
        try {
            const response = await axios.patch(`/admin/routes/${routeId}/toggle-status`);
            
            if (response.data.success) {
                showNotification(response.data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al cambiar el estado de la ruta', 'error');
        }
    }

    // Función para eliminar la ruta
    async function deleteRoute(routeId) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará la ruta y todos sus datos asociados',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
        });

        if (result.isConfirmed) {
            try {
                const response = await axios.delete(`/admin/routes/${routeId}`);
                
                if (response.data.success) {
                    Swal.fire({
                        title: '¡Eliminada!',
                        text: response.data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                    
                    setTimeout(() => {
                        window.location.href = '{{ route("routes.index") }}';
                    }, 2000);
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo eliminar la ruta',
                    icon: 'error',
                });
            }
        }
    }

    // Función para exportar la ruta
    function exportRoute() {
        const exportData = {
            route: routeData,
            exported_at: new Date().toISOString(),
            exported_from: 'Route Detail View',
        };

        const blob = new Blob([JSON.stringify(exportData, null, 2)], { 
            type: 'application/json', 
        });
        
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `ruta_${String(routeData.name || '').toLowerCase().replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        showNotification('Ruta exportada correctamente', 'success');
    }

    // Manejar errores de Google Maps
    window.gm_authFailure = function () {
        showNotification('Error de autenticación con Google Maps. Verifica la API key', 'error');
    };
</script>
@endpush
