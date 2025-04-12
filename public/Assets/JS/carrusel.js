document.addEventListener('DOMContentLoaded', function() {
    // Carrusel
    const slides = document.querySelectorAll('.slide');
    const indicatorsContainer = document.querySelector('.carrusel-indicators');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    let currentSlide = 0;
    let slideInterval;
    const intervalTime = 5000;
    
    // Crear indicadores
    slides.forEach((slide, index) => {
        const indicator = document.createElement('div');
        indicator.classList.add('indicator');
        if(index === 0) indicator.classList.add('active');
        indicator.addEventListener('click', () => goToSlide(index));
        indicatorsContainer.appendChild(indicator);
    });
    
    const indicators = document.querySelectorAll('.indicator');
    
    // Función para ir a un slide específico
    function goToSlide(n) {
        slides[currentSlide].classList.remove('active');
        indicators[currentSlide].classList.remove('active');
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        indicators[currentSlide].classList.add('active');
        resetInterval();
    }
    
    // Siguiente slide
    function nextSlide() {
        goToSlide(currentSlide + 1);
    }
    
    // Slide anterior
    function prevSlide() {
        goToSlide(currentSlide - 1);
    }
    
    // Resetear intervalo
    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, intervalTime);
    }
    
    // Event listeners
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);
    
    // Iniciar intervalo
    slideInterval = setInterval(nextSlide, intervalTime);
    
    // Menú responsive
    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.menu');
    
    menuToggle.addEventListener('click', () => {
        menu.classList.toggle('active');
        menuToggle.innerHTML = menu.classList.contains('active') ? 
            '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
    });
    
    // Cerrar menú al hacer clic en un enlace
    document.querySelectorAll('.menu a').forEach(link => {
        link.addEventListener('click', () => {
            menu.classList.remove('active');
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        });
    });
    
    // Sistema de tabs
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', () => {
            const tabId = link.getAttribute('data-tab');
            
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            link.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Acordeón
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        
        header.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            
            accordionItems.forEach(i => i.classList.remove('active'));
            
            if(!isActive) {
                item.classList.add('active');
            }
        });
    });
    
    // Smooth scrolling para enlaces del menú
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if(targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Efecto de aparición al hacer scroll
    const fadeElements = document.querySelectorAll('.slide-content, .about-content, .benefit-card, .info-tabs, .contact-content');
    
    function checkFade() {
        fadeElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if(elementTop < windowHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    }
    
    // Establecer estilos iniciales para los elementos con efecto fade
    fadeElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });
    
    window.addEventListener('scroll', checkFade);
    window.addEventListener('load', checkFade);
    
    // Header con efecto al hacer scroll
    const header = document.querySelector('header');
    let lastScroll = 0;
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if(currentScroll <= 0) {
            header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.5)';
            return;
        }
        
        if(currentScroll > lastScroll && currentScroll > 100) {
            // Scroll hacia abajo
            header.style.transform = 'translateY(-100%)';
            header.style.boxShadow = 'none';
        } else if(currentScroll < lastScroll && currentScroll > 100) {
            // Scroll hacia arriba
            header.style.transform = 'translateY(0)';
            header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.5)';
        }
        
        lastScroll = currentScroll;
    });
});
