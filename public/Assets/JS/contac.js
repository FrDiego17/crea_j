document.addEventListener('DOMContentLoaded', function() {
    // Datos de ejemplo para las rutas (puedes reemplazarlos con tus datos reales)
    const routesData = [
        {
            id: 1,
            name: "Ruta Apopa",
            number: "A1",
            description: "Puntos de encuentro en las zonas mas seguras y accesibles de la zona de Apopa",
            schedule: ["6:00 a.m", "6:10 a.m", "6:15 a.m", "6:20 a.m", "6:45 a.m", " 9:30 a.m ", "11:30 a.m", "1:30p.m", "3:30 p.m" , "5:30 p.m"],
            link: "/apopa",
        },
        {
            id: 2,
            name: "Ruta Cojutepeque",
            number: "B2",
            description: "Recorre paradas desde el Parque Central Hasta el campus de la UDB",
            schedule: ["5:50 a.m ","07:15 a.m ", "8:00 a.m ", "10:00 a.m.", "12:00 m", "2:00 p.m.", "3:20 p.m", "5:20 p.m", "7:00 p.m"],
            link: "/cojute",
        },
        {
            id: 3,
            name: "Ruta Ilobasco",
            number: "C3",
            description: "Cubre la parte central de Ilobasco , San Rafael Cedros hasta el campus de la UDB",
            schedule: [" 5:40 a.m", " 5:50 a.m", "9:00 a.m", "5:00 p.m"],
            link: "/ilobasco",
        },
        {
            id: 4,
            name: "Ruta San Martin",
            number: "D4",
            description: "Servicio de transporte matutino que recorre completamente puntos especificos, seguros y accesibles",
            schedule: ["6:10 a.m", " 6:25 a.m", "6:35 a.m", "6:45 a.m", "3:30 p.m"],
            link: "/sanmartin",
        },
        {
            id: 5,
            name: "Ruta Santa Lucia",
            number: "E5",
            description: "Cubre en su totalidad puntos de encuentro seguros en la zona",
            schedule: [" 6:00 a.m", "6:25 a.m", " 6:40 a.m", "6:45 a.m", " 12:00 m", "5:20 p.m"],
            link: "/lucia",
        },
        {
            id: 6,
            name: "Ruta Quezaltepeque",
            number: "E5",
            description: "Servicio especial durante periodos de exámenes con horario extendido.",
            schedule: ["6:00 a.m", "8:00 a.m", "10:00 a.m", "12:00 m", "2:00 p.m", "4:00 p.m"],
            link: "/quezalte",
        }
    ];

    const carouselTrack = document.querySelector('.carousel-track');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const cardWidth = 280; // Ancho de cada tarjeta + margen
    let currentPosition = 0;

    // Generar las tarjetas de rutas
    function generateRouteCards() {
        carouselTrack.innerHTML = '';
        
        routesData.forEach(route => {
            const card = document.createElement('div');
            card.className = 'route-card';
            
            // Formatear los horarios
            const scheduleHTML = route.schedule.map(time => 
                `<span class="time-badge">${time}</span>`
            ).join('');
            
            card.innerHTML = `
                <div class="card-header">
                    <h3 class="route-name">${route.name}</h3>
                    <div class="route-number">${route.number}</div>
                </div>
                <div class="card-body">
                    <p class="route-description">${route.description}</p>
                    <div class="schedule">
                        <div class="schedule-title">Horarios:</div>
                        <div class="schedule-times">
                            ${scheduleHTML}
                        </div>
                    </div>
                </div>
              <div class="card-footer">
                <a href="${route.link}">Ver más detalles</a>
    </div>
`;
            
            carouselTrack.appendChild(card);
        });
    }

    // Mover el carrusel
    function moveCarousel(direction) {
        const trackWidth = carouselTrack.scrollWidth;
        const visibleWidth = carouselTrack.parentElement.offsetWidth;
        const maxPosition = visibleWidth - trackWidth;
        
        if (direction === 'prev') {
            currentPosition = Math.min(currentPosition + cardWidth * 2, 0);
        } else {
            currentPosition = Math.max(currentPosition - cardWidth * 2, maxPosition);
        }
        
        carouselTrack.style.transform = `translateX(${currentPosition}px)`;
        
        // Actualizar estado de los botones
        prevBtn.disabled = currentPosition === 0;
        nextBtn.disabled = currentPosition <= maxPosition + 10; // Margen de error
    }

    // Event listeners
    prevBtn.addEventListener('click', () => moveCarousel('prev'));
    nextBtn.addEventListener('click', () => moveCarousel('next'));

    // Inicializar
    generateRouteCards();
    moveCarousel('next'); // Para deshabilitar el botón next si es necesario

    // Actualizar botones al redimensionar la ventana
    window.addEventListener('resize', () => {
        moveCarousel('next');
    });

});