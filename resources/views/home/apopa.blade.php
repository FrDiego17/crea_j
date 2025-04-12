<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Ruta Universitaria</title>
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
                <h1 class="route-title" id="route-title">APOPA-UDB</h1>
                <div class="route-number" id="route-number">A1</div>
            </div>
            
            <div class="route-body">
                <div class="contact-info">
                    <p><span class="contact-label">Tipo:</span> Microbús</p>
                    <p><span class="contact-label">Responsable:</span> Giovani Garcia</p>
                    <p><span class="contact-label">Teléfono:</span> 7170-7117</p>
                </div>

                <h2 class="section-title">Horario de Entrada</h2>
                <div class="schedule-container">
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">6:00 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Gasolinera Puma</li>
                            </ul>
                        </div>
                        <div class="schedule-item">
                            <div class="time-label">6:10 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Pericentro Apopa</li>
                            </ul>
                        </div>
                    </div>
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">6:15 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Plaza Mundo Apopa</li>
                            </ul>
                        </div>
                        <div class="schedule-item">
                            <div class="time-label">6:20 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Mega Paca Apopa</li>
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

                <h2 class="section-title">Horario de Salida</h2>
                <div class="schedule-container">
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">9:30 a.m. / 11:30 a.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Universidad Don Bosco</li>
                                <li><i class="fas fa-map-marker-alt"></i> Paso a desnivel de Apopa</li>
                                <li><i class="fas fa-map-marker-alt"></i> Plaza Mundo Apopa</li>
                                <li><i class="fas fa-map-marker-alt"></i> Pericentro Apopa</li>
                            </ul>
                        </div>
                    </div>
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">1:30 p.m. / 3:30 p.m. / 5:30 p.m.</div>
                            <ul class="reference-list">
                                <li><i class="fas fa-map-marker-alt"></i> Universidad Don Bosco</li>
                                <li><i class="fas fa-map-marker-alt"></i> Paso a desnivel de Apopa</li>
                                <li><i class="fas fa-map-marker-alt"></i> Plaza Mundo Apopa</li>
                                <li><i class="fas fa-map-marker-alt"></i> Pericentro Apopa</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.123456789012!2d-89.12345678901234!3d13.123456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTPCsDA3JzI0LjQiTiA4OcKwMDcnMjQuNCJX!5e0!3m2!1ses!2ssv!4v1234567890123!5m2!1ses!2ssv" 
                            allowfullscreen="" 
                            loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('Assets/JS/ruta.css')}}"></script>
</body>
</html>