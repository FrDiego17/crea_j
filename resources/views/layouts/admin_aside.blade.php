<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('Assets/CSS/admin-conductores.css')}}">
    <title>Document</title>
</head>
<body>
    <div class= "d-flex" >
        <aside class="sidebar">

            <div class="logo">
                <i class="bi bi-bootstrap"></i> U Go!
            </div>

            <a href="/admin" class="menu-item">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <a href="{{route('users.index')}}" class="menu-item">
                <i class="bi bi-house-door"></i> Usuarios
            </a>

            <a href="{{route('conductores.index')}}" class="menu-item">
                <i class="bi bi-box"></i> Conductores
            </a>
            <a href="{{route('rutas.index')}}" class="menu-item">
                <i class="bi bi-people"></i> Rutas
            </a>

            <div class="footer">
                <i class="bi bi-person-circle"></i> usuario
            </div>
        </aside>
    </div>

    @yield('aside')
</body>
</html>