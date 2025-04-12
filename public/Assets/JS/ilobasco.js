document.addEventListener('DOMContentLoaded', function() {
    // Datos específicos de la ruta ILOBASCO
    const routeData = {
        id: 'ilobasco',
        title: "ILOBASCO",
        number: "I1",
        type: "Microbús",
        responsible: "Jasser Henríquez",
        phone: "7603-6640",
        additionalInfo: "2251-8216",
        entrySchedule: [
            { 
                time: "5:40 a.m.", 
                references: [
                    "Ilobasco",
                    "San Rafael Cedros",
                    "Universidad Don Bosco"
                ] 
            },
            { 
                time: "5:50 a.m.", 
                references: [
                    "Ilobasco",
                    "San Rafael Cedros",
                    "Universidad Don Bosco"
                ] 
            }
        ],
        exitSchedule: [
            { 
                time: "9:00 a.m. / 5:00 p.m.", 
                references: [
                    "Universidad Don Bosco",
                    "San Rafael Cedros",
                    "Ilobasco"
                ] 
            }
        ],
        mapUrl: "https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d12345.678901234567!2d-88.893722!3d13.844444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x8f6385d5b5a5a5a5%3A0x5a5a5a5a5a5a5a5!2sIlobasco!3m2!1d13.844444!2d-88.893722!4m5!1s0x8f6331e7d8f7e1a1%3A0x3a5b3e3b3b3b3b3b!2sUniversidad%20Don%20Bosco!3m2!1d13.716927!2d-89.218927!5e0!3m2!1ses!2ssv!4v1234567890123!5m2!1ses!2ssv"
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
            <p><span class="contact-label">Tipo:</span> ${route.type}</p>
            <p><span class="contact-label">Responsable:</span> ${route.responsible}</p>
            <p><span class="contact-label">Teléfono:</span> ${route.phone}</p>
            <p><span class="contact-label">Más información:</span> ${route.additionalInfo}</p>
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
        return schedule.map(item => `
            <div class="schedule-column">
                <div class="schedule-item">
                    <div class="time-label">${item.time}</div>
                    <ul class="reference-list">
                        ${item.references.map(ref => 
                            `<li><i class="fas fa-map-marker-alt"></i> ${ref}</li>`
                        ).join('')}
                    </ul>
                </div>
            </div>
        `).join('');
    }
});