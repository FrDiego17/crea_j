@auth

  <!DOCTYPE html>
  <html lang="es">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Rutas Universitarias</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="{{ url('Assets/CSS/contact.css')}}">
  </head>
  <body>
    @include('layouts.partials.navbar')
      
      <section class="routes-section">
          <h2 class="section-title">Rutas de Buses Universitarios</h2>
          <div class="routes-carousel">
              <button class="carousel-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
              
              <div class="carousel-container">
                  <div class="carousel-track">
                      <!-- Las tarjetas se generarán dinámicamente con JavaScript -->
                  </div>
              </div>
              
              <button class="carousel-btn next-btn"><i class="fas fa-chevron-right"></i></button>
          </div>
      </section>
      <footer class="ugo-footer">
          <div class="footer-container">
            <div class="footer-brand">
              <h2>U-Go!</h2>
              <p>Movilidad universitaria inteligente</p>
            </div>
            
            <div class="footer-grid">
              <div class="footer-column">
                <h3>Enlaces</h3>
                <ul>
                  <li><a href="#">Inicio</a></li>
                  <li><a href="#">Sobre nosotros</a></li>
                  <li><a href="#">Condiciones</a></li>
                  <li><a href="#">Horarios</a></li>
                </ul>
              </div>
              
              <div class="footer-column">
                <h3>Contacto</h3>
                <ul>
                  <li>El Salvador, San Salvador</li>
                  <li>infoUgo@gmail.com</li>
                </ul>
              </div>
              
              <div class="footer-column">
                <h3>Newsletter</h3>
                <form class="newsletter-form">
                  <input type="email" placeholder="Tu email">
                  <button type="submit">Enviar</button>
                </form>
              </div>
            </div>
          </div>
          
          <div class="footer-bottom">
            <p>© 2025 U-Go! - Todos los derechos reservados.</p>
          </div>
        </footer>

      <script src="{{ url('Assets/JS/contac.js')}}"></script>
  </body>
  </html>
@endauth