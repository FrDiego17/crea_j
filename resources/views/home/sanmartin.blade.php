<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruta SAN MARTÍN - Universidad Don Bosco</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ url('Assets/CSS/ruta.css')}}">
</head>
<body>
    <div class="container">
        <a href="/horario" class="back-button">
            <i class="fas fa-arrow-left"></i> Volver a todas las rutas
        </a>

        <div class="route-card">
            <div class="route-header">
                <h1 class="route-title">SAN MARTÍN</h1>
                <div class="route-number">SM1</div>
            </div>
            
            <div class="route-body">
                <div class="contact-info">
                    <p><span class="contact-label">Responsable:</span> Idalia Quinteros</p>
                    <p><span class="contact-label">Teléfono:</span> 7701-5181</p>
                </div>

                <h2 class="section-title">Horario de Entrada ▼</h2>
                <div class="schedule-container">
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">6:15 a.m. / 12:40 p.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Parque Central de San Martín</li>
                                <li><i class="fas fa-map-marker-alt"></i> Desvío a Tonacatepeque</li>
                                <li><i class="fas fa-map-marker-alt"></i> Parada Ciudad Mujer</li>
                                <li><i class="fas fa-map-marker-alt"></i> Parada Iglesia Cristo Te Llama</li>
                                <li><i class="fas fa-map-marker-alt"></i> Gasolinera Texaco</li>
                                <li><i class="fas fa-map-marker-alt"></i> Unicentro Altavista</li>
                                <li><i class="fas fa-map-marker-alt"></i> Desvío a San Bartolo</li>
                                <li><i class="fas fa-map-marker-alt"></i> Desvío Las Cañas</li>
                                <li><i class="fas fa-map-marker-alt"></i> Paso a desnivel Col. San José</li>
                                <li><i class="fas fa-map-marker-alt"></i> Unicentro Soyapango</li>
                                <li><i class="fas fa-map-marker-alt"></i> Universidad Don Bosco</li>
                            </ul>
                        </div>
                    </div>
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">Horarios específicos:</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-clock"></i> 6:10 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:18 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:20 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:25 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:28 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:30 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:33 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:35 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:38 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:40 a.m.</li>
                                <li><i class="fas fa-clock"></i> 6:45 a.m.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <h2 class="section-title">Horario de Salida ▲</h2>
                <div class="schedule-container">
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">12:10, 1:30, 3:30 y 5:20 p.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Universidad Don Bosco</li>
                                <li><i class="fas fa-map-marker-alt"></i> Unicentro Soyapango</li>
                                <li><i class="fas fa-map-marker-alt"></i> Paso a desnivel Col. San José</li>
                                <li><i class="fas fa-map-marker-alt"></i> Desvío a San Bartolo</li>
                                <li><i class="fas fa-map-marker-alt"></i> Unicentro Altavista</li>
                                <li><i class="fas fa-map-marker-alt"></i> Gasolinera Texaco</li>
                                <li><i class="fas fa-map-marker-alt"></i> Parada Ciudad Mujer</li>
                                <li><i class="fas fa-map-marker-alt"></i> Desvío a Tonacatepeque</li>
                                <li><i class="fas fa-map-marker-alt"></i> Parque Central de San Martín</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d15505.78956746828!2d-89.218927!3d13.716927!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x8f6331e7d8f7e1a1%3A0x3a5b3e3b3b3b3b3b!2sParque%20Central%20de%20San%20Martín!3m2!1d13.716927!2d-89.218927!4m5!1s0x8f6331e7d8f7e1a1%3A0x3a5b3e3b3b3b3b3b!2sUniversidad%20Don%20Bosco!3m2!1d13.716927!2d-89.218927!5e0!3m2!1ses!2ssv!4v1234567890123!5m2!1ses!2ssv" 
                            allowfullscreen="" 
                            loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="../js2/sanmartin.js"></script>
</body>
</html>