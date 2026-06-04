document.addEventListener("DOMContentLoaded", function () {
  const myCarousel = document.getElementById('carouselSlider');
  const carousel = new bootstrap.Carousel(myCarousel, {
    interval: false,
    wrap: true // Enable looping if needed
  });

  let isSliding = false;
  let accumulatedScroll = 0;
  const SCROLL_THRESHOLD = 100;

  // Helper function to get the current active slide index
  function getCurrentIndex() {
    const carouselItems = myCarousel.querySelectorAll('.carousel-item');
    return [...carouselItems].findIndex(item => item.classList.contains('active'));
  }

  // Function to block the page scroll
  function blockPageScroll() {
    document.documentElement.style.overflow = 'hidden'; // Block page scroll
  }

  // Function to unblock the page scroll
  function unblockPageScroll() {
    document.documentElement.style.overflow = ''; // Restore default overflow
  }

  // Event listener for wheel scroll
  myCarousel.addEventListener('wheel', function (event) {
    // If the carousel is currently sliding, prevent scroll input
    if (isSliding) return;

    // Block page scroll while interacting with the carousel
    blockPageScroll();

    // Prevent default scroll behavior (page scroll)
    event.preventDefault();

    // Accumulate the scroll distance
    accumulatedScroll += event.deltaY;

    // If the accumulated scroll exceeds the threshold (100px), change the slide
    if (Math.abs(accumulatedScroll) >= SCROLL_THRESHOLD) {
      isSliding = true;

      // If scrolling down (deltaY > 0), go to the next slide; otherwise, go to the previous slide
      if (accumulatedScroll > 0) {
        carousel.next();
      } else {
        carousel.prev();
      }

      // Reset the accumulated scroll after handling the slide
      accumulatedScroll = 0;
    }
  }, { passive: false });

  // Reset isSliding flag when the slide transition is complete
  myCarousel.addEventListener('slid.bs.carousel', function () {
    isSliding = false;
    unblockPageScroll(); // Unblock page scroll after transition completes
  });
});
