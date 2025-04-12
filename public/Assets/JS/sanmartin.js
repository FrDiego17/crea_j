document.addEventListener('DOMContentLoaded', function() {
    // Datos específicos de la ruta SAN MARTÍN
    const routeData = {
        id: 'sanmartin',
        title: "SAN MARTÍN",
        number: "SM1",
        responsible: "Idalia Quinteros",
        phone: "7701-5181",
        entrySchedule: [
            { 
                time: "6:15 a.m. / 12:40 p.m.", 
                references: [
                    "Parque Central de San Martín",
                    "Desvío a Tonacatepeque",
                    "Parada Ciudad Mujer",
                    "Parada Iglesia Cristo Te Llama",
                    "Gasolinera Texaco",
                    "Unicentro Altavista",
                    "Desvío a San Bartolo",
                    "Desvío Las Cañas",
                    "Paso a desnivel Col. San José",
                    "Unicentro Soyapango",
                    "Universidad Don Bosco"
                ],
                specificTimes: [
                    "6:10 a.m.",
                    "6:18 a.m.",
                    "6:20 a.m.",
                    "6:25 a.m.",
                    "6:28 a.m.",
                    "6:30 a.m.",
                    "6:33 a.m.",
                    "6:35 a.m.",
                    "6:38 a.m.",
                    "6:40 a.m.",
                    "6:45 a.m."
                ]
            }
        ],
        exitSchedule: [
            { 
                time: "12:10, 1:30, 3:30 y 5:20 p.m.", 
                references: [
                    "Universidad Don Bosco",
                    "Unicentro Soyapango",
                    "Paso a desnivel Col. San José",
                    "Desvío a San Bartolo",
                    "Unicentro Altavista",
                    "Gasolinera Texaco",
                    "Parada Ciudad Mujer",
                    "Desvío a Tonacatepeque",
                    "Parque Central de San Martín"
                ] 
            }
        ],
        mapUrl: "https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d15505.78956746828!2d-89.218927!3d13.716927!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x8f6331e7d8f7e1a1%3A0x3a5b3e3b3b3b3b3b!2sParque%20Central%20de%20San%20Martín!3m2!1d13.716927!2d-89.218927!4m5!1s0x8f6331e7d8f7e1a1%3A0x3a5b3e3b3b3b3b3b!2sUniversidad%20Don%20Bosco!3m2!1d13.716927!2d-89.218927!5e0!3m2!1ses!2ssv!4v1234567890123!5m2!1ses!2ssv"
    };

    // Cargar datos en la página
    loadRouteData(routeData);

    // Función para cargar los datos de la ruta en la página
    function loadRouteData(route) {
        // Actualizar información básica
        document.getElementById('route-title').textContent = route.title;
        document.getElementById('route-number').textContent = route.number;
        
        // Actualizar información de contacto
        const contactInfo = document.querySelector('.contact-info');
        contactInfo.innerHTML = `
            <p><span class="contact-label">Responsable:</span> ${route.responsible}</p>
            <p><span class="contact-label">Teléfono:</span> ${route.phone}</p>
        `;
        
        // Actualizar horarios de entrada
        const entryContainer = document.querySelectorAll('.schedule-container')[0];
        entryContainer.innerHTML = generateScheduleHTML(route.entrySchedule);
        
        // Actualizar horarios de salida
        const exitContainer = document.querySelectorAll('.schedule-container')[1];
        exitContainer.innerHTML = generateScheduleHTML(route.exitSchedule);
        
        // Actualizar mapa si existe
        if (route.mapUrl) {
            const mapIframe = document.querySelector('.map-container iframe');
            mapIframe.src = route.mapUrl;
        }
    }

    // Función para generar el HTML de los horarios
    function generateScheduleHTML(schedule) {
        let columnsHTML = '';
        
        schedule.forEach(item => {
            let referencesHTML = item.references.map(ref => 
                `<li><i class="fas fa-map-marker-alt"></i> ${ref}</li>`
            ).join('');
            
            let specificTimesHTML = '';
            if (item.specificTimes) {
                specificTimesHTML = `
                    <div class="schedule-column">
                        <div class="schedule-item">
                            <div class="time-label">Horarios específicos:</div>
                            <ul class="reference-list">
                                ${item.specificTimes.map(time => 
                                    `<li><i class="fas fa-clock"></i> ${time}</li>`
                                ).join('')}
                            </ul>
                        </div>
                    </div>
                `;
            }
            
            columnsHTML += `
                <div class="schedule-column">
                    <div class="schedule-item">
                        <div class="time-label">${item.time}</div>
                        <ul class="reference-list">
                            ${referencesHTML}
                        </ul>
                    </div>
                </div>
                ${specificTimesHTML}
            `;
        });
        
        return columnsHTML;
    }
});