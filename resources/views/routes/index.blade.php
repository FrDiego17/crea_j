@extends('layouts.app')

@section('title', 'Lista de Rutas')

@php
    $pageTitle = 'Gestión de Rutas de Bus';
    $pageDescription = 'Administra todas las rutas, paradas y horarios del sistema de transporte';
    $breadcrumbs = [
        ['title' => 'Inicio', 'url' => route('routes.index')],
        ['title' => 'Rutas', 'url' => '']
    ];
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header con estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-route text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Rutas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $routes->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-bus text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Rutas Activas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $routes->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-map-marker-alt text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Paradas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $routes->sum(fn($route) => $route->busStops->count()) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Horarios</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $routes->sum(fn($route) => $route->schedules->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones principales -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('routes.create') }}" class="btn-gradient text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nueva Ruta
            </a>
            
            <button onclick="exportRoutes()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors">
                <i class="fas fa-download mr-2"></i>
                Exportar Datos
            </button>
        </div>
        
        <!-- Filtros y búsqueda -->
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Buscar rutas..." 
                       class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Todos los estados</option>
                <option value="active">Activas</option>
                <option value="inactive">Inactivas</option>
            </select>
        </div>
    </div>

    <!-- Lista de rutas -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($routes->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($routes as $route)
                    <div class="p-6 hover:bg-gray-50 transition-colors route-item" 
                         data-name="{{ strtolower($route->name) }}" 
                         data-status="{{ $route->is_active ? 'active' : 'inactive' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $route->color }}"></div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $route->name }}</h3>
                                    @if($route->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Activa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Inactiva
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-600 mt-1">{{ $route->description ?: 'Sin descripción' }}</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm text-gray-500">
                                    <div>
                                        <span class="font-medium">Origen:</span>
                                        <p>{{ $route->start_location }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium">Destino:</span>
                                        <p>{{ $route->end_location }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium">Paradas:</span>
                                        <p>{{ $route->busStops->count() }} parada{{ $route->busStops->count() !== 1 ? 's' : '' }}</p>
                                    </div>
                                </div>

                                <!-- Horarios resumidos -->
                                @if($route->schedules->count() > 0)
                                    <div class="mt-3">
                                        <span class="text-sm font-medium text-gray-700">Próximos horarios:</span>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @php
                                                $nextSchedules = $route->schedules->take(5);
                                            @endphp
                                            @foreach($nextSchedules as $schedule)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                                    {{ ucfirst($schedule->day_of_week) }} {{ $schedule->formatted_time }}
                                                </span>
                                            @endforeach
                                            @if($route->schedules->count() > 5)
                                                <span class="text-xs text-gray-500">+{{ $route->schedules->count() - 5 }} más</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <span class="text-sm text-gray-500 italic">Sin horarios definidos</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Acciones -->
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('routes.show', $route) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('routes.edit', $route) }}" 
                                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button onclick="toggleRouteStatus({{ $route->id }})" 
                                        class="{{ $route->is_active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                        title="{{ $route->is_active ? 'Desactivar' : 'Activar' }}">
                                    <i class="fas {{ $route->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                                
                                <button onclick="deleteRoute({{ $route->id }})" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="bg-white px-6 py-3 border-t border-gray-200">
                {{ $routes->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-bus text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay rutas registradas</h3>
                <p class="text-gray-500 mb-6">Comienza creando tu primera ruta de bus</p>
                <a href="{{ route('routes.create') }}" class="btn-gradient text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primera Ruta
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Funciones de filtrado y búsqueda
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const routeItems = document.querySelectorAll('.route-item');

        function filterRoutes() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;

            routeItems.forEach(item => {
                const name = item.getAttribute('data-name');
                const status = item.getAttribute('data-status');
                
                const matchesSearch = name.includes(searchTerm);
                const matchesStatus = !statusValue || status === statusValue;
                
                item.style.display = matchesSearch && matchesStatus ? 'block' : 'none';
            });
        }

        if (searchInput && statusFilter) {
            searchInput.addEventListener('input', filterRoutes);
            statusFilter.addEventListener('change', filterRoutes);
        }
    });

    // Función para cambiar el estado de una ruta con SweetAlert2
    async function toggleRouteStatus(routeId) {
        // Obtener información actual de la ruta
        const button = event.target.closest('button');
        const routeItem = button.closest('.route-item');
        const routeName = routeItem.querySelector('h3').textContent;
        const isActive = routeItem.getAttribute('data-status') === 'active';
        
        // Configurar el modal de confirmación
        const result = await Swal.fire({
            title: isActive ? '¿Desactivar ruta?' : '¿Activar ruta?',
            html: `
                <div class="text-left">
                    <p class="mb-3"><strong>Ruta:</strong> ${routeName}</p>
                    <p class="text-gray-600">
                        ${isActive 
                            ? 'La ruta dejará de estar disponible para los usuarios.' 
                            : 'La ruta estará disponible para los usuarios.'
                        }
                    </p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isActive ? '#f59e0b' : '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: isActive ? 'Sí, desactivar' : 'Sí, activar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'border-l-4 ' + (isActive ? 'border-l-orange-500' : 'border-l-green-500')
            }
        });

        if (!result.isConfirmed) return;

        // Mostrar loading
        Swal.fire({
            title: isActive ? 'Desactivando ruta...' : 'Activando ruta...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await axios.patch(`/routes/${routeId}/toggle-status`, {}, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.data.success) {
                // Cerrar loading y mostrar éxito
                await Swal.fire({
                    title: '¡Éxito!',
                    text: response.data.message,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                
                // Actualizar la interfaz sin recargar
                updateRouteStatusUI(routeItem, button, response.data.is_active);
                
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.data.message || 'Error al cambiar estado',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            
            let message = 'Error al cambiar el estado de la ruta';
            if (error.response?.data?.message) {
                message = error.response.data.message;
            } else if (error.response?.status === 419) {
                message = 'Sesión expirada. Por favor, recarga la página.';
            }
            
            Swal.fire({
                title: 'Error',
                text: message,
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        }
    }

    // Función para actualizar la UI sin recargar la página
    function updateRouteStatusUI(routeItem, button, isActive) {
        // Actualizar el atributo data-status
        routeItem.setAttribute('data-status', isActive ? 'active' : 'inactive');
        
        // Actualizar el badge de estado
        const statusBadge = routeItem.querySelector('.inline-flex.items-center.px-2\\.5');
        if (statusBadge) {
            if (isActive) {
                statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Activa';
            } else {
                statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                statusBadge.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Inactiva';
            }
        }
        
        // Actualizar el botón
        if (isActive) {
            button.className = 'bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors';
            button.innerHTML = '<i class="fas fa-pause"></i>';
            button.title = 'Desactivar';
        } else {
            button.className = 'bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors';
            button.innerHTML = '<i class="fas fa-play"></i>';
            button.title = 'Activar';
        }
    }

    // Función para eliminar una ruta con SweetAlert2 mejorado
    async function deleteRoute(routeId) {
        // Obtener información de la ruta
        const button = event.target.closest('button');
        const routeItem = button.closest('.route-item');
        const routeName = routeItem.querySelector('h3').textContent;
        const stopsCount = routeItem.querySelector('.route-item p').textContent.match(/(\d+) parada/)?.[1] || '0';
        
        // Modal de confirmación con información detallada
        const result = await Swal.fire({
            title: '¿Eliminar ruta?',
            html: `
                <div class="text-left space-y-3">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="font-medium text-red-800 mb-1">Ruta a eliminar:</p>
                        <p class="text-red-700">${routeName}</p>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="font-medium text-yellow-800 mb-1">Se eliminarán también:</p>
                        <ul class="text-yellow-700 text-sm list-disc list-inside">
                            <li>${stopsCount} parada(s) de bus</li>
                            <li>Todos los horarios programados</li>
                            <li>Historial de la ruta</li>
                        </ul>
                    </div>
                    
                    <p class="text-gray-600 text-center font-medium">
                        ⚠️ Esta acción no se puede deshacer
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'border-l-4 border-l-red-500'
            },
            // Requiere confirmación adicional para acciones peligrosas
            input: 'text',
            inputLabel: 'Escribe "ELIMINAR" para confirmar:',
            inputPlaceholder: 'ELIMINAR',
            inputValidator: (value) => {
                if (value !== 'ELIMINAR') {
                    return 'Debes escribir "ELIMINAR" exactamente';
                }
            }
        });

        if (!result.isConfirmed) return;

        // Mostrar loading
        Swal.fire({
            title: 'Eliminando ruta...',
            html: 'Por favor espera, esto puede tomar unos momentos.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await axios.delete(`/routes/${routeId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.data.success) {
                // Animación de éxito
                await Swal.fire({
                    title: '¡Eliminada!',
                    text: response.data.message || 'La ruta ha sido eliminada exitosamente',
                    icon: 'success',
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                
                // Remover visualmente el elemento
                routeItem.style.transform = 'translateX(-100%)';
                routeItem.style.opacity = '0';
                setTimeout(() => routeItem.remove(), 300);
                
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.data.message || 'Error al eliminar la ruta',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            
            let message = 'No se pudo eliminar la ruta';
            let details = '';
            
            if (error.response) {
                if (error.response.status === 404) {
                    message = 'Ruta no encontrada';
                    details = 'Es posible que la ruta ya haya sido eliminada.';
                } else if (error.response.status === 403) {
                    message = 'Sin permisos';
                    details = 'No tienes autorización para eliminar esta ruta.';
                } else if (error.response.status === 419) {
                    message = 'Sesión expirada';
                    details = 'Por favor, recarga la página e intenta de nuevo.';
                } else if (error.response.data?.message) {
                    message = error.response.data.message;
                }
            }
            
            Swal.fire({
                title: message,
                text: details,
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        }
    }

    // Función para exportar datos con mejor feedback
    async function exportRoutes() {
        try {
            // Mostrar loading
            Swal.fire({
                title: 'Exportando datos...',
                text: 'Preparando archivo de exportación',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const routesData = @json($routes->items());
            
            const dataToExport = {
                routes: routesData.map(route => ({
                    id: route.id,
                    name: route.name,
                    description: route.description,
                    start_location: route.start_location,
                    end_location: route.end_location,
                    price: route.price,
                    color: route.color,
                    stops: route.bus_stops || [],
                    schedules: route.schedules || [],
                    is_active: route.is_active,
                    created_at: route.created_at
                })),
                exported_at: new Date().toISOString(),
                total_routes: routesData.length,
                exported_by: 'Sistema de Gestión de Rutas'
            };

            const blob = new Blob([JSON.stringify(dataToExport, null, 2)], { 
                type: 'application/json' 
            });
            
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            const fileName = `rutas_bus_${new Date().toISOString().split('T')[0]}.json`;
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            // Mostrar éxito con información del archivo
            Swal.fire({
                title: '¡Exportación exitosa!',
                html: `
                    <div class="text-left">
                        <p class="mb-2"><strong>Archivo:</strong> ${fileName}</p>
                        <p class="mb-2"><strong>Rutas exportadas:</strong> ${routesData.length}</p>
                        <p class="text-gray-600 text-sm">El archivo se ha descargado automáticamente</p>
                    </div>
                `,
                icon: 'success',
                timer: 3000,
                timerProgressBar: true
            });

        } catch (error) {
            console.error('Error exportando datos:', error);
            Swal.fire({
                title: 'Error',
                text: 'Error al exportar los datos. Intenta de nuevo.',
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        }
    }
</script>
@endpush