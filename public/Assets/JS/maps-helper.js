// public/js/maps-helper.js - Helper para Google Maps sin warnings

class MapsHelper {
    constructor() {
        this.apiKey = 'AIzaSyAXWZfin7I7WXH62ZnHV-TRoC2XtMkMHDo';
        this.isLoaded = false;
        this.callbacks = [];
    }

    // Cargar Google Maps API de forma controlada
    loadMapsAPI() {
        return new Promise((resolve, reject) => {
            if (typeof google !== 'undefined' && google.maps) {
                this.isLoaded = true;
                resolve();
                return;
            }

            // Crear el script tag
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&libraries=places`;
            script.async = true;
            script.defer = true;
            
            script.onload = () => {
                this.isLoaded = true;
                this.callbacks.forEach(callback => callback());
                this.callbacks = [];
                resolve();
            };
            
            script.onerror = (error) => {
                console.error('Error loading Google Maps API:', error);
                reject(error);
            };
            
            document.head.appendChild(script);
        });
    }

    // Ejecutar callback cuando Maps esté listo
    whenReady(callback) {
        if (this.isLoaded) {
            callback();
        } else {
            this.callbacks.push(callback);
        }
    }

    // Crear mapa básico
    createMap(elementId, options = {}) {
        const defaultOptions = {
            zoom: 12,
            center: { lat: 13.6929, lng: -89.2182 }, // San Salvador
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
            zoomControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            ...options
        };

        return new google.maps.Map(document.getElementById(elementId), defaultOptions);
    }

    // Crear marcador de parada
    createBusStopMarker(position, map, options = {}) {
        const defaultOptions = {
            position: position,
            map: map,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/bus.png',
                scaledSize: new google.maps.Size(32, 32)
            },
            ...options
        };

        return new google.maps.Marker(defaultOptions);
    }

    // Crear renderer de direcciones
    createDirectionsRenderer(map, options = {}) {
        const defaultOptions = {
            map: map,
            draggable: true,
            polylineOptions: {
                strokeColor: '#3498db',
                strokeWeight: 6,
                strokeOpacity: 0.8
            },
            ...options
        };

        return new google.maps.DirectionsRenderer(defaultOptions);
    }

    // Servicio de direcciones
    getDirectionsService() {
        return new google.maps.DirectionsService();
    }

    // Servicio de geocoding
    getGeocodingService() {
        return new google.maps.Geocoder();
    }

    // Configurar autocompletado
    setupAutocomplete(inputElement, map) {
        const autocomplete = new google.maps.places.Autocomplete(inputElement);
        autocomplete.bindTo('bounds', map);
        return autocomplete;
    }

    // Calcular ruta
    calculateRoute(start, end, callback, options = {}) {
        const directionsService = this.getDirectionsService();
        
        const request = {
            origin: start,
            destination: end,
            travelMode: google.maps.TravelMode.DRIVING,
            avoidHighways: false,
            avoidTolls: false,
            ...options
        };

        directionsService.route(request, (result, status) => {
            if (status === 'OK') {
                callback(result, null);
            } else {
                callback(null, status);
            }
        });
    }

    // Obtener dirección desde coordenadas
    reverseGeocode(latlng, callback) {
        const geocoder = this.getGeocodingService();
        
        geocoder.geocode({ location: latlng }, (results, status) => {
            if (status === 'OK' && results[0]) {
                callback(results[0].formatted_address, null);
            } else {
                callback(null, status);
            }
        });
    }

    // Crear ventana de información
    createInfoWindow(content, options = {}) {
        const defaultOptions = {
            content: content,
            ...options
        };

        return new google.maps.InfoWindow(defaultOptions);
    }

    // Ajustar mapa a bounds
    fitBounds(map, bounds) {
        map.fitBounds(bounds);
    }

    // Crear bounds desde array de coordenadas
    createBoundsFromCoordinates(coordinates) {
        const bounds = new google.maps.LatLngBounds();
        coordinates.forEach(coord => {
            bounds.extend(coord);
        });
        return bounds;
    }

    // Convertir polyline a coordenadas
    decodePolyline(polyline) {
        return google.maps.geometry.encoding.decodePath(polyline);
    }

    // Formatear coordenadas
    formatCoordinates(lat, lng, precision = 6) {
        return {
            lat: parseFloat(parseFloat(lat).toFixed(precision)),
            lng: parseFloat(parseFloat(lng).toFixed(precision))
        };
    }

    // Calcular distancia entre dos puntos
    calculateDistance(point1, point2) {
        const service = new google.maps.DistanceMatrixService();
        return new Promise((resolve, reject) => {
            service.getDistanceMatrix({
                origins: [point1],
                destinations: [point2],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC
            }, (response, status) => {
                if (status === 'OK') {
                    resolve(response.rows[0].elements[0]);
                } else {
                    reject(status);
                }
            });
        });
    }

    // Manejar errores de autenticación
    handleAuthError() {
        console.error('Google Maps authentication failed. Please check your API key.');
        showNotification('Error de autenticación con Google Maps. Verifica la API key', 'error');
    }
}

// Crear instancia global
window.mapsHelper = new MapsHelper();

// Manejar errores de autenticación
window.gm_authFailure = function() {
    window.mapsHelper.handleAuthError();
};

// Funciones de utilidad global
function showNotification(message, type = 'success') {
    // Remover notificaciones existentes
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    const colors = {
        success: 'success',
        error: 'error', 
        warning: 'warning',
        info: 'info'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = `notification ${colors[type]}`;
    notification.innerHTML = `
        <div style="display: flex; align-items: center;">
            <i class="fas ${icons[type]}" style="margin-right: 8px;"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animación de entrada
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Eliminar después de 4 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Función para formatear fechas
function formatDate(date) {
    return new Intl.DateTimeFormat('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(date));
}

// Función para capitalizar texto
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Función para validar coordenadas
function validateCoordinates(lat, lng) {
    return lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
}

// Función para validar color hex
function validateHexColor(color) {
    return /^#[0-9A-F]{6}$/i.test(color);
}

// Función para generar ID único
function generateUniqueId() {
    return 'id_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

// Configuración global para Alpine.js
document.addEventListener('alpine:init', () => {
    // Configuraciones globales de Alpine si las necesitas
});

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages con clase notification
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        setTimeout(() => {
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        notification.remove();
                    }
                }, 300);
            }, 4000);
        }, 100);
    });
});

