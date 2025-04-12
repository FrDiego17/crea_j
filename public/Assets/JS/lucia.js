document.addEventListener('DOMContentLoaded', function() {
    // Datos específicos de la ruta SANTA LUCÍA
    const routeData = {
        id: 'santalucia',
        title: "SANTA LUCÍA",
        number: "SL1",
        type: "Microbús",
        responsible: "Fabricio Castillo",
        phone: "7475-1151",
        additionalInfo: "2251-8216",
        entrySchedule: [
            { 
                time: "6:00 a.m.", 
                references: ["Col. Sta. Lucía-Iglesia Elim"]
            },
            { 
                time: "6:10 a.m.", 
                references: ["Cárcel de Mujeres"]
            },
            { 
                time: "6:25 a.m.", 
                references: ["Liceo Azcúnaga"]
            },
            { 
                time: "6:35 a.m.", 
                references: ["Esc. María Auxiliadora"]
            },
            { 
                time: "6:40 a.m.", 
                references: ["Unicentro Soyapango"]
            },
            { 
                time: "6:45 a.m.", 
                references: ["Carretera de Oro-UDB"]
            }
        ],
        exitSchedule: [
            { 
                time: "12:00 m. y 5:20 p.m.", 
                references: [
                    "Universidad Don Bosco",
                    "Unicentro Soyapango",
                    "Esc. María Auxiliadora",
                    "Centro de Soyapango",
                    "Cárcel de Mujeres",
                    "Col. Sta. Lucía-Iglesia Elim"
                ]
            }
        ],
        mapUrl: "https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d15506.123456789012!2d-89.150987!3d13.710123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x8f6331a1a1a1a1a1%3A0x1a1a1a1a1a1a1a1!2sCol.%20Sta.%20Luc%C3%ADa-Iglesia%20Elim!3m2!1d13.710123!2d-89.150987!4m5!1s0x8f6331e7d8f7e1a1%3A0x3a5b3e3b3b3b3b3b!2sUniversidad%20Don%20Bosco!3m2!1d13.716927!2d-89.218927!5e0!3m2!1ses!2ssv!4v1234567890123!5m2!1ses!2ssv"
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
        let columnsHTML = '';
        const itemsPerColumn = Math.ceil(schedule.length / 3);
        
        for (let i = 0; i < 3; i++) {
            let itemsHTML = '';
            const startIndex = i * itemsPerColumn;
            const endIndex = Math.min(startIndex + itemsPerColumn, schedule.length);
            
            for (let j = startIndex; j < endIndex; j++) {
                const item = schedule[j];
                itemsHTML += `
                    <div class="schedule-item">
                        <div class="time-label">${item.time}</div>
                        <ul class="reference-list">
                            ${item.references.map(ref => 
                                `<li><i class="fas fa-map-marker-alt"></i> ${ref}</li>`
                            ).join('')}
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