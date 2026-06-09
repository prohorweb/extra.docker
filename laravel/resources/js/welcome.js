let currentIndex = 0;

window.addEventListener('scroll', function () {
    const heroContent = document.querySelector('.hero-content');
    const video = document.querySelector('header video');
    if (!heroContent || !video) return;

    const scrollValue = window.scrollY;
    heroContent.style.transform = `scale(${1 + scrollValue * 0.05})`;
    video.style.transform = `scale(${1 + scrollValue * 0.0025})`;
});

const carousel = document.querySelector('.carousel_clubs');

if (carousel) {
    const slides = Array.from(carousel.querySelectorAll('.carousel_clubs-item'));
    const totalImages = slides.length;

    const updateSlides = () => {
        slides.forEach((slide, index) => {
            const offset = (index - currentIndex + totalImages) % totalImages;
            if (offset === 0) {
                slide.style.transform = 'translateX(0) scale(1.2)';
                slide.style.zIndex = '2';
                slide.style.opacity = '1';
            } else if (offset === 1) {
                slide.style.transform = 'translateX(120%) scale(0.8)';
                slide.style.zIndex = '1';
                slide.style.opacity = '0.75';
            } else if (offset === totalImages - 1) {
                slide.style.transform = 'translateX(-120%) scale(0.8)';
                slide.style.zIndex = '1';
                slide.style.opacity = '0.75';
            } else {
                slide.style.transform = `translateX(${offset * 150}%) scale(0.5)`;
                slide.style.opacity = '0';
            }
        });
    };

    const changeSlide = (direction) => {
        currentIndex = (currentIndex + direction + totalImages) % totalImages;
        updateSlides();
    };

    document.addEventListener('DOMContentLoaded', updateSlides);

    const container = carousel.closest('[id]');
    const prevButton = container?.querySelector('.prev');
    const nextButton = container?.querySelector('.next');

    if (prevButton && nextButton) {
        prevButton.addEventListener('click', () => changeSlide(-1));
        nextButton.addEventListener('click', () => changeSlide(1));
    }

    let touchStartX = 0;

    carousel.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].clientX;
    });

    carousel.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].clientX;
        if (touchEndX < touchStartX) changeSlide(1);
        if (touchEndX > touchStartX) changeSlide(-1);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('a[href="#clubs"], a[href="#clubs-mobile"]').forEach((btn) => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').slice(1);
            const target = document.getElementById(targetId);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
