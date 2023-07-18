const carouselSection = document.querySelector('.carousel-section');
const carousel = carouselSection.querySelector('.carousel');
const images = carouselSection.querySelectorAll('.carousel-image');

let currentIndex = 0;
let interval;

function showImage(index) {
  carousel.style.transform = `translateX(-${index * 100}%)`;
}

function startCarousel() {
  interval = setInterval(() => {
    currentIndex = (currentIndex + 1) % images.length;
    showImage(currentIndex);
  }, 2000);
}

function stopCarousel() {
  clearInterval(interval);
}

carouselSection.addEventListener('mouseenter', stopCarousel);
carouselSection.addEventListener('mouseleave', startCarousel);

showImage(currentIndex);
startCarousel();
