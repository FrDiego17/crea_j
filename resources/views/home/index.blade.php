@extends('layouts.app-master')

@section('content')

    @auth
    <section id="inicio" class="hero">
        <div class="carrusel">
            <div class="slide active">
                <img src="{{ url('Assets/IMG/bass.jpg')}}" alt="Movilidad inteligente">
            </div>
            <div class="slide active">
                <img src="{{ url('Assets/IMG/carrusel1.jpg')}}" alt="Movilidad inteligente">
            </div>
            <div class="slide active">
                <img src="{{ url('Assets/IMG/carrusel2.jpg')}}" alt="Movilidad inteligente">
            </div>
            <div class="slide active">
                <img src="{{ url('Assets/IMG/carrusel4.jpg')}}" alt="Movilidad inteligente">
            </div>

            <div class="carrusel-controls">
                <button class="prev"><i class="fas fa-chevron-left"></i></button>
                <button class="next"><i class="fas fa-chevron-right"></i></button>
            </div>
            <div class="carrusel-indicators"></div>
        </div>
        
        <div class="hero-content">
            <h1>Una forma más justa de moverte en la universidad</h1>
            <p>Rutas seguras, fáciles y en tiempo real.</p>
            <div class="app-buttons">
                <a href="#" class="btn-descarga">
                    <i class="fab fa-apple"></i> App Store
                </a>
                <a href="#" class="btn-descarga">
                    <i class="fab fa-google-play"></i> Play Store
                </a>
            </div>
        </div>
        
    </section>
    
    <section class="about-section">
        <div class="about-container">
          <div class="about-image">
            <img src="{{ url('Assets/IMG/conductor.jpg')}}" alt="Estudiante en transporte">
          </div>
          <div class="about-text">
            <h2>SERVICIO, CALIDAD Y EXPERIENCIA<br>EN CADA RECORRIDO</h2>
            <div class="red-line"></div>
            <p>
              Somos una plataforma creada por estudiantes para estudiantes, con el objetivo de mejorar la movilidad universitaria mediante tecnología innovadora y colaboración comunitaria.
            </p>
            <p>
              Nuestra misión es reducir el tiempo de desplazamiento, aumentar la seguridad y promover el transporte sostenible entre campus.
            </p>
          </div>
        </div>
      </section>
      
      
        <section class="vehiculos">
            <h2 class="titulo-seccion">Nuestros vehículos</h2>
            <div class="vehiculo-container">
              <div class="vehiculo-card">
                <img src="{{ url('Assets/IMG/busi.png')}}" alt="Camión Equipado">
                <h3>BUS EQUIPADO</h3>
                <p class="asientos"><i class="fas fa-chair"></i> 37 asientos</p>
                <ul>
                  <li>Paradas seguras preestablecidas</li>
                  <li>Cinturones de seguridad</li>
                  <li>Unidades climatizadas</li>
                  <li>GPS</li>
                  <li>Horarios fijos de rutas</li>
                  <li>Seguro de viajero a bordo</li>
                  <li>Conductor capacitado en seguridad vial</li>
                  <li>Unidad exclusiva de la universidad</li>
                  <li>Monitoreo de abordaje</li>
                </ul>
              </div>
          
              <div class="vehiculo-card">
                <img src="{{ url('Assets/IMG/micro.png')}}" alt="Camioneta">
                <h3>MICROBÚS</h3>
                <p class="asientos"><i class="fas fa-chair"></i> 19 asientos</p>
                <ul>
                  <li>Paradas seguras preestablecidas</li>
                  <li>Cinturones de seguridad</li>
                  <li>Unidades climatizadas</li>
                  <li>GPS</li>
                  <li>Horarios fijos de rutas</li>
                  <li>Seguro de viajero a bordo</li>
                  <li>Conductor capacitado en seguridad vial</li>
                  <li>Unidad exclusiva de la universidad</li>
                  <li>Monitoreo de abordaje</li>
                </ul>
              </div>
            </div>
          </section>

    
    <section id="informacion" class="info-section">
        <div class="container">
            <h2 class="section-title">¡Más Información Aquí!</h2>
            <div class="info-tabs">
                <div class="tab-header">
                    <div class="tab-link active" data-tab="faq">FAQ</div>
                    <div class="tab-link" data-tab="precios">Misión</div>
                    <div class="tab-link" data-tab="cobertura">Visión</div>
                </div>
                <div class="tab-content active" id="faq">
                    <div class="accordion">
                        <div class="accordion-item">
                            <div class="accordion-header">¿Cómo funciona la app? <i class="fas fa-chevron-down"></i></div>
                            <div class="accordion-content">
                                <p>La app analiza tu ubicación y destino para sugerir las mejores rutas, considerando tráfico, seguridad y transporte disponible.</p>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">¿Es gratuito? <i class="fas fa-chevron-down"></i></div>
                            <div class="accordion-content">
                                <p>El servicio básico es completamente gratuito,  beneficiando a los estudiantes de la Universidad Don Bosco.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="precios">
                    <p>Facilitar una movilidad segura, eficiente y sostenible para estudiantes universitarios mediante el uso de tecnología inteligente,
                     promoviendo la puntualidad, la comodidad y la colaboración comunitaria en cada recorrido.</p>
                </div>
                <div class="tab-content" id="cobertura">
                    <p>Ser la plataforma líder en soluciones de transporte universitario, transformando la experiencia de movilidad estudiantil
                        con innovación, responsabilidad ambiental y compromiso social en toda la región.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section id="contacto" class="contact-section">
        <div class="container">
            <h2 class="section-title">¡Contáctanos Acá!</h2>
            <div class="contact-content">
                <div class="contact-form">
                    <form>
                        <div class="form-group">
                            <input type="text" placeholder="Nombre" required>
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Tu mensaje" required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Enviar</button>
                    </form>
                </div>
                <div class="contact-info">
                    <h3>Contáctanos</h3>
                    <p><i class="fas fa-envelope"></i>infoUgo@gmail.com</p>
                    <p><i class="fas fa-phone"></i> +503 7215-6128</p>
                    <div class="social-media">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>U-Go!</h3>
                    <p>Movilidad universitaria inteligente</p>
                </div>
                <div class="footer-links">
                    <h4>Enlaces</h4>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#about">Sobre nosotros</a></li>
                        <li><a href="#beneficios">Contáctanos</a></li>
                        <li><a href="#informacion">Horarios</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Contacto</h4>
                    <p>El Salvador, San Salvador</p>
                    <p>infoUgo@gmail.com</p>
                </div>
                <div class="footer-newsletter">
                    <h4>Newsletter</h4>
                    <form>
                        <input type="email" placeholder="Tu email">
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 U-Go! - Todos los derechos reservados.</p>
                <div class="legal-links">
                    <a href="#">Términos</a>
                    <a href="#">Privacidad</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    @endauth

    <!-- Aqui empieza la parte de invitado -->

    @guest
    <section id="inicio" class="hero">
        <div class="carrusel">
            <div class="slide active">
                <img src="{{ url('Assets/IMG/bass.jpg')}}" alt="Movilidad inteligente">
            </div>
            <div class="slide active">
                <img src="{{ url('Assets/IMG/carrusel1.jpg')}}" alt="Movilidad inteligente">
            </div>
            <div class="slide active">
                <img src="{{ url('Assets/IMG/carrusel2.jpg')}}" alt="Movilidad inteligente">
            </div>
            <div class="slide active">
                <img src="{{ url('Assets/IMG/carrusel4.jpg')}}" alt="Movilidad inteligente">
            </div>

            <div class="carrusel-controls">
                <button class="prev"><i class="fas fa-chevron-left"></i></button>
                <button class="next"><i class="fas fa-chevron-right"></i></button>
            </div>
            <div class="carrusel-indicators"></div>
        </div>
        
        <div class="hero-content">
            <h1>Una forma más justa de moverte en la universidad</h1>
            <p>Rutas seguras, fáciles y en tiempo real.</p>
            <div class="app-buttons">
                <a href="#" class="btn-descarga">
                    <i class="fab fa-apple"></i> App Store
                </a>
                <a href="#" class="btn-descarga">
                    <i class="fab fa-google-play"></i> Play Store
                </a>
            </div>
        </div>
        
    </section>
    
    <section class="about-section">
        <div class="about-container">
          <div class="about-image">
            <img src="{{ url('Assets/IMG/conductor.jpg')}}" alt="Estudiante en transporte">
          </div>
          <div class="about-text">
            <h2>SERVICIO, CALIDAD Y EXPERIENCIA<br>EN CADA RECORRIDO</h2>
            <div class="red-line"></div>
            <p>
              Somos una plataforma creada por estudiantes para estudiantes, con el objetivo de mejorar la movilidad universitaria mediante tecnología innovadora y colaboración comunitaria.
            </p>
            <p>
              Nuestra misión es reducir el tiempo de desplazamiento, aumentar la seguridad y promover el transporte sostenible entre campus.
            </p>
          </div>
        </div>
      </section>
      
      
        <section class="vehiculos">
            <h2 class="titulo-seccion">Nuestros vehículos</h2>
            <div class="vehiculo-container">
              <div class="vehiculo-card">
                <img src="{{ url('Assets/IMG/busi.png')}}" alt="Camión Equipado">
                <h3>BUS EQUIPADO</h3>
                <p class="asientos"><i class="fas fa-chair"></i> 37 asientos</p>
                <ul>
                  <li>Paradas seguras preestablecidas</li>
                  <li>Cinturones de seguridad</li>
                  <li>Unidades climatizadas</li>
                  <li>GPS</li>
                  <li>Horarios fijos de rutas</li>
                  <li>Seguro de viajero a bordo</li>
                  <li>Conductor capacitado en seguridad vial</li>
                  <li>Unidad exclusiva de la universidad</li>
                  <li>Monitoreo de abordaje</li>
                </ul>
              </div>
          
              <div class="vehiculo-card">
                <img src="{{ url('Assets/IMG/micro.png')}}" alt="Camioneta">
                <h3>MICROBÚS</h3>
                <p class="asientos"><i class="fas fa-chair"></i> 19 asientos</p>
                <ul>
                  <li>Paradas seguras preestablecidas</li>
                  <li>Cinturones de seguridad</li>
                  <li>Unidades climatizadas</li>
                  <li>GPS</li>
                  <li>Horarios fijos de rutas</li>
                  <li>Seguro de viajero a bordo</li>
                  <li>Conductor capacitado en seguridad vial</li>
                  <li>Unidad exclusiva de la universidad</li>
                  <li>Monitoreo de abordaje</li>
                </ul>
              </div>
            </div>
          </section>

    
    <section id="informacion" class="info-section">
        <div class="container">
            <h2 class="section-title">¡Más Información Aquí!</h2>
            <div class="info-tabs">
                <div class="tab-header">
                    <div class="tab-link active" data-tab="faq">FAQ</div>
                    <div class="tab-link" data-tab="precios">Misión</div>
                    <div class="tab-link" data-tab="cobertura">Visión</div>
                </div>
                <div class="tab-content active" id="faq">
                    <div class="accordion">
                        <div class="accordion-item">
                            <div class="accordion-header">¿Cómo funciona la app? <i class="fas fa-chevron-down"></i></div>
                            <div class="accordion-content">
                                <p>La app analiza tu ubicación y destino para sugerir las mejores rutas, considerando tráfico, seguridad y transporte disponible.</p>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">¿Es gratuito? <i class="fas fa-chevron-down"></i></div>
                            <div class="accordion-content">
                                <p>El servicio básico es completamente gratuito,  beneficiando a los estudiantes de la Universidad Don Bosco.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="precios">
                    <p>Facilitar una movilidad segura, eficiente y sostenible para estudiantes universitarios mediante el uso de tecnología inteligente,
                     promoviendo la puntualidad, la comodidad y la colaboración comunitaria en cada recorrido.</p>
                </div>
                <div class="tab-content" id="cobertura">
                    <p>Ser la plataforma líder en soluciones de transporte universitario, transformando la experiencia de movilidad estudiantil
                        con innovación, responsabilidad ambiental y compromiso social en toda la región.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section id="contacto" class="contact-section">
        <div class="container">
            <h2 class="section-title">¡Contáctanos Acá!</h2>
            <div class="contact-content">
                <div class="contact-form">
                    <form>
                        <div class="form-group">
                            <input type="text" placeholder="Nombre" required>
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Tu mensaje" required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Enviar</button>
                    </form>
                </div>
                <div class="contact-info">
                    <h3>Contáctanos</h3>
                    <p><i class="fas fa-envelope"></i>infoUgo@gmail.com</p>
                    <p><i class="fas fa-phone"></i> +503 7215-6128</p>
                    <div class="social-media">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>U-Go!</h3>
                    <p>Movilidad universitaria inteligente</p>
                </div>
                <div class="footer-links">
                    <h4>Enlaces</h4>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#about">Sobre nosotros</a></li>
                        <li><a href="#beneficios">Contáctanos</a></li>
                        <li><a href="#informacion">Horarios</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Contacto</h4>
                    <p>El Salvador, San Salvador</p>
                    <p>infoUgo@gmail.com</p>
                </div>
                <div class="footer-newsletter">
                    <h4>Newsletter</h4>
                    <form>
                        <input type="email" placeholder="Tu email">
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 U-Go! - Todos los derechos reservados.</p>
                <div class="legal-links">
                    <a href="#">Términos</a>
                    <a href="#">Privacidad</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    
    @endguest
@endsection


