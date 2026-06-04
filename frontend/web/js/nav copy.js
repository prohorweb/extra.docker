let scrollPosition = 0; // Track scroll position manually

function navbarShrink(event) {
    const navbar = document.getElementById('mainNav');

    // Update scroll position based on wheel delta
    scrollPosition += event.deltaY;

    // Log scroll position and wheel delta
    // console.log('Scroll Position:', scrollPosition);
    // console.log('Wheel DeltaY:', event.deltaY);

    // Shrink the navbar when scrolling down
    if (scrollPosition > 50) {
        if (!navbar.classList.contains('navbar-shrink')) {
            navbar.classList.add('navbar-shrink');
            console.log('Navbar Shrinked');
        }
    } 
    // Expand the navbar when scrolling up
    else {
        if (navbar.classList.contains('navbar-shrink') && window.scrollY !== 0) {
            navbar.classList.remove('navbar-shrink');
            console.log('Navbar Expanded');
        }
    }

    // Reset scroll position and navbar class when at the top
    if (window.scrollY === 0) {
        scrollPosition = 0;
        if (navbar.classList.contains('navbar-shrink')) {
            navbar.classList.remove('navbar-shrink');
            console.log('Navbar Reset');
        }
    }
}

// Function to check scroll position on page load
function checkScrollPositionOnLoad() {
    const navbar = document.getElementById('mainNav');
    if (window.scrollY > 50) {
        navbar.classList.add('navbar-shrink');
        scrollPosition = window.scrollY; // Set initial scroll position
    }
}

// Listen for the wheel event
document.addEventListener('wheel', navbarShrink);

// Check scroll position when page loads
document.addEventListener('DOMContentLoaded', checkScrollPositionOnLoad);
