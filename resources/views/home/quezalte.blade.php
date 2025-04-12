<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruta QUEZALTEPEQUE - Universidad Don Bosco</title>
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
                <h1 class="route-title">QUEZALTEPEQUE</h1>
                <div class="route-number">Q1</div>
            </div>
            
            <div class="route-body">
                <div class="contact-info">
                    <p><span class="contact-label">Tipo:</span> Microbús</p>
                    <p><span class="contact-label">Responsable:</span> Nelson García</p>
                    <p><span class="contact-label">Teléfono:</span> 7938-4790</p>
                    <p><span class="contact-label">Más información:</span> 2251-8216</p>
                </div>

                <h2 class="section-title">Horario de Entrada ▼</h2>
                <div class="schedule-container">
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">6:00, 8:00 y 10:00 a.m. / 12:00 m. / 2:00 y 4:00 p.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Plaza Centenario</li>
                                <li><i class="fas fa-map-marker-alt"></i> Ciudad Versalles</li>
                                <li><i class="fas fa-map-marker-alt"></i> Redondel de Apopa</li>
                                <li><i class="fas fa-map-marker-alt"></i> Universidad Don Bosco</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <h2 class="section-title">Horario de Salida ▲</h2>
                <div class="schedule-container">
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">9:00 y 11:00 a.m. / 1:00, 3:00 y 5:00 p.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Universidad Don Bosco</li>
                                <li><i class="fas fa-map-marker-alt"></i> Redondel de Apopa</li>
                                <li><i class="fas fa-map-marker-alt"></i> Ciudad Versalles</li>
                                <li><i class="fas fa-map-marker-alt"></i> Quezaltepeque</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d12345.678901234567!2d-89.272345!3d13.835678!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x8f6385d5b5a5a5a5%3A0x5a5a5a5a5a5a5a5!2sQuezaltepeque!3m2!1d13.835678!2d-89.272345!4m5!1s0x8f6331e7d8f7e1a1%3A0x3a5b3e3b3b3b3b3b!2sUniversidad%20Don%20Bosco!3m2!1d13.716927!2d-89.218927!5e0!3m2!1ses!2ssv!4v1234567890123!5m2!1ses!2ssv" 
                            allowfullscreen="" 
                            loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="../js2/quezalte.js"></script>
</body>
</html>