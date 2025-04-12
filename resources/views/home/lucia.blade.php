<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruta SANTA LUCÍA - Universidad Don Bosco</title>
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
                <h1 class="route-title">SANTA LUCÍA</h1>
                <div class="route-number">SL1</div>
            </div>
            
            <div class="route-body">
                <div class="contact-info">
                    <p><span class="contact-label">Tipo:</span> Microbús</p>
                    <p><span class="contact-label">Responsable:</span> Fabricio Castillo</p>
                    <p><span class="contact-label">Teléfono:</span> 7475-1151</p>
                    <p><span class="contact-label">Más información:</span> 2251-8216</p>
                </div>

                <h2 class="section-title">Horario de Entrada ▼</h2>
                <div class="schedule-container">
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">6:00 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Col. Sta. Lucía-Iglesia Elim</li>
                            </ul>
                        </div>
                        <div class="schedule-item">
                            <div class="time-label">6:10 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Cárcel de Mujeres</li>
                            </ul>
                        </div>
                    </div>
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">6:25 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Liceo Azcúnaga</li>
                            </ul>
                        </div>
                        <div class="schedule-item">
                            <div class="time-label">6:35 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Esc. María Auxiliadora</li>
                            </ul>
                        </div>
                    </div>
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">6:40 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Unicentro Soyapango</li>
                            </ul>
                        </div>
                        <div class="schedule-item">
                            <div class="time-label">6:45 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Carretera de Oro-UDB</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <h2 class="section-title">Horario de Salida ▲</h2>
                <div class="schedule-container">
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">12:00 m. y 5:20 p.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Universidad Don Bosco</li>
                                <li><i class="fas fa-map-marker-alt"></i> Unicentro Soyapango</li>
                                <li><i class="fas fa-map-marker-alt"></i> Esc. María Auxiliadora</li>
                                <li><i class="fas fa-map-marker-alt"></i> Centro de Soyapango</li>
                                <li><i class="fas fa-map-marker-alt"></i> Cárcel de Mujeres</li>
                                <li><i class="fas fa-map-marker-alt"></i> Col. Sta. Lucía-Iglesia Elim</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d15506.123456789012!2d-89.150987!3d13.710123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x8f6331a1a1a1a1a1%3A0x1a1a1a1a1a1a1a1!2sCol.%20Sta.%20Luc%C3%ADa-Iglesia%20Elim!3m2!1d13.710123!2d-89.150987!4m5!1s0x8f6331e7d8f7e1a1%3A0x3a5b3e3b3b3b3b3b!2sUniversidad%20Don%20Bosco!3m2!1d13.716927!2d-89.218927!5e0!3m2!1ses!2ssv!4v1234567890123!5m2!1ses!2ssv" 
                            allowfullscreen="" 
                            loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="../js2/lucia.js"></script>
</body>
</html>