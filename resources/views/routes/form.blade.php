<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Rutas de Bus</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
        }

        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .map-section {
            position: relative;
        }

        #map {
            width: 100%;
            height: 500px;
            border-radius: 10px;
            border: 1px solid #ddd;
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

        .stops-list {
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #ddd;
            max-height: 300px;
            overflow-y: auto;
        }

        .stop-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }

        .stop-item:last-child {
            border-bottom: none;
        }

        .stop-info {
            flex: 1;
        }

        .stop-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .stop-coords {
            font-size: 0.9em;
            color: #7f8c8d;
        }

        .schedules-section {
            grid-column: 1 / -1;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            margin-top: 20px;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .schedule-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .routes-list {
            grid-column: 1 / -1;
            background: white;
            border-radius: 10px;
            padding: 25px;
            border: 1px solid #ddd;
            margin-top: 20px;
        }

        .route-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
        }

        .route-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .route-name {
            font-size: 1.2em;
            font-weight: 600;
            color: #2c3e50;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 10000;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 90vw;
            max-height: 90vh;
            overflow: auto;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
            color: #aaa;
        }

        .close:hover {
            color: #000;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .schedules-section, .routes-list {
                grid-column: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚌 Administrador de Rutas de Bus</h1>
            <p>Gestiona rutas, paradas y horarios de manera intuitiva</p>
        </div>

        <div class="main-content">
            <!-- Formulario para crear ruta -->
            <div class="form-section">
                <h2>📝 Crear Nueva Ruta</h2>
                <form id="routeForm">
                    <div class="form-group">
                        <label for="routeName">Nombre de la Ruta</label>
                        <input type="text" id="routeName" placeholder="Ej: Ruta Centro-Universidad" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="routeDescription">Descripción</label>
                        <textarea id="routeDescription" rows="3" placeholder="Descripción de la ruta..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="startLocation">Punto de Inicio</label>
                        <input type="text" id="startLocation" placeholder="Buscar ubicación de inicio" required>
                    </div>

                    <div class="form-group">
                        <label for="endLocation">Punto Final</label>
                        <input type="text" id="endLocation" placeholder="Buscar ubicación final" required>
                    </div>

                    <div class="form-group">
                        <label for="routeColor">Color de la Ruta</label>
                        <input type="color" id="routeColor" value="#3498db">
                    </div>

                    <button type="button" class="btn" onclick="calculateRoute()">📍 Calcular Ruta</button>
                    <button type="button" class="btn btn-success" onclick="saveRoute()" disabled id="saveBtn">💾 Guardar Ruta</button>
                </form>

                <!-- Lista de paradas -->
                <div class="stops-list">
                    <h3>🚏 Paradas de la Ruta</h3>
                    <div id="stopsList">
                        <p style="text-align: center; color: #7f8c8d;">Haz clic en el mapa para agregar paradas</p>
                    </div>
                </div>
            </div>

            <!-- Mapa -->
            <div class="map-section">
                <div class="map-controls">
                    <button class="btn" onclick="clearMap()">🗑️ Limpiar</button>
                    <button class="btn" onclick="toggleStopsMode()" id="stopsBtn">📍 Modo Paradas</button>
                </div>
                <div id="map"></div>
            </div>
        </div>

        <!-- Sección de horarios -->
        <div class="main-content">
            <div class="schedules-section">
                <h2>⏰ Gestión de Horarios</h2>
                <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                    <input type="time" id="newSchedule" placeholder="Nuevo horario">
                    <select id="scheduleDay">
                        <option value="lunes">Lunes</option>
                        <option value="martes">Martes</option>
                        <option value="miercoles">Miércoles</option>
                        <option value="jueves">Jueves</option>
                        <option value="viernes">Viernes</option>
                        <option value="sabado">Sábado</option>
                        <option value="domingo">Domingo</option>
                    </select>
                    <button class="btn" onclick="addSchedule()">➕ Agregar</button>
                </div>
                
                <div class="schedule-grid" id="scheduleGrid">
                    <!-- Los horarios se llenarán dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Lista de rutas guardadas -->
        <div class="main-content">
            <div class="routes-list">
                <h2>🗂️ Rutas Guardadas</h2>
                <div id="savedRoutes">
                    <!-- Las rutas guardadas se mostrarán aquí -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver ruta completa -->
    <div id="routeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle"></h2>
            <div id="modalMap" style="width: 100%; height: 400px; margin: 20px 0;"></div>
            <div id="modalInfo"></div>
        </div>
    </div>

    <script>
        // Variables globales
        let map;
        let directionsService;
        let directionsRenderer;
        let geocoder;
        let currentRoute = null;
        let stopsMode = false;
        let routeStops = [];
        let schedules = {};
        let savedRoutes = [];

        // Datos simulados para demostración
        const DEMO_API_KEY = 'AIzaSyAXWZfin7I7WXH62ZnHV-TRoC2XtMkMHDo'; 

        // Inicializar la aplicación
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            loadSavedRoutes();
            setupAutocomplete();
        });

        function initMap() {
            // Configuración del mapa (San Salvador como ejemplo)
            const defaultLocation = { lat: 13.6929, lng: -89.2182 };
            
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: defaultLocation,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                draggable: true,
                map: map
            });

            geocoder = new google.maps.Geocoder();

            // Evento para agregar paradas
            map.addListener('click', function(event) {
                if (stopsMode) {
                    addBusStop(event.latLng);
                }
            });

            // Evento cuando se modifica la ruta
            directionsRenderer.addListener('directions_changed', function() {
                document.getElementById('saveBtn').disabled = false;
            });
        }

        function setupAutocomplete() {
            const startInput = document.getElementById('startLocation');
            const endInput = document.getElementById('endLocation');

            const startAutocomplete = new google.maps.places.Autocomplete(startInput);
            const endAutocomplete = new google.maps.places.Autocomplete(endInput);

            startAutocomplete.bindTo('bounds', map);
            endAutocomplete.bindTo('bounds', map);
        }

        function calculateRoute() {
            const start = document.getElementById('startLocation').value;
            const end = document.getElementById('endLocation').value;

            if (!start || !end) {
                alert('Por favor ingresa el punto de inicio y final');
                return;
            }

            const request = {
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING,
                avoidHighways: false,
                avoidTolls: false
            };

            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                    currentRoute = result;
                    
                    // Cambiar color de la ruta
                    const routeColor = document.getElementById('routeColor').value;
                    directionsRenderer.setOptions({
                        polylineOptions: {
                            strokeColor: routeColor,
                            strokeWeight: 6,
                            strokeOpacity: 0.8
                        }
                    });

                    document.getElementById('saveBtn').disabled = false;
                    showNotification('Ruta calculada correctamente', 'success');
                } else {
                    alert('No se pudo calcular la ruta: ' + status);
                }
            });
        }

        function toggleStopsMode() {
            stopsMode = !stopsMode;
            const btn = document.getElementById('stopsBtn');
            
            if (stopsMode) {
                btn.textContent = '🛑 Salir Modo Paradas';
                btn.style.background = 'linear-gradient(135deg, #e74c3c, #c0392b)';
                map.setOptions({ cursor: 'crosshair' });
                showNotification('Haz clic en el mapa para agregar paradas', 'info');
            } else {
                btn.textContent = '📍 Modo Paradas';
                btn.style.background = 'linear-gradient(135deg, #3498db, #2980b9)';
                map.setOptions({ cursor: 'default' });
            }
        }

        function addBusStop(location) {
            const stopId = 'stop_' + Date.now();
            
            // Crear marcador para la parada
            const marker = new google.maps.Marker({
                position: location,
                map: map,
                title: 'Parada de Bus #' + (routeStops.length + 1),
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/bus.png',
                    scaledSize: new google.maps.Size(32, 32)
                }
            });

            // Ventana de información
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px;">
                        <h4>Parada #${routeStops.length + 1}</h4>
                        <input type="text" id="stopName_${stopId}" placeholder="Nombre de la parada" style="width: 100%; margin: 5px 0; padding: 5px;">
                        <button onclick="saveStopName('${stopId}')" style="padding: 5px 10px; background: #3498db; color: white; border: none; border-radius: 3px;">Guardar</button>
                        <button onclick="removeStop('${stopId}')" style="padding: 5px 10px; background: #e74c3c; color: white; border: none; border-radius: 3px; margin-left: 5px;">Eliminar</button>
                    </div>
                `
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });

            // Guardar parada
            const stop = {
                id: stopId,
                name: `Parada #${routeStops.length + 1}`,
                lat: location.lat(),
                lng: location.lng(),
                marker: marker,
                infoWindow: infoWindow
            };

            routeStops.push(stop);
            updateStopsList();
            
            // Obtener nombre de la dirección
            geocoder.geocode({ location: location }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    stop.address = results[0].formatted_address;
                    updateStopsList();
                }
            });
        }

        function saveStopName(stopId) {
            const stop = routeStops.find(s => s.id === stopId);
            const nameInput = document.getElementById(`stopName_${stopId}`);
            
            if (stop && nameInput) {
                stop.name = nameInput.value || stop.name;
                stop.marker.setTitle(stop.name);
                updateStopsList();
                stop.infoWindow.close();
                showNotification('Nombre de parada guardado', 'success');
            }
        }

        function removeStop(stopId) {
            const stopIndex = routeStops.findIndex(s => s.id === stopId);
            
            if (stopIndex !== -1) {
                const stop = routeStops[stopIndex];
                stop.marker.setMap(null);
                routeStops.splice(stopIndex, 1);
                updateStopsList();
                showNotification('Parada eliminada', 'success');
            }
        }

        function updateStopsList() {
            const stopsList = document.getElementById('stopsList');
            
            if (routeStops.length === 0) {
                stopsList.innerHTML = '<p style="text-align: center; color: #7f8c8d;">Haz clic en el mapa para agregar paradas</p>';
                return;
            }

            stopsList.innerHTML = routeStops.map((stop, index) => `
                <div class="stop-item">
                    <div class="stop-info">
                        <div class="stop-name">${stop.name}</div>
                        <div class="stop-coords">${stop.lat.toFixed(6)}, ${stop.lng.toFixed(6)}</div>
                        ${stop.address ? `<div class="stop-coords">${stop.address}</div>` : ''}
                    </div>
                    <button class="btn btn-danger" onclick="removeStop('${stop.id}')" style="padding: 5px 10px; font-size: 12px;">Eliminar</button>
                </div>
            `).join('');
        }

        function addSchedule() {
            const timeInput = document.getElementById('newSchedule');
            const daySelect = document.getElementById('scheduleDay');
            
            if (!timeInput.value) {
                alert('Por favor selecciona una hora');
                return;
            }

            const day = daySelect.value;
            const time = timeInput.value;

            if (!schedules[day]) {
                schedules[day] = [];
            }

            if (!schedules[day].includes(time)) {
                schedules[day].push(time);
                schedules[day].sort();
                updateScheduleGrid();
                timeInput.value = '';
                showNotification('Horario agregado correctamente', 'success');
            } else {
                alert('Este horario ya existe para este día');
            }
        }

        function removeSchedule(day, time) {
            if (schedules[day]) {
                schedules[day] = schedules[day].filter(t => t !== time);
                if (schedules[day].length === 0) {
                    delete schedules[day];
                }
                updateScheduleGrid();
                showNotification('Horario eliminado', 'success');
            }
        }

        function updateScheduleGrid() {
            const grid = document.getElementById('scheduleGrid');
            const days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
            const dayNames = {
                'lunes': 'Lunes',
                'martes': 'Martes', 
                'miercoles': 'Miércoles',
                'jueves': 'Jueves',
                'viernes': 'Viernes',
                'sabado': 'Sábado',
                'domingo': 'Domingo'
            };

            grid.innerHTML = days.map(day => {
                const daySchedules = schedules[day] || [];
                return `
                    <div class="schedule-item">
                        <h4 style="margin-bottom: 10px; color: #2c3e50;">${dayNames[day]}</h4>
                        ${daySchedules.length === 0 ? 
                            '<p style="color: #7f8c8d; font-size: 0.9em;">Sin horarios</p>' :
                            daySchedules.map(time => `
                                <div style="display: flex; justify-content: space-between; align-items: center; margin: 5px 0; padding: 5px; background: #ecf0f1; border-radius: 3px;">
                                    <span>${time}</span>
                                    <button onclick="removeSchedule('${day}', '${time}')" style="background: #e74c3c; color: white; border: none; border-radius: 3px; padding: 2px 6px; font-size: 12px;">×</button>
                                </div>
                            `).join('')
                        }
                    </div>
                `;
            }).join('');
        }

        function saveRoute() {
            const routeName = document.getElementById('routeName').value;
            const routeDescription = document.getElementById('routeDescription').value;
            const startLocation = document.getElementById('startLocation').value;
            const endLocation = document.getElementById('endLocation').value;
            const routeColor = document.getElementById('routeColor').value;

            if (!routeName || !currentRoute || routeStops.length === 0) {
                alert('Por favor completa todos los campos requeridos y agrega al menos una parada');
                return;
            }

            // Crear objeto de ruta para guardar
            const newRoute = {
                id: 'route_' + Date.now(),
                name: routeName,
                description: routeDescription,
                startLocation: startLocation,
                endLocation: endLocation,
                color: routeColor,
                stops: routeStops.map(stop => ({
                    id: stop.id,
                    name: stop.name,
                    lat: stop.lat,
                    lng: stop.lng,
                    address: stop.address
                })),
                schedules: { ...schedules },
                routeData: currentRoute,
                createdAt: new Date().toISOString()
            };

            // Simular guardado en base de datos
            savedRoutes.push(newRoute);
            localStorage.setItem('busRoutes', JSON.stringify(savedRoutes));

            // Limpiar formulario
            clearForm();
            updateSavedRoutesList();
            showNotification('Ruta guardada correctamente en la base de datos', 'success');
        }

        function clearForm() {
            document.getElementById('routeForm').reset();
            document.getElementById('routeColor').value = '#3498db';
            clearMap();
            schedules = {};
            updateScheduleGrid();
            document.getElementById('saveBtn').disabled = true;
        }

        function clearMap() {
            if (directionsRenderer) {
                directionsRenderer.setMap(null);
                directionsRenderer = new google.maps.DirectionsRenderer({
                    draggable: true,
                    map: map
                });
            }
            
            routeStops.forEach(stop => {
                stop.marker.setMap(null);
            });
            
            routeStops = [];
            currentRoute = null;
            updateStopsList();
            
            if (stopsMode) {
                toggleStopsMode();
            }
        }

        function loadSavedRoutes() {
            const saved = localStorage.getItem('busRoutes');
            if (saved) {
                savedRoutes = JSON.parse(saved);
                updateSavedRoutesList();
            }
        }

        function updateSavedRoutesList() {
            const container = document.getElementById('savedRoutes');
            
            if (savedRoutes.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #7f8c8d;">No hay rutas guardadas</p>';
                return;
            }

            container.innerHTML = savedRoutes.map(route => `
                <div class="route-card">
                    <div class="route-header">
                        <div>
                            <div class="route-name">${route.name}</div>
                            <p style="color: #7f8c8d; margin: 5px 0;">${route.description || 'Sin descripción'}</p>
                            <p style="font-size: 0.9em; color: #7f8c8d;">
                                <strong>Desde:</strong> ${route.startLocation}<br>
                                <strong>Hasta:</strong> ${route.endLocation}<br>
                                <strong>Paradas:</strong> ${route.stops.length}<br>
                                <strong>Creada:</strong> ${new Date(route.createdAt).toLocaleDateString()}
                            </p>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn" onclick="viewRoute('${route.id}')">👁️ Ver</button>
                            <button class="btn" onclick="editRoute('${route.id}')">✏️ Editar</button>
                            <button class="btn btn-danger" onclick="deleteRoute('${route.id}')">🗑️ Eliminar</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function viewRoute(routeId) {
            const route = savedRoutes.find(r => r.id === routeId);
            if (!route) return;

            document.getElementById('modalTitle').textContent = route.name;
            document.getElementById('routeModal').style.display = 'block';

            // Inicializar mapa del modal
            setTimeout(() => {
                const modalMap = new google.maps.Map(document.getElementById('modalMap'), {
                    zoom: 12,
                    center: { lat: 13.6929, lng: -89.2182 }
                });

                const modalDirectionsRenderer = new google.maps.DirectionsRenderer({
                    map: modalMap,
                    polylineOptions: {
                        strokeColor: route.color,
                        strokeWeight: 6,
                        strokeOpacity: 0.8
                    }
                });

                if (route.routeData) {
                    modalDirectionsRenderer.setDirections(route.routeData);
                }

                // Agregar paradas
                route.stops.forEach(stop => {
                    new google.maps.Marker({
                        position: { lat: stop.lat, lng: stop.lng },
                        map: modalMap,
                        title: stop.name,
                        icon: {
                            url: 'https://maps.google.com/mapfiles/ms/icons/bus.png',
                            scaledSize: new google.maps.Size(32, 32)
                        }
                    });
                });

                // Mostrar información de la ruta
                const scheduleInfo = Object.keys(route.schedules).length > 0 ? 
                    Object.entries(route.schedules).map(([day, times]) => 
                        `<strong>${day.charAt(0).toUpperCase() + day.slice(1)}:</strong> ${times.join(', ')}`
                    ).join('<br>') : 'Sin horarios definidos';

                document.getElementById('modalInfo').innerHTML = `
                    <div style="margin-top: 20px;">
                        <h3>Información de la Ruta</h3>
                        <p><strong>Descripción:</strong> ${route.description || 'N/A'}</p>
                        <p><strong>Punto de Inicio:</strong> ${route.startLocation}</p>
                        <p><strong>Punto Final:</strong> ${route.endLocation}</p>
                        <p><strong>Número de Paradas:</strong> ${route.stops.length}</p>
                        <p><strong>Color de Ruta:</strong> <span style="display: inline-block; width: 20px; height: 20px; background: ${route.color}; border-radius: 3px; vertical-align: middle;"></span> ${route.color}</p>
                        <h4 style="margin-top: 15px;">Paradas:</h4>
                        <ul style="margin: 10px 0;">
                            ${route.stops.map(stop => `<li><strong>${stop.name}</strong> - ${stop.address || 'Coordenadas: ' + stop.lat.toFixed(6) + ', ' + stop.lng.toFixed(6)}</li>`).join('')}
                        </ul>
                        <h4 style="margin-top: 15px;">Horarios:</h4>
                        <div style="margin: 10px 0;">${scheduleInfo}</div>
                    </div>
                `;
            }, 100);
        }

        function editRoute(routeId) {
            const route = savedRoutes.find(r => r.id === routeId);
            if (!route) return;

            // Llenar formulario con datos de la ruta
            document.getElementById('routeName').value = route.name;
            document.getElementById('routeDescription').value = route.description || '';
            document.getElementById('startLocation').value = route.startLocation;
            document.getElementById('endLocation').value = route.endLocation;
            document.getElementById('routeColor').value = route.color;

            // Cargar ruta en el mapa
            if (route.routeData) {
                directionsRenderer.setDirections(route.routeData);
                directionsRenderer.setOptions({
                    polylineOptions: {
                        strokeColor: route.color,
                        strokeWeight: 6,
                        strokeOpacity: 0.8
                    }
                });
                currentRoute = route.routeData;
            }

            // Cargar paradas
            routeStops = route.stops.map(stop => {
                const marker = new google.maps.Marker({
                    position: { lat: stop.lat, lng: stop.lng },
                    map: map,
                    title: stop.name,
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/bus.png',
                        scaledSize: new google.maps.Size(32, 32)
                    }
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 10px;">
                            <h4>${stop.name}</h4>
                            <input type="text" id="stopName_${stop.id}" value="${stop.name}" placeholder="Nombre de la parada" style="width: 100%; margin: 5px 0; padding: 5px;">
                            <button onclick="saveStopName('${stop.id}')" style="padding: 5px 10px; background: #3498db; color: white; border: none; border-radius: 3px;">Guardar</button>
                            <button onclick="removeStop('${stop.id}')" style="padding: 5px 10px; background: #e74c3c; color: white; border: none; border-radius: 3px; margin-left: 5px;">Eliminar</button>
                        </div>
                    `
                });

                marker.addListener('click', function() {
                    infoWindow.open(map, marker);
                });

                return {
                    id: stop.id,
                    name: stop.name,
                    lat: stop.lat,
                    lng: stop.lng,
                    address: stop.address,
                    marker: marker,
                    infoWindow: infoWindow
                };
            });

            // Cargar horarios
            schedules = { ...route.schedules };
            updateScheduleGrid();
            updateStopsList();

            // Eliminar ruta original para evitar duplicados
            savedRoutes = savedRoutes.filter(r => r.id !== routeId);
            localStorage.setItem('busRoutes', JSON.stringify(savedRoutes));
            updateSavedRoutesList();

            document.getElementById('saveBtn').disabled = false;
            showNotification('Ruta cargada para edición', 'info');

            // Scroll hacia arriba para mostrar el formulario
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function deleteRoute(routeId) {
            if (confirm('¿Estás seguro de que quieres eliminar esta ruta?')) {
                savedRoutes = savedRoutes.filter(r => r.id !== routeId);
                localStorage.setItem('busRoutes', JSON.stringify(savedRoutes));
                updateSavedRoutesList();
                showNotification('Ruta eliminada correctamente', 'success');
            }
        }

        function closeModal() {
            document.getElementById('routeModal').style.display = 'none';
        }

        function showNotification(message, type = 'info') {
            // Crear elemento de notificación
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                z-index: 10001;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
                max-width: 300px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            `;

            // Colores según el tipo
            const colors = {
                success: 'linear-gradient(135deg, #27ae60, #2ecc71)',
                error: 'linear-gradient(135deg, #e74c3c, #c0392b)',
                info: 'linear-gradient(135deg, #3498db, #2980b9)',
                warning: 'linear-gradient(135deg, #f39c12, #e67e22)'
            };

            notification.style.background = colors[type] || colors.info;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Animación de entrada
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Eliminar después de 4 segundos
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 4000);
        }

        // Función para exportar datos (útil para integración con backend)
        function exportRouteData() {
            const dataToExport = {
                routes: savedRoutes.map(route => ({
                    id: route.id,
                    name: route.name,
                    description: route.description,
                    startLocation: route.startLocation,
                    endLocation: route.endLocation,
                    color: route.color,
                    stops: route.stops,
                    schedules: route.schedules,
                    createdAt: route.createdAt,
                    // Datos de la ruta para reconstruir en la app móvil
                    routePolyline: route.routeData?.routes[0]?.overview_polyline?.points,
                    bounds: route.routeData?.routes[0]?.bounds
                }))
            };

            console.log('Datos para exportar a la base de datos:', JSON.stringify(dataToExport, null, 2));
            
            // Crear enlace de descarga
            const blob = new Blob([JSON.stringify(dataToExport, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'rutas_bus_' + new Date().toISOString().split('T')[0] + '.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            showNotification('Datos exportados correctamente', 'success');
        }

        // Función para integrar con backend (ejemplo)
        async function syncWithDatabase() {
            try {
                // Ejemplo de cómo enviar los datos a tu backend
                const response = await fetch('/api/routes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('authToken') // Si usas autenticación
                    },
                    body: JSON.stringify({
                        routes: savedRoutes.map(route => ({
                            id: route.id,
                            name: route.name,
                            description: route.description,
                            startLocation: route.startLocation,
                            endLocation: route.endLocation,
                            color: route.color,
                            stops: route.stops.map(stop => ({
                                id: stop.id,
                                name: stop.name,
                                latitude: stop.lat,
                                longitude: stop.lng,
                                address: stop.address
                            })),
                            schedules: route.schedules,
                            routePolyline: route.routeData?.routes[0]?.overview_polyline?.points,
                            createdAt: route.createdAt
                        }))
                    })
                });

                if (response.ok) {
                    showNotification('Datos sincronizados con la base de datos', 'success');
                } else {
                    throw new Error('Error en la sincronización');
                }
            } catch (error) {
                console.error('Error al sincronizar:', error);
                showNotification('Error al sincronizar con la base de datos', 'error');
            }
        }

        // Agregar botones de utilidad
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.header');
            const utilityButtons = document.createElement('div');
            utilityButtons.style.cssText = 'margin-top: 15px;';
            utilityButtons.innerHTML = `
                <button class="btn" onclick="syncWithDatabase()" style="margin: 0 5px;">🔄 Sincronizar BD</button>
            `;
            header.appendChild(utilityButtons);
        });

        // Función para cargar una ruta de demostración
        function loadDemoRoute() {
            // Limpiar primero
            clearForm();

            // Llenar con datos de ejemplo
            document.getElementById('routeName').value = 'Ruta Centro-Universidad';
            document.getElementById('routeDescription').value = 'Ruta principal que conecta el centro de la ciudad con la zona universitaria';
            document.getElementById('startLocation').value = 'Plaza Barrios, San Salvador, El Salvador';
            document.getElementById('endLocation').value = 'Universidad de El Salvador, San Salvador, El Salvador';
            document.getElementById('routeColor').value = '#e74c3c';

            // Agregar horarios de ejemplo
            schedules = {
                'lunes': ['06:00', '07:00', '08:00', '12:00', '17:00', '18:00'],
                'martes': ['06:00', '07:00', '08:00', '12:00', '17:00', '18:00'],
                'miercoles': ['06:00', '07:00', '08:00', '12:00', '17:00', '18:00'],
                'jueves': ['06:00', '07:00', '08:00', '12:00', '17:00', '18:00'],
                'viernes': ['06:00', '07:00', '08:00', '12:00', '17:00', '18:00'],
                'sabado': ['08:00', '10:00', '14:00', '16:00']
            };
            updateScheduleGrid();

            showNotification('Ruta de demostración cargada. Haz clic en "Calcular Ruta" para continuar', 'info');
        }

        // Cerrar modal al hacer clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('routeModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Inicializar cuando se carga Google Maps
        function initializeGoogleMaps() {
            // Esta función se llamará cuando Google Maps esté listo
            if (typeof google !== 'undefined') {
                initMap();
            } else {
                setTimeout(initializeGoogleMaps, 100);
            }
        }

        // Manejar errores de Google Maps
        window.gm_authFailure = function() {
            showNotification('Error de autenticación con Google Maps. Verifica tu API key', 'error');
        };
    </script>

    <!-- Cargar Google Maps API -->
    <script async defer 
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXWZfin7I7WXH62ZnHV-TRoC2XtMkMHDo&libraries=places&callback=initializeGoogleMaps">
    </script>


</body>
</html>