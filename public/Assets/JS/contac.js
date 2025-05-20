document.addEventListener('DOMContentLoaded', function() {
    // Datos de ejemplo para las rutas (puedes reemplazarlos con tus datos reales)
    const carouselTrack = document.querySelector('.carousel-track');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const cardWidth = 280; // Ancho de cada tarjeta + margen
    let currentPosition = 0;

    async function getRutas(){
        try {
            const response = await fetch('/datosRutas',)
            console.log(response)
            if (!response.ok){
                const errorData = await response.json().catch(()=>null)
                throw new Error(errorData?.message || `Error ${response.status}`)
            }
            
            const data = await response.json();
            return data.map(d => ({
                ...d,
                link: `/conductor/${d.conductor_id}`
            }));

        } catch (error) {
            console.error('Error:',error);  
        }   
    }   

    // Generar las tarjetas de rutas
    async function generateRouteCards() {

        const routesData = await getRutas();
        
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
                    <h3 class="route-name">${route.origen}</h3>
                    <div class="route-number">${route.horarios}</div>
                </div>
                <div class="card-body">
                    <p class="route-description">${route.descripcion}</p>
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
        moveCarousel('next'); // Para deshabilitar el botón next si es necesario

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

    // Actualizar botones al redimensionar la ventana
    window.addEventListener('resize', () => {
        moveCarousel('next');
    });

});