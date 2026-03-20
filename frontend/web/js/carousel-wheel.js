// Mouse wheel scrolling for Bootstrap carousel
document.addEventListener('DOMContentLoaded', function() {
    // Defer heavy logic until carousel is in viewport
    const carousel = document.getElementById('carouselActionsFade');
    if (!carousel) {
        console.warn('❌ Carousel with ID "carouselActionsFade" not found');
        return;
    }
    // Use IntersectionObserver for lazy initialization
    const initCarousel = () => {
        console.log('🚀 Carousel wheel script loaded');
        
        // Initialize Bootstrap carousel if not already initialized
        let bootstrapCarousel;
        try {
            bootstrapCarousel = new bootstrap.Carousel(carousel, { 
                interval: false, // Disable auto-sliding
                wrap: true       // Enable wrapping
            });
            console.log('✅ Bootstrap carousel initialized');
        } catch (e) {
            // Carousel might already be initialized
            bootstrapCarousel = bootstrap.Carousel.getInstance(carousel);
            console.log('✅ Bootstrap carousel already initialized:', bootstrapCarousel);
        }

        if (!bootstrapCarousel) {
            console.warn('❌ Failed to initialize Bootstrap carousel');
            return;
        }

        let isScrolling = false;
        let currentIndex = 0;
        const totalSlides = carousel.querySelectorAll('.carousel-item').length;
        let scrollThreshold = 5; // Lower threshold for faster slide change
        let accumulatedDelta = 0;
        let isCarouselActive = false; // Track if carousel is active and blocking scroll
        let shouldNudgePage = false;
        let lastSlideNudged = false;

        console.log('📊 Carousel stats:', {
            totalSlides: totalSlides,
            scrollThreshold: scrollThreshold
        });

        // Function to check if carousel is 100% visible
        function isCarouselFullyVisible() {
            const carouselRect = carousel.getBoundingClientRect();
            const tolerance = 30; // px
            const fullyVisible = (carouselRect.top >= -tolerance) && (carouselRect.bottom <= window.innerHeight + tolerance);
            console.log('👁️ Tolerant visibility check:', {
                top: carouselRect.top,
                bottom: carouselRect.bottom,
                windowHeight: window.innerHeight,
                fullyVisible: fullyVisible
            });
            return fullyVisible;
        }

        // Function to block/unblock page scrolling
        function setPageScrollBlock(block) {
            console.log('🔒 Setting page scroll block:', block);
            const carousel = document.getElementById('carouselActionsFade');
            if (carousel) {
                // Always remove transform to avoid stuck state
                carousel.style.transform = '';
            }
            if (block) {
                // Save scroll position
                const scrollY = window.scrollY;
                document.body.dataset.scrollY = scrollY;
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
                // document.body.style.top = `-${scrollY}px`;
                console.log('🚫 Page scroll blocked:', {
                    overflow: document.body.style.overflow,
                    position: document.body.style.position,
                    width: document.body.style.width,
                    top: document.body.style.top,
                    originalScrollY: scrollY
                });
            } else {
                // Restore scroll position
                const scrollY = document.body.dataset.scrollY || '0';
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
                document.body.style.top = '';
                delete document.body.dataset.scrollY;
                window.scrollTo(0, parseInt(scrollY, 10));
                // Page scroll unblocked log removed as requested
            }
        }

        // Track current slide index
        carousel.addEventListener('slid.bs.carousel', function(event) {
            currentIndex = event.to;
            console.log('🔄 Carousel slide changed:', {
                from: event.from,
                to: event.to,
                currentIndex: currentIndex
            });
            // Unblock scroll if on first or last slide
            if (currentIndex === 0 || currentIndex === totalSlides - 1) {
                if (isCarouselActive) {
                    console.log('🏁 Unblocking scroll on first/last slide (via arrow/indicator/keyboard)');
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
                        console.log('⬇️ Nudged page by 1px on last slide (immediate from click/arrow/indicator)');
                    }
                    console.log('🟢 Will nudge page on next wheel event');
                } else {
                    lastSlideNudged = false; // Reset when leaving last slide
                }
            } else {
                lastSlideNudged = false; // Reset when not on last slide
            }
        });

        // Mouse wheel event handler
        window.addEventListener('wheel', function(event) {
            const isFullyVisible = isCarouselFullyVisible();
            console.log('🖱️ Wheel event:', {
                deltaY: event.deltaY,
                isFullyVisible: isFullyVisible,
                isCarouselActive: isCarouselActive,
                isScrolling: isScrolling,
                currentIndex: currentIndex,
                zoom: (window.devicePixelRatio * 100).toFixed(0) + '%'
            });
            // Only block scroll if carousel is truly visible and not zoom gesture
            if (isFullyVisible && !isCarouselActive) {
                console.log('🎯 Activating carousel scroll blocking');
                isCarouselActive = true;
                setPageScrollBlock(true);
            } else if (!isFullyVisible && isCarouselActive) {
                console.log('🔓 Deactivating carousel scroll blocking');
                isCarouselActive = false;
                setPageScrollBlock(false);
            }
            // If not fully visible or scrolling, skip
            if (!isFullyVisible || isScrolling) {
                console.log('⏭️ Skipping carousel interaction:', {
                    reason: !isFullyVisible ? 'not fully visible' : 'already scrolling'
                });
                return;
            }
            // Allow browser zoom (pinch or Ctrl+wheel)
            if (event.ctrlKey) {
                console.log('🔎 Allowing browser zoom');
                return;
            }
            // Prevent default scroll behavior when carousel is fully in view
            event.preventDefault();
            console.log('🚫 Prevented default wheel behavior');
            accumulatedDelta += event.deltaY;
            console.log('📈 Accumulated delta:', accumulatedDelta);
            // Trigger slide change when threshold is reached
            if (Math.abs(accumulatedDelta) >= scrollThreshold) {
                console.log('🎯 Threshold reached, changing slide');
                if (accumulatedDelta > 0) {
                    // Scrolling down - go to next slide
                    if (currentIndex < totalSlides - 1) {
                        console.log('⬇️ Going to next slide');
                        bootstrapCarousel.next();
                    } else {
                        // On last slide, unblock scroll and allow normal page scrolling
                        console.log('🏁 Last slide reached, unblocking scroll');
                        isCarouselActive = false;
                        setPageScrollBlock(false);
                        window.scrollBy({ top: 50, behavior: 'smooth' }); // Nudge page down
                        return;
                    }
                } else {
                    // Scrolling up - go to previous slide
                    if (currentIndex > 0) {
                        console.log('⬆️ Going to previous slide');
                        bootstrapCarousel.prev();
                    } else {
                        // On first slide, unblock scroll and allow normal page scrolling
                        console.log('🏁 First slide reached, unblocking scroll');
                        isCarouselActive = false;
                        setPageScrollBlock(false);
                        return;
                    }
                }
                // Reset accumulated delta and set scrolling flag
                accumulatedDelta = 0;
                isScrolling = true;
                console.log('⏱️ Setting scroll cooldown');
                // Prevent rapid scrolling
                setTimeout(() => {
                    isScrolling = false;
                    console.log('✅ Scroll cooldown finished');
                }, 300); // Shorter cooldown for faster response
            }
            // If on last slide and should nudge page, scroll page by 1px and reset flag
            if (currentIndex === totalSlides - 1 && shouldNudgePage && !lastSlideNudged) {
                window.scrollBy({ top: 10, behavior: 'auto' });
                shouldNudgePage = false;
                lastSlideNudged = true;
                console.log('⬇️ Nudged page by 1px on last slide (from wheel)');
                return;
            }
        }, { passive: false });


        // Clean up scroll blocking when page is unloaded
        window.addEventListener('beforeunload', function() {
            if (isCarouselActive) {
                console.log('🧹 Cleaning up scroll blocking on page unload');
                setPageScrollBlock(false);
            }
        });

        // Handle window resize to recheck carousel visibility
        window.addEventListener('resize', function() {
            console.log('📏 Window resized');
            const isFullyVisible = isCarouselFullyVisible();
            if (!isFullyVisible && isCarouselActive) {
                console.log('🔓 Window resize - deactivating carousel scroll blocking');
                isCarouselActive = false;
                setPageScrollBlock(false);
            }
        });

        console.log('🎉 Carousel wheel script setup complete');
    };
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    initCarousel();
                    obs.disconnect();
                }
            });
        }, { threshold: 0.5 }); // Start when at least 50% visible
        observer.observe(carousel);
    } else {
        // Fallback for old browsers
        initCarousel();
    }
});