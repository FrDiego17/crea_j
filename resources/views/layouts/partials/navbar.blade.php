@auth
    <header>
        <nav>
            <div class="logo">U-Go!</div>
            <ul class="menu">
                <li><a href="/home">Inicio</a></li>
                <li><a href="/horario">Horarios</a></li>
                <li><a href="#contacto">Contáctanos</a></li>
                <li><a href="/usuario">Bienvenido {{auth()->user()->username}} </a></li>
                <li><a href="/logout">Cerrar Session</a></li>
            </ul>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>
@endauth

@guest
    <header>
        <nav>
            <div class="logo">U-Go!</div>
            <ul class="menu">
                <li><a href="/home">Inicio</a></li>
                <li><a href="/login">Inicio de sesión</a></li>
                <li><a href="/register">Registrarse</a></li>
                <li><a href="#contacto">Contáctanos</a></li>
            </ul>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>
@endguest
