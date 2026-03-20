window.addEventListener('scroll', function () {
    const parallaxSection = document.getElementById('carouselActionsFade'); 
    // Get the scroll position
    const scrollPosition = window.scrollY;
    // Apply both translateY and scale effects
    parallaxSection.style.transform = `translateY(${scrollPosition * 0.5}px)`;


});

window.addEventListener('scroll', function () {
    const scrollPosition = window.scrollY;
   

    const parallaxSectionActions = document.getElementById('actions');
    if (parallaxSectionActions) {
      // Adjust the 0.5 value for more/less parallax effect
      parallaxSectionActions.style.backgroundPositionY = - (scrollPosition * 0.1) + 'px';
    }
});
