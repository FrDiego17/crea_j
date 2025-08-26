<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Rutas de Bus')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 350px;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #3498db, #2980b9);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        
        .btn-success-gradient {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }
        
        .btn-danger-gradient {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }
        
        .btn-warning-gradient {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="gradient-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('routes.index') }}" class="text-xl font-bold">
                        <i class="fas fa-bus mr-2"></i>
                        Admin Rutas de Bus
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('routes.index') }}" class="hover:bg-white hover:bg-opacity-20 px-3 py-2 rounded-md transition-colors">
                        <i class="fas fa-list mr-1"></i>
                        Rutas
                    </a>
                    <a href="{{ route('routes.create') }}" class="hover:bg-white hover:bg-opacity-20 px-3 py-2 rounded-md transition-colors">
                        <i class="fas fa-plus mr-1"></i>
                        Nueva Ruta
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        @if(isset($breadcrumbs))
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                @foreach($breadcrumbs as $breadcrumb)
                    <li class="inline-flex items-center">
                        @if(!$loop->last)
                            <a href="{{ $breadcrumb['url'] }}" class="text-gray-500 hover:text-gray-700">
                                {{ $breadcrumb['title'] }}
                            </a>
                            <i class="fas fa-chevron-right text-gray-400 ml-2"></i>
                        @else
                            <span class="text-gray-700 font-medium">{{ $breadcrumb['title'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
        @endif

        <!-- Page Header -->
        @if(isset($pageTitle) || isset($pageDescription))
        <div class="mb-8">
            @isset($pageTitle)
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $pageTitle }}</h1>
            @endisset
            @isset($pageDescription)
                <p class="text-gray-600">{{ $pageDescription }}</p>
            @endisset
        </div>
        @endif

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="notification bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="notification bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="notification bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-semibold">Errores de validación:</span>
                </div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500">
                <p>&copy; {{ date('Y') }} Sistema de Administración de Rutas de Bus. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        // Configurar Axios con CSRF token
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Función global para mostrar notificaciones
        function showNotification(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            const notification = document.createElement('div');
            notification.className = `notification ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg transform translate-x-full opacity-0 transition-all duration-300`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${icons[type]} mr-2"></i>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animación de entrada
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
            }, 100);
            
            // Eliminar después de 4 segundos
            setTimeout(() => {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 4000);
        }
        
        // Auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 4000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>