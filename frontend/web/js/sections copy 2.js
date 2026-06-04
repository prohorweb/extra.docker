document.addEventListener('DOMContentLoaded', () => {
  const carousel_scroll = document.getElementById('carousel_scroll');
  const slide_scrolls = document.querySelectorAll('.slide_scroll');
  const nextSection = document.getElementById('next_section');
  const totalSlides = slide_scrolls.length;

  if (!carousel_scroll || totalSlides === 0) {
    console.warn('carousel_scroll or slide_scroll not found');
    return;
  }

  let currentSlide = 0;
  let slideWidth = window.innerWidth;
  let accumulatedDelta = 0;
  let isThrottled = false;
  let blockSliderScroll = false;

  const scrollToSlide = (index) => {
    const target = Math.min(Math.max(index, 0), totalSlides - 1);
    carousel_scroll.scrollTo({
      left: target * slideWidth,
      behavior: 'smooth',
    });
    currentSlide = target;
  };

  // IntersectionObserver to detect if next_section is visible
  const observer = new IntersectionObserver((entries) => {
    for (const entry of entries) {
      if (entry.isIntersecting) {
        blockSliderScroll = true;
      } else {
        blockSliderScroll = false;
      }
    }
  }, {
    root: null,
    threshold: 0.01 // Adjust as needed: any visible part
  });

  observer.observe(nextSection);

  // Horizontal scroll handler
  window.addEventListener('wheel', (e) => {
    if (blockSliderScroll) return; // Do nothing if next_section is visible

    e.preventDefault(); // block page scroll

    if (!isThrottled) {
      accumulatedDelta += e.deltaY;

      while (Math.abs(accumulatedDelta) >= 100) {
        if (accumulatedDelta > 0 && currentSlide < totalSlides - 1) {
          scrollToSlide(currentSlide + 1);
        } else if (accumulatedDelta < 0 && currentSlide > 0) {
          scrollToSlide(currentSlide - 1);
        }

        accumulatedDelta = 0;
        isThrottled = true;
        setTimeout(() => {
          isThrottled = false;
        }, 600);
      }
    }
  }, { passive: false });

  // Resize fix
  window.addEventListener('resize', () => {
    slideWidth = window.innerWidth;
    scrollToSlide(currentSlide);
  });

  scrollToSlide(0);
});
