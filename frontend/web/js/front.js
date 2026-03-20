window.addEventListener('scroll', function() {
    const heroContent = document.querySelector('.hero-content');
    const video = document.querySelector('header.main video');
    let scrollValue = window.scrollY;

    heroContent.style.transform = `scale(${1 + scrollValue * .05})`;
    video.style.transform = `scale(${1 + scrollValue * .0025})`;
    
});

// let currentIndex = 1;
const carousel = document.querySelector(".carousel_clubs");

if (carousel) {
    const slides = Array.from(carousel.querySelectorAll(".carousel_clubs-item"));
    const totalImages = slides.length;

    const updateSlides = () => {
        slides.forEach((slide, index) => {
            let offset = (index - currentIndex + totalImages) % totalImages;
            if (offset === 0) {
                slide.style.transform = `translateX(0) scale(1.2)`;
                slide.style.zIndex = "2";
                slide.style.opacity = "1";
            } else if (offset === 1) {
                slide.style.transform = `translateX(120%) scale(0.8)`;
                slide.style.zIndex = "1";
                slide.style.opacity = "0.75";
            } else if (offset === totalImages - 1) {
                slide.style.transform = `translateX(-120%) scale(0.8)`;
                slide.style.zIndex = "1";
                slide.style.opacity = "0.75";
            } else {
                slide.style.transform = `translateX(${offset * 150}%) scale(0.5)`;
                slide.style.opacity = "0";
            }
        });
    };

    const changeSlide = (direction) => {
        currentIndex = (currentIndex + direction + totalImages) % totalImages;
        updateSlides();
    };

    document.addEventListener("DOMContentLoaded", updateSlides);

    const prevButton = document.querySelector(".prev");
    const nextButton = document.querySelector(".next");

    if (prevButton && nextButton) {
        prevButton.addEventListener("click", () => changeSlide(-1));
        nextButton.addEventListener("click", () => changeSlide(1));
    }

    let touchStartX = 0;
    let touchEndX = 0;

    carousel.addEventListener("touchstart", (e) => {
        touchStartX = e.changedTouches[0].clientX;
    });

    carousel.addEventListener("touchend", (e) => {
        touchEndX = e.changedTouches[0].clientX;
        if (touchEndX < touchStartX) changeSlide(1);
        if (touchEndX > touchStartX) changeSlide(-1);
    });
}

document.addEventListener('DOMContentLoaded', function() {
  const btn = document.querySelector('a[href="#clubs"]');
  if (btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.getElementById('clubs');
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  }
   const btn_m = document.querySelector('a[href="#clubs-mobile"]');
  if (btn_m) {
    btn_m.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.getElementById('clubs-mobile');
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  }
});