// Configurar Axios con CSRF token si existe
if (typeof axios !== 'undefined') {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
    }
    
    // Interceptor para manejar errores globalmente
    axios.interceptors.response.use(
        response => response,
        error => {
            if (error.response) {
                const status = error.response.status;
                const message = error.response.data.message || 'Error del servidor';
                
                switch (status) {
                    case 401:
                        showNotification('No autorizado. Por favor inicia sesión.', 'error');
                        break;
                    case 403:
                        showNotification('No tienes permisos para realizar esta acción.', 'error');
                        break;
                    case 404:
                        showNotification('Recurso no encontrado.', 'error');
                        break;
                    case 422:
                        // Errores de validación - se manejan en cada función
                        break;
                    case 500:
                        showNotification('Error interno del servidor.', 'error');
                        break;
                    default:
                        showNotification(message, 'error');
                }
            } else if (error.request) {
                showNotification('Error de conexión. Verifica tu internet.', 'error');
            } else {
                showNotification('Error inesperado.', 'error');
            }
            return Promise.reject(error);
        }
    );
}

// Utilidades para trabajar con localStorage de forma segura
const Storage = {
    set(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (e) {
            console.warn('LocalStorage not available:', e);
            return false;
        }
    },
    
    get(key, defaultValue = null) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (e) {
            console.warn('Error reading from localStorage:', e);
            return defaultValue;
        }
    },
    
    remove(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch (e) {
            console.warn('Error removing from localStorage:', e);
            return false;
        }
    },
    
    clear() {
        try {
            localStorage.clear();
            return true;
        } catch (e) {
            console.warn('Error clearing localStorage:', e);
            return false;
        }
    }
};

// Exportar para uso global
window.Storage = Storage;