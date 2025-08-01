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
                <li class="nav-item">
                    <div id="google_translate_element" class="translate-bar"></div>
                </li>
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
                <li class="nav-item">
                    <div id="google_translate_element" class="translate-bar"></div>
                </li>
            </ul>

            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>
@endguest

<div id="google_translate_element" style="display: none;"></div>
<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement(
      {
        pageLanguage: 'es',
        includedLanguages: 'en,es,fr,de,it,pt',
        layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
      },
      'google_translate_element'
    );
  }
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
