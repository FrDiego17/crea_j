document.addEventListener('DOMContentLoaded', function() {
    // Obtener parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const routeId = urlParams.get('id');

    // Datos de ejemplo (en un caso real, esto vendría de una API o base de datos)
    const routesData = {
        '1': {
            title: "APOPA-UDB",
            number: "A1",
            type: "Microbús",
            responsible: "Giovani Garcia",
            phone: "7170-7117",
            entrySchedule: [
                { time: "6:00 a.m.", references: ["Gasolinera Puma"] },
                { time: "6:10 a.m.", references: ["Pericentro Apopa"] },
                { time: "6:15 a.m.", references: ["Plaza Mundo Apopa"] },
                { time: "6:20 a.m.", references: ["Mega Paca Apopa"] },
                { time: "6:45 a.m.", references: ["Carretera de Oro-UDB"] }
            ],
            exitSchedule: [
                { 
                    time: "9:30 a.m. / 11:30 a.m.", 
                    references: [
                        "Universidad Don Bosco",
                        "Paso a desnivel de Apopa",
                        "Plaza Mundo Apopa",
                        "Pericentro Apopa"
                    ] 
                },
                { 
                    time: "1:30 p.m. / 3:30 p.m. / 5:30 p.m.", 
                    references: [
                        "Universidad Don Bosco",
                        "Paso a desnivel de Apopa",
                        "Plaza Mundo Apopa",
                        "Pericentro Apopa"
                    ] 
                }
            ],
            mapUrl: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.123456789012!2d-89.12345678901234!3d13.123456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTPCsDA3JzI0LjQiTiA4OcKwMDcnMjQuNCJX!5e0!3m2!1ses!2ssv!4v1234567890123!5m2!1ses!2ssv"
        }
        // Puedes agregar más rutas aquí...
    };

    // Cargar datos de la ruta
    if (routeId && routesData[routeId]) {
        const route = routesData[routeId];
        loadRouteData(route);
    } else {
        // Usar datos de los parámetros URL si no hay coincidencia
        const routeTitle = urlParams.get('title');
        const routeNumber = urlParams.get('number');
        
        if (routeTitle) {
            document.getElementById('route-title').textContent = routeTitle;
        }
        
        if (routeNumber) {
            document.getElementById('route-number').textContent = routeNumber;
        }
    }

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
        const itemsPerColumn = Math.ceil(schedule.length / 2);
        
        for (let i = 0; i < 2; i++) {
            let itemsHTML = '';
            const startIndex = i * itemsPerColumn;
            const endIndex = Math.min(startIndex + itemsPerColumn, schedule.length);
            
            for (let j = startIndex; j < endIndex; j++) {
                const item = schedule[j];
                let referencesHTML = item.references.map(ref => 
                    `<li><i class="fas fa-map-marker-alt"></i> ${ref}</li>`
                ).join('');
                
                itemsHTML += `
                    <div class="schedule-item">
                        <div class="time-label">${item.time}</div>
                        <ul class="reference-list">
                            ${referencesHTML}
                        </ul>
                    </div>
                `;
            }
            
            columnsHTML += `
                <div class="schedule-column">
                    ${itemsHTML}
                </div>
            `;
        }
        
        return columnsHTML;
    }
});