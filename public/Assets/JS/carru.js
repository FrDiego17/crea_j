const carouselImages = document.querySelector('.carousel-images');  
const images = document.querySelectorAll('.carousel-images img');  
let currentIndex = 0;  

function showNextImage() {  
    currentIndex = (currentIndex + 1) % images.length;  
    const offset = -currentIndex * 100;  
    carouselImages.style.transform = `translateX(${offset}%)`;  
}  

setInterval(showNextImage, 3000); // Cambia la imagen cada 3 segundos  

document.getElementById('join-button').addEventListener('click', () => {  
    alert('Join Now button clicked!');  
});  

document.getElementById('buy-button').addEventListener('click', () => {  
    alert('Buy Online button clicked!');  
});  