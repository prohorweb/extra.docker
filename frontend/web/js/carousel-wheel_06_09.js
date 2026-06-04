// Mouse wheel scrolling for Bootstrap carousel
document.addEventListener('DOMContentLoaded', function() {
    
    const carousel = document.getElementById('carouselActionsFade');
    
    if (!carousel) {
        return;
    }
    
    // Initialize Bootstrap carousel if not already initialized
    let bootstrapCarousel;
    try {
        bootstrapCarousel = new bootstrap.Carousel(carousel, { 
            interval: false, // Disable auto-sliding
            wrap: false       // Disable wrapping
        });
    } catch (e) {
        // Carousel might already be initialized
        bootstrapCarousel = bootstrap.Carousel.getInstance(carousel);
    }

    if (!bootstrapCarousel) {
        return;
    }

    let isScrolling = false;
    let currentIndex = 0;
    const totalSlides = carousel.querySelectorAll('.carousel-item').length;
    let scrollThreshold = 50; // Minimum scroll amount to trigger slide change
    let accumulatedDelta = 0;
    let isCarouselActive = false; // Track if carousel is active and blocking scroll
    let shouldNudgePage = false;
    let lastSlideNudged = false;

    // Function to check if carousel is 100% visible
    function isCarouselFullyVisible() {
        const carouselRect = carousel.getBoundingClientRect();
        const tolerance = 10;
        const isVisible = carouselRect.top >= -tolerance &&
                          carouselRect.bottom <= window.innerHeight + tolerance &&
                          carouselRect.left >= 0 &&
                          carouselRect.right <= window.innerWidth;
        return isVisible;
    }

    // Function to block/unblock page scrolling
    function setPageScrollBlock(block) {
        
        if (block) {
            const currentScrollY = window.scrollY;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
            document.body.style.top = `-${currentScrollY}px`;
            
        } else {
            const scrollY = document.body.style.top;
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.width = '';
            document.body.style.top = '';
            
            const restoreScrollY = parseInt(scrollY || '0') * -1;
            window.scrollTo(0, restoreScrollY);
            
        }
    }

    // Track current slide index
    carousel.addEventListener('slid.bs.carousel', function(event) {
        currentIndex = event.to;
        // Unblock scroll if on first or last slide
        if (currentIndex === 0 || currentIndex === totalSlides - 1) {
            if (isCarouselActive) {
                isCarouselActive = false;
                setPageScrollBlock(false);
            }
            // Set nudge flag if on last slide
            if (currentIndex === totalSlides - 1) {
                shouldNudgePage = true;
                // If not already nudged, nudge immediately (for click/arrow/indicator)
                if (!lastSlideNudged) {
                    window.scrollBy({ top: 1, behavior: 'auto' });
                    lastSlideNudged = true;
                }
            } else {
                lastSlideNudged = false; // Reset when leaving last slide
            }
        } else {
            lastSlideNudged = false; // Reset when not on last slide
        }
    });

    // Mouse wheel event handler
    window.addEventListener('wheel', function(event) {
        
        // Block page scroll when carousel is fully visible
        if (isCarouselFullyVisible() && !isCarouselActive) {
            isCarouselActive = true;
            setPageScrollBlock(true);
        } else if (!isCarouselFullyVisible() && isCarouselActive) {
            isCarouselActive = false;
            setPageScrollBlock(false);
        }

        if (!isCarouselFullyVisible() || isScrolling) {
            return;
        }

        // Prevent default scroll behavior when carousel is fully in view
        event.preventDefault();

        accumulatedDelta += event.deltaY;

        // Trigger slide change when threshold is reached
        if (Math.abs(accumulatedDelta) >= scrollThreshold) {
            
            if (accumulatedDelta > 0) {
                // Scrolling down - go to next slide
                if (currentIndex < totalSlides - 1) {
                    bootstrapCarousel.next();
                } else {
                    // On last slide, unblock scroll and allow normal page scrolling
                    isCarouselActive = false;
                    setPageScrollBlock(false);
                    return;
                }
            } else {
                // Scrolling up - go to previous slide
                if (currentIndex > 0) {
                    bootstrapCarousel.prev();
                } else {
                    // On first slide, unblock scroll and allow normal page scrolling
                    isCarouselActive = false;
                    setPageScrollBlock(false);
                    return;
                }
            }

            // Reset accumulated delta and set scrolling flag
            accumulatedDelta = 0;
            isScrolling = true;
            
            // Prevent rapid scrolling
            setTimeout(() => {
                isScrolling = false;
            }, 800); // Adjust timing as needed
        }

        // If on last slide and should nudge page, scroll page by 1px and reset flag
        if (currentIndex === totalSlides - 1 && shouldNudgePage && !lastSlideNudged) {
            window.scrollBy({ top: 1, behavior: 'auto' });
            shouldNudgePage = false;
            lastSlideNudged = true;
            return;
        }
    }, { passive: false });

    // Touch/swipe support for mobile devices
    let touchStartY = 0;
    let touchEndY = 0;
    let touchStartTime = 0;

    carousel.addEventListener('touchstart', function(e) {
        if (e.touches.length === 1) {
            touchStartY = e.touches[0].clientY;
            touchStartTime = Date.now();
        }
    }, { passive: true });

    carousel.addEventListener('touchmove', function(e) {
        if (e.touches.length === 1) {
            touchEndY = e.touches[0].clientY;
        }
    }, { passive: true });

    carousel.addEventListener('touchend', function(e) {
        if (!touchStartY || !touchEndY) return;

        const deltaY = touchStartY - touchEndY;
        const deltaTime = Date.now() - touchStartTime;
        const minSwipeDistance = 50;
        const maxSwipeTime = 300;

        // Check if it's a valid swipe gesture
        if (Math.abs(deltaY) > minSwipeDistance && deltaTime < maxSwipeTime) {
            if (deltaY > 0 && currentIndex < totalSlides - 1) {
                // Swipe up - next slide
                bootstrapCarousel.next();
            } else if (deltaY < 0 && currentIndex > 0) {
                // Swipe down - previous slide
                bootstrapCarousel.prev();
            }
        }

        // Reset touch values
        touchStartY = 0;
        touchEndY = 0;
        touchStartTime = 0;
    }, { passive: true });

    // Keyboard support (arrow keys)
    document.addEventListener('keydown', function(event) {
        // Check if carousel is 100% visible in viewport
        const isFullyVisible = isCarouselFullyVisible();

        if (!isFullyVisible) return;

        switch(event.key) {
            case 'ArrowDown':
            case 'ArrowRight':
                event.preventDefault();
                if (currentIndex < totalSlides - 1) {
                    bootstrapCarousel.next();
                }
                break;
            case 'ArrowUp':
            case 'ArrowLeft':
                event.preventDefault();
                if (currentIndex > 0) {
                    bootstrapCarousel.prev();
                }
                break;
        }
    });

    // Optional: Add visual feedback for scroll direction
    carousel.style.cursor = 'grab';
    
    carousel.addEventListener('mousedown', function() {
        carousel.style.cursor = 'grabbing';
    });
    
    carousel.addEventListener('mouseup', function() {
        carousel.style.cursor = 'grab';
    });
    
    carousel.addEventListener('mouseleave', function() {
        carousel.style.cursor = 'grab';
    });

    // Clean up scroll blocking when page is unloaded
    window.addEventListener('beforeunload', function() {
        if (isCarouselActive) {
            setPageScrollBlock(false);
        }
    });

    // Handle window resize to recheck carousel visibility
    window.addEventListener('resize', function() {
        const isFullyVisible = isCarouselFullyVisible();
        if (!isFullyVisible && isCarouselActive) {
            isCarouselActive = false;
            setPageScrollBlock(false);
        }
    });
});