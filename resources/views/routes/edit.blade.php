@extends('layouts.app')

@section('title', 'Editar Ruta - ' . $route->name)

@php
    $pageTitle = 'Editar Ruta';
    $pageDescription = 'Modifica la información, paradas y horarios de la ruta';
    $breadcrumbs = [
        ['title' => 'Inicio', 'url' => route('routes.index')],
        ['title' => 'Rutas', 'url' => route('routes.index')],
        ['title' => $route->name, 'url' => route('routes.show', $route)],
        ['title' => 'Editar', 'url' => '']
    ];
@endphp

@push('styles')
<style>
    .map-container {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    #map {
        width: 100%;
        height: 500px;
    }
    
    .map-controls {
        position: absolute;
        top: 10px;
        left: 10px;
        background: white;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
    }
    
    .stop-item {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .stop-item:hover {
        background: #f1f3f4;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .schedule-day {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px;
    }
    
    .time-chip {
        background: #eff6ff;
        color: #1e40af;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-right: 5px;
        margin-bottom: 5px;
        display: inline-block;
    }

    .edit-mode-notice {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div x-data="routeEditManager()" x-init="initMap()" class="space-y-8">
    <!-- Aviso de modo edición -->
    <div class="edit-mode-notice">
        <div class="flex items-center">
            <i class="fas fa-edit text-2xl mr-3"></i>
            <div>
                <h3 class="font-semibold text-lg">Modo Edición Activo</h3>
                <p class="text-sm opacity-90">Estás editando la ruta "{{ $route->name }}". Los cambios se guardarán al hacer clic en "Actualizar Ruta".</p>
            </div>
        </div>
    </div>

    <form @submit.prevent="submitForm" class="space-y-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Formulario de información básica -->
            <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-3">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Información Básica
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Ruta *
                        </label>
                        <input type="text" 
                               id="name" 
                               x-model="formData.name"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: Ruta Centro-Universidad"
                               required>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea id="description" 
                                  x-model="formData.description"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Descripción detallada de la ruta..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="startLocation" class="block text-sm font-medium text-gray-700 mb-2">
                                Punto de Inicio *
                            </label>
                            <input type="text" 
                                   id="startLocation" 
                                   x-model="formData.start_location"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Buscar ubicación de inicio"
                                   required>
                        </div>
                        
                        <div>
                            <label for="endLocation" class="block text-sm font-medium text-gray-700 mb-2">
                                Punto Final *
                            </label>
                            <input type="text" 
                                   id="endLocation" 
                                   x-model="formData.end_location"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Buscar ubicación final"
                                   required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Precio del Pasaje *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" 
                                       id="price" 
                                       x-model.number="formData.price"
                                       step="0.01"
                                       min="0"
                                       max="999.99"
                                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0.25"
                                       required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                                Color de la Ruta
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="color" 
                                       id="color" 
                                       x-model="formData.color"
                                       class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer">
                                <span class="text-sm text-gray-600" x-text="formData.color"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="checkbox" 
                               id="is_active" 
                               x-model="formData.is_active"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="is_active" class="text-sm font-medium text-gray-700">
                            Ruta activa
                        </label>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            @click="calculateRoute()"
                            class="btn-gradient text-white px-6 py-3 rounded-lg font-medium inline-flex items-center"
                            :disabled="!formData.start_location || !formData.end_location || calculating"
                            x-bind:class="{ 'opacity-50 cursor-not-allowed': !formData.start_location || !formData.end_location || calculating }">
                        <i :class="calculating ? 'fas fa-spinner fa-spin' : 'fas fa-route'" class="mr-2"></i>
                        <span x-text="calculating ? 'Calculando...' : 'Recalcular Ruta'"></span>
                    </button>
                    
                    <button type="button" 
                            @click="resetToOriginal()"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors">
                        <i class="fas fa-undo mr-2"></i>
                        Restaurar Original
                    </button>
                </div>
                
                <!-- Lista de paradas -->
                <div class="border-t pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            Paradas de la Ruta
                        </h3>
                        <button type="button" 
                                @click="toggleStopsMode()"
                                class="px-4 py-2 rounded-lg font-medium text-sm transition-colors"
                                :class="stopsMode ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-blue-600 hover:bg-blue-700 text-white'">
                            <i :class="stopsMode ? 'fas fa-times' : 'fas fa-map-marker-alt'" class="mr-1"></i>
                            <span x-text="stopsMode ? 'Salir Modo Paradas' : 'Editar Paradas'"></span>
                        </button>
                    </div>
                    
                    <div id="stopsList" class="space-y-3 max-h-64 overflow-y-auto">
                        <template x-if="stops.length === 0">
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-map-marker-alt text-4xl mb-3 opacity-50"></i>
                                <p>Haz clic en "Editar Paradas" y luego en el mapa para añadir paradas</p>
                            </div>
                        </template>
                        
                        <template x-for="(stop, index) in stops" :key="stop.id">
                            <div class="stop-item">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold" 
                                                  x-text="index + 1"></span>
                                            <input type="text" 
                                                   x-model="stop.name"
                                                   class="flex-1 px-3 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   placeholder="Nombre de la parada">
                                        </div>
                                        <div class="text-xs text-gray-500 space-y-1">
                                            <p><strong>Coordenadas:</strong> <span x-text="`${stop.latitude.toFixed(6)}, ${stop.longitude.toFixed(6)}`"></span></p>
                                            <p x-show="stop.address"><strong>Dirección:</strong> <span x-text="stop.address"></span></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-3">
                                        <button type="button" 
                                                @click="focusOnStop(stop.latitude, stop.longitude)"
                                                class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-md transition-colors"
                                                title="Ver en mapa">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        <button type="button" 
                                                @click="removeStop(stop.id)"
                                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-md transition-colors">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Mapa -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-3 mb-6">
                    <i class="fas fa-map text-green-600 mr-2"></i>
                    Mapa Interactivo
                </h2>
                
                <div class="map-container">
                    <div class="map-controls">
                        <div class="text-xs text-gray-600 mb-2">
                            <div x-show="!stopsMode">Modo: Visualización</div>
                            <div x-show="stopsMode" class="text-blue-600 font-medium">Modo: Editar Paradas</div>
                        </div>
                        <div x-show="stopsMode" class="text-xs text-blue-600">
                            Haz clic en el mapa para agregar paradas
                        </div>
                    </div>
                    <div id="map"></div>
                </div>
                
                <div class="mt-4 text-sm text-gray-600">
                    <p><strong>Instrucciones:</strong></p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Puedes arrastrar la ruta para modificarla</li>
                        <li>Activa "Editar Paradas" para añadir o quitar paradas</li>
                        <li>Haz clic en "Recalcular Ruta" si cambias los puntos de inicio/final</li>
                        <li>Usa "Restaurar Original" para volver a la configuración inicial</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Gestión de horarios -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 border-b pb-3 mb-6">
                <i class="fas fa-clock text-purple-600 mr-2"></i>
                Gestión de Horarios
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Día</label>
                    <select x-model="newSchedule.day" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="lunes">Lunes</option>
                        <option value="martes">Martes</option>
                        <option value="miercoles">Miércoles</option>
                        <option value="jueves">Jueves</option>
                        <option value="viernes">Viernes</option>
                        <option value="sabado">Sábado</option>
                        <option value="domingo">Domingo</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora</label>
                    <input type="time" 
                           x-model="newSchedule.time"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="md:col-span-2 flex items-end">
                    <button type="button" 
                            @click="addSchedule()"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium inline-flex items-center transition-colors w-full md:w-auto">
                        <i class="fas fa-plus mr-2"></i>
                        Agregar Horario
                    </button>
                </div>
            </div>
            
            <div class="schedule-grid">
                <template x-for="day in ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']" :key="day">
                    <div class="schedule-day">
                        <h4 class="font-medium text-gray-900 mb-3 capitalize" x-text="getDayName(day)"></h4>
                        <div class="space-y-2 min-h-[60px]">
                            <template x-if="getSchedulesForDay(day).length === 0">
                                <p class="text-sm text-gray-500 italic">Sin horarios</p>
                            </template>
                            <template x-for="schedule in getSchedulesForDay(day)" :key="schedule.id">
                                <div class="flex items-center justify-between">
                                    <span class="time-chip" x-text="schedule.time"></span>
                                    <button type="button" 
                                            @click="removeSchedule(schedule.id)"
                                            class="text-red-600 hover:text-red-800 p-1">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Botones de acción -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="text-sm text-gray-600">
                    <p><strong>Resumen de cambios:</strong></p>
                    <p>Paradas: <span x-text="stops.length"></span> | Horarios: <span x-text="schedules.length"></span> | Precio: $<span x-text="formData.price || '0.00'"></span></p>
                    <p class="text-xs mt-1">Última modificación: <span x-text="lastModified"></span></p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('routes.show', $route) }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    
                    <button type="submit" 
                            class="btn-success-gradient text-white px-6 py-3 rounded-lg font-medium inline-flex items-center"
                            :disabled="!canSubmit()"
                            x-bind:class="{ 'opacity-50 cursor-not-allowed': !canSubmit() }">
                        <i class="fas fa-save mr-2"></i>
                        <span x-show="!submitting">Actualizar Ruta</span>
                        <span x-show="submitting">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Actualizando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key', 'AIzaSyAXWZfin7I7WXH62ZnHV-TRoC2XtMkMHDo') }}&libraries=places&callback=initializeGoogleMaps"></script>

<script>
    function routeEditManager() {
        return {
            // Datos originales de la ruta
            originalData: {
                name: {!! json_encode($route->name) !!},
                description: {!! json_encode($route->description) !!},
                start_location: {!! json_encode($route->start_location) !!},
                end_location: {!! json_encode($route->end_location) !!},
                price: {{ $route->price ?? 0 }},
                color: {!! json_encode($route->color) !!},
                is_active: {{ $route->is_active ? 'true' : 'false' }},
                route_data: {!! $route->route_data ? json_encode($route->route_data) : 'null' !!},
                stops: {!! json_encode($route->busStops->map(function($stop) {
                    return [
                        'id' => $stop->id,
                        'name' => $stop->name,
                        'latitude' => (float)$stop->latitude,
                        'longitude' => (float)$stop->longitude,
                        'address' => $stop->address,
                        'order' => $stop->order
                    ];
                })) !!},
                schedules: {!! json_encode($route->schedules->map(function($schedule) {
                    return [
                        'id' => $schedule->id,
                        'day' => $schedule->day_of_week,
                        'time' => $schedule->departure_time->format('H:i')
                    ];
                })) !!}
            },
            
            // Estado del formulario (inicializado con datos originales)
            formData: {
                name: {!! json_encode($route->name) !!},
                description: {!! json_encode($route->description) !!},
                start_location: {!! json_encode($route->start_location) !!},
                end_location: {!! json_encode($route->end_location) !!},
                price: {{ $route->price ?? 0 }},
                color: {!! json_encode($route->color) !!},
                is_active: {{ $route->is_active ? 'true' : 'false' }}
            },
            
            // Estado del mapa
            map: null,
            directionsService: null,
            directionsRenderer: null,
            geocoder: null,
            currentRoute: null,
            stopsMode: false,
            calculating: false,
            mapLoaded: false,
            
            // Autocompletado
            startAutocomplete: null,
            endAutocomplete: null,
            
            // Datos de paradas y horarios
            stops: [],
            schedules: [],
            submitting: false,
            lastModified: new Date().toLocaleString(),
            
            // Nuevo horario temporal
            newSchedule: {
                day: 'lunes',
                time: ''
            },
            
            // Inicializar el componente
            initMap() {
                // Cargar datos originales
                this.loadOriginalData();
                window.routeEditInstance = this;
            },
            
            loadOriginalData() {
                // Cargar paradas originales
                this.stops = [...this.originalData.stops];
                
                // Cargar horarios originales
                this.schedules = this.originalData.schedules.map(schedule => ({
                    id: schedule.id,
                    day: schedule.day,
                    time: schedule.time
                }));
                
                // Cargar datos de ruta original
                if (this.originalData.route_data) {
                    try {
                        this.currentRoute = typeof this.originalData.route_data === 'string' 
                            ? JSON.parse(this.originalData.route_data) 
                            : this.originalData.route_data;
                    } catch (e) {
                        console.error('Error parsing route data:', e);
                        this.currentRoute = null;
                    }
                }
            },
            
            // Configurar el mapa después de que Google Maps se cargue
            setupMap() {
                console.log('Inicializando mapa de edición...');
                const defaultLocation = { lat: 13.6929, lng: -89.2182 }; // San Salvador
                
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 12,
                    center: defaultLocation,
                    mapTypeControl: true,
                    streetViewControl: true,
                    fullscreenControl: true
                });

                this.directionsService = new google.maps.DirectionsService();
                this.directionsRenderer = new google.maps.DirectionsRenderer({
                    draggable: true,
                    map: this.map
                });

                this.geocoder = new google.maps.Geocoder();

                // Cargar ruta original si existe
                if (this.currentRoute) {
                    this.directionsRenderer.setDirections(this.currentRoute);
                    this.directionsRenderer.setOptions({
                        polylineOptions: {
                            strokeColor: this.formData.color,
                            strokeWeight: 6,
                            strokeOpacity: 0.8
                        }
                    });
                }

                // Configurar autocompletado
                this.setupAutocomplete();
                
                // Configurar eventos del mapa
                this.setupMapEvents();
                
                // Cargar paradas existentes
                this.loadExistingStops();
                
                this.mapLoaded = true;
                console.log('Mapa de edición inicializado correctamente');
            },
            
            setupAutocomplete() {
                try {
                    const startInput = document.getElementById('startLocation');
                    const endInput = document.getElementById('endLocation');

                    if (startInput && endInput) {
                        this.startAutocomplete = new google.maps.places.Autocomplete(startInput, {
                            bounds: new google.maps.LatLngBounds(
                                new google.maps.LatLng(13.5, -89.5),
                                new google.maps.LatLng(14.0, -89.0)
                            ),
                            strictBounds: false,
                            types: ['establishment', 'geocode']
                        });

                        this.endAutocomplete = new google.maps.places.Autocomplete(endInput, {
                            bounds: new google.maps.LatLngBounds(
                                new google.maps.LatLng(13.5, -89.5),
                                new google.maps.LatLng(14.0, -89.0)
                            ),
                            strictBounds: false,
                            types: ['establishment', 'geocode']
                        });

                        // Eventos para cuando se selecciona un lugar
                        this.startAutocomplete.addListener('place_changed', () => {
                            const place = this.startAutocomplete.getPlace();
                            if (place.geometry) {
                                this.formData.start_location = place.name || place.formatted_address;
                            }
                        });

                        this.endAutocomplete.addListener('place_changed', () => {
                            const place = this.endAutocomplete.getPlace();
                            if (place.geometry) {
                                this.formData.end_location = place.name || place.formatted_address;
                            }
                        });

                        console.log('Autocompletado de edición configurado');
                    }
                } catch (error) {
                    console.error('Error configurando autocompletado:', error);
                }
            },
            
            setupMapEvents() {
                // Evento para agregar paradas
                this.map.addListener('click', (event) => {
                    if (this.stopsMode) {
                        this.addBusStop(event.latLng);
                    }
                });
                
                // Evento cuando se modifica la ruta
                this.directionsRenderer.addListener('directions_changed', () => {
                    this.currentRoute = this.directionsRenderer.getDirections();
                    this.updateLastModified();
                });

                console.log('Eventos del mapa de edición configurados');
            },
            
            loadExistingStops() {
                // Crear marcadores para las paradas existentes
                this.stops.forEach(stop => {
                    this.createStopMarker(stop);
                });
            },
            
            createStopMarker(stop) {
                const marker = new google.maps.Marker({
                    position: { lat: stop.latitude, lng: stop.longitude },
                    map: this.map,
                    title: stop.name,
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/bus.png',
                        scaledSize: new google.maps.Size(32, 32)
                    }
                });

                stop.marker = marker;
                
                // Agregar info window
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 10px;">
                            <h4>${stop.name}</h4>
                            <p class="text-sm text-gray-600">${stop.address || 'Sin dirección'}</p>
                            <p class="text-xs text-gray-500">${stop.latitude.toFixed(6)}, ${stop.longitude.toFixed(6)}</p>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(this.map, marker);
                });
            },
            
            // Calcular ruta entre inicio y final
            calculateRoute() {
                if (!this.mapLoaded) {
                    showNotification('El mapa aún se está cargando, intenta de nuevo', 'warning');
                    return;
                }

                if (!this.formData.start_location || !this.formData.end_location) {
                    showNotification('Completa los puntos de inicio y final', 'warning');
                    return;
                }

                if (this.calculating) {
                    return;
                }

                this.calculating = true;
                console.log('Recalculando ruta:', {
                    origen: this.formData.start_location,
                    destino: this.formData.end_location
                });

                const request = {
                    origin: this.formData.start_location.trim(),
                    destination: this.formData.end_location.trim(),
                    travelMode: google.maps.TravelMode.DRIVING,
                    avoidHighways: false,
                    avoidTolls: false,
                    region: 'SV'
                };

                this.directionsService.route(request, (result, status) => {
                    this.calculating = false;
                    
                    console.log('Resultado de la ruta:', status, result);
                    
                    if (status === 'OK') {
                        this.directionsRenderer.setDirections(result);
                        this.currentRoute = result;
                        
                        // Aplicar color personalizado
                        this.directionsRenderer.setOptions({
                            polylineOptions: {
                                strokeColor: this.formData.color || "#4285F4",
                                strokeWeight: 6,
                                strokeOpacity: 0.8
                            }
                        });

                        // Ajustar vista del mapa
                        const bounds = new google.maps.LatLngBounds();
                        const route = result.routes[0];
                        bounds.extend(route.bounds.getNorthEast());
                        bounds.extend(route.bounds.getSouthWest());
                        this.map.fitBounds(bounds);

                        this.updateLastModified();
                        showNotification('Ruta recalculada correctamente', 'success');
                    } else {
                        let errorMessage = 'No se pudo calcular la ruta';
                        
                        switch(status) {
                            case 'NOT_FOUND':
                                errorMessage = 'No se encontraron las ubicaciones especificadas';
                                break;
                            case 'ZERO_RESULTS':
                                errorMessage = 'No se encontró una ruta entre estos puntos';
                                break;
                            case 'MAX_WAYPOINTS_EXCEEDED':
                                errorMessage = 'Demasiados puntos de referencia';
                                break;
                            case 'INVALID_REQUEST':
                                errorMessage = 'Solicitud inválida. Verifica las ubicaciones';
                                break;
                            case 'OVER_QUERY_LIMIT':
                                errorMessage = 'Límite de consultas excedido. Intenta más tarde';
                                break;
                            case 'REQUEST_DENIED':
                                errorMessage = 'Solicitud denegada. Verifica la configuración de la API';
                                break;
                            case 'UNKNOWN_ERROR':
                                errorMessage = 'Error desconocido. Intenta de nuevo';
                                break;
                        }
                        
                        showNotification(errorMessage, 'error');
                        console.error('Error calculando ruta:', status, result);
                    }
                });
            },
            
            // Restaurar datos originales
            resetToOriginal() {
                // Restaurar formulario
                Object.assign(this.formData, {
                    name: this.originalData.name,
                    description: this.originalData.description,
                    start_location: this.originalData.start_location,
                    end_location: this.originalData.end_location,
                    price: this.originalData.price,
                    color: this.originalData.color,
                    is_active: this.originalData.is_active
                });
                
                // Limpiar marcadores actuales
                this.stops.forEach(stop => {
                    if (stop.marker) {
                        stop.marker.setMap(null);
                    }
                });
                
                // Restaurar datos originales
                this.loadOriginalData();
                this.loadExistingStops();
                
                // Restaurar ruta en el mapa
                if (this.currentRoute) {
                    this.directionsRenderer.setDirections(this.currentRoute);
                    this.directionsRenderer.setOptions({
                        polylineOptions: {
                            strokeColor: this.formData.color,
                            strokeWeight: 6,
                            strokeOpacity: 0.8
                        }
                    });
                }
                
                if (this.stopsMode) {
                    this.toggleStopsMode();
                }
                
                showNotification('Datos restaurados a la versión original', 'info');
            },
            
            // Alternar modo de editar paradas
            toggleStopsMode() {
                this.stopsMode = !this.stopsMode;
                
                if (this.stopsMode) {
                    this.map.setOptions({ cursor: 'crosshair' });
                    showNotification('Haz clic en el mapa para agregar paradas', 'info');
                } else {
                    this.map.setOptions({ cursor: 'default' });
                }
            },
            
            // Agregar parada de bus
            addBusStop(location) {
                const stopId = 'stop_' + Date.now();
                const order = this.stops.length;
                
                const marker = new google.maps.Marker({
                    position: location,
                    map: this.map,
                    title: `Parada #${order + 1}`,
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/bus.png',
                        scaledSize: new google.maps.Size(32, 32)
                    }
                });

                const stop = {
                    id: stopId,
                    name: `Parada #${order + 1}`,
                    latitude: location.lat(),
                    longitude: location.lng(),
                    address: '',
                    order: order,
                    marker: marker
                };

                this.stops.push(stop);
                
                // Obtener dirección
                this.geocoder.geocode({ location: location }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        stop.address = results[0].formatted_address;
                        this.$nextTick(() => {
                            this.stops = [...this.stops];
                        });
                    }
                });
                
                this.updateLastModified();
                showNotification('Parada agregada', 'success');
            },
            
            // Enfocar en una parada
            focusOnStop(lat, lng) {
                this.map.setCenter({ lat: lat, lng: lng });
                this.map.setZoom(16);
            },
            
            // Eliminar parada
            removeStop(stopId) {
                const stopIndex = this.stops.findIndex(s => s.id === stopId);
                
                if (stopIndex !== -1) {
                    const stop = this.stops[stopIndex];
                    if (stop.marker) {
                        stop.marker.setMap(null);
                    }
                    this.stops.splice(stopIndex, 1);
                    
                    // Renumerar paradas
                    this.stops.forEach((stop, index) => {
                        stop.order = index;
                        if (stop.name.includes('Parada #')) {
                            stop.name = `Parada #${index + 1}`;
                        }
                        if (stop.marker) {
                            stop.marker.setTitle(`Parada #${index + 1}`);
                        }
                    });
                    
                    this.updateLastModified();
                    showNotification('Parada eliminada', 'success');
                }
            },
            
            // Agregar horario
            addSchedule() {
                if (!this.newSchedule.time) {
                    showNotification('Selecciona una hora', 'warning');
                    return;
                }
                
                const scheduleExists = this.schedules.some(s => 
                    s.day === this.newSchedule.day && s.time === this.newSchedule.time
                );
                
                if (scheduleExists) {
                    showNotification('Este horario ya existe para este día', 'warning');
                    return;
                }
                
                this.schedules.push({
                    id: 'schedule_' + Date.now(),
                    day: this.newSchedule.day,
                    time: this.newSchedule.time
                });
                
                this.newSchedule.time = '';
                this.updateLastModified();
                showNotification('Horario agregado', 'success');
            },
            
            // Eliminar horario
            removeSchedule(scheduleId) {
                this.schedules = this.schedules.filter(s => s.id !== scheduleId);
                this.updateLastModified();
                showNotification('Horario eliminado', 'success');
            },
            
            // Obtener horarios por día
            getSchedulesForDay(day) {
                return this.schedules
                    .filter(s => s.day === day)
                    .sort((a, b) => a.time.localeCompare(b.time));
            },
            
            // Obtener nombre del día
            getDayName(day) {
                const days = {
                    'lunes': 'Lunes',
                    'martes': 'Martes',
                    'miercoles': 'Miércoles',
                    'jueves': 'Jueves',
                    'viernes': 'Viernes',
                    'sabado': 'Sábado',
                    'domingo': 'Domingo'
                };
                return days[day] || day;
            },
            
            updateLastModified() {
                this.lastModified = new Date().toLocaleString();
            },
            
            // Verificar si se puede enviar el formulario
            canSubmit() {
                return this.formData.name && 
                       this.formData.start_location && 
                       this.formData.end_location && 
                       this.formData.price > 0 &&
                       this.stops.length > 0 && 
                       !this.submitting;
            },
            
            // Enviar formulario
            async submitForm() {
                // Validación del precio antes del envío
                if (!this.formData.price || parseFloat(this.formData.price) <= 0) {
                    showNotification('El precio debe ser un número válido mayor a 0', 'warning');
                    this.submitting = false;
                    return;
                }

                if (!this.canSubmit()) {
                    showNotification('Completa todos los campos requeridos', 'warning');
                    return;
                }
                
                this.submitting = true;
                
                try {
                    const formData = new FormData();
                    
                    // Log para debugging
                    console.log('Precio antes de enviar:', this.formData.price);
                    console.log('Precio como número:', parseFloat(this.formData.price));
                    
                    // Datos básicos
                    formData.append('name', this.formData.name);
                    formData.append('description', this.formData.description || '');
                    formData.append('start_location', this.formData.start_location);
                    formData.append('end_location', this.formData.end_location);
                    formData.append('price', parseFloat(this.formData.price)); // Convertir a número
                    formData.append('color', this.formData.color);
                    formData.append('is_active', this.formData.is_active ? '1' : '0');
                    
                    // Datos de la ruta de Google Maps
                    if (this.currentRoute) {
                        formData.append('route_data', JSON.stringify(this.currentRoute));
                        formData.append('route_polyline', this.currentRoute.routes[0].overview_polyline.points);
                        formData.append('route_bounds', JSON.stringify(this.currentRoute.routes[0].bounds));
                        
                        // Información adicional de la ruta
                        const route = this.currentRoute.routes[0];
                        if (route.legs && route.legs.length > 0) {
                            const totalDistance = route.legs.reduce((sum, leg) => sum + leg.distance.value, 0);
                            const totalDuration = route.legs.reduce((sum, leg) => sum + leg.duration.value, 0);
                            
                            formData.append('total_distance', totalDistance);
                            formData.append('total_duration', totalDuration);
                        }
                    }
                    
                    // Paradas
                    this.stops.forEach((stop, index) => {
                        formData.append(`stops[${index}][name]`, stop.name);
                        formData.append(`stops[${index}][latitude]`, stop.latitude);
                        formData.append(`stops[${index}][longitude]`, stop.longitude);
                        formData.append(`stops[${index}][address]`, stop.address || '');
                        formData.append(`stops[${index}][order]`, stop.order);
                    });
                    
                    // Horarios
                    this.schedules.forEach((schedule, index) => {
                        formData.append(`schedules[${index}][day_of_week]`, schedule.day);
                        formData.append(`schedules[${index}][departure_time]`, schedule.time);
                    });
                    
                    formData.append('_method', 'PUT');
                    
                    // Log de FormData para debugging
                    console.log('FormData completa:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }
                    
                    const response = await axios.post('{{ route("routes.update", $route) }}', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    if (response.data.success) {
                        showNotification('Ruta actualizada exitosamente', 'success');
                        setTimeout(() => {
                            window.location.href = '{{ route("routes.show", $route) }}';
                        }, 2000);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    if (error.response && error.response.data.errors) {
                        const errors = Object.values(error.response.data.errors).flat();
                        showNotification('Errores: ' + errors.join(', '), 'error');
                    } else if (error.response && error.response.data.message) {
                        showNotification(error.response.data.message, 'error');
                    } else {
                        showNotification('Error al actualizar la ruta', 'error');
                    }
                } finally {
                    this.submitting = false;
                }
            }
        }
    }
    
    // Variable global para almacenar la instancia
    let googleMapsEditTimeout;
    
    // Inicializar Google Maps cuando se carga la API
    window.initializeGoogleMaps = function() {
        console.log('Google Maps API cargada para edición');
        
        if (window.routeEditInstance) {
            window.routeEditInstance.setupMap();
        } else {
            // Esperar un poco más si la instancia no está lista
            googleMapsEditTimeout = setTimeout(() => {
                if (window.routeEditInstance) {
                    window.routeEditInstance.setupMap();
                } else {
                    console.error('No se pudo encontrar la instancia de routeEditManager');
                }
            }, 500);
        }
    };
    
    // Limpiar timeout si existe
    if (googleMapsEditTimeout) {
        clearTimeout(googleMapsEditTimeout);
    }
</script>
@endpush