let bootstrapCarousel = new bootstrap.Carousel(document.getElementById('myCarousel'), { interval: false });
let isScrolling = false;
let currentIndex = 0;
const totalSlides = document.querySelectorAll(".carousel-item").length;
let firstScrollUp = true; // Flag to track the first scroll up
const contentSection = document.getElementById('contentSection');

// Track Bootstrap carousel slide event
document.getElementById('myCarousel').addEventListener('slid.bs.carousel', function (event) {
    currentIndex = event.to;
});

window.addEventListener("wheel", function (event) {
    if (!isScrolling) {
        const isAtTop = window.scrollY === 0;
        const isAtContentTop = isContentSectionAtTop() && isContentSectionScrolledToTop();

        if (event.deltaY > 0) { 
            // Scrolling down
            if (currentIndex < totalSlides - 1) {
                bootstrapCarousel.next();
            } else if (currentIndex === totalSlides - 1) {
                // After the last slide, scroll to content
                setTimeout(scrollToContent, 500);
            }
            firstScrollUp = true; // Reset flag when scrolling down
        } else { 
            // Scrolling up
            if (isAtContentTop) {
                scrollToTop();
            } else if (isAtTop) {
                if (firstScrollUp) {
                    firstScrollUp = false; // Ignore first scroll up
                } else if (currentIndex > 0) {
                    bootstrapCarousel.prev(); // Move to previous slide
                    firstScrollUp = true; // Reset flag
                }
            }
        }

        isScrolling = true;
        setTimeout(() => isScrolling = false, 500); // Prevent fast scrolling
    }
});

// Functions to check scroll position
function isContentSectionAtTop() {
    return contentSection.getBoundingClientRect().top === 0;
}

function isContentSectionScrolledToTop() {
    return contentSection.scrollTop === 0;
}

function scrollToContent() {
    window.scrollTo({ top: contentSection.offsetTop, behavior: "smooth" });
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: "smooth" });
}
