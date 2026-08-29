/* ==========================================
   ExtraSport Theme - JavaScript
   ========================================== */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeTheme();
    });

    /**
     * Initialize theme functionality
     */
    function initializeTheme() {
        setupNavigation();
        setupFilters();
        setupMobileMenu();
    }

    /**
     * Setup navigation
     */
    function setupNavigation() {
        var navigation = document.querySelector('.main-navigation');
        if (!navigation) return;

        var links = navigation.querySelectorAll('a');
        links.forEach(function(link) {
            if (link.href === window.location.href) {
                link.classList.add('current');
            }
        });
    }

    /**
     * Setup filter buttons
     */
    function setupFilters() {
        var filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                filterButtons.forEach(function(btn) {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    }

    /**
     * Setup mobile menu toggle
     */
    function setupMobileMenu() {
        // Add mobile menu toggle functionality if needed
    }

    /**
     * Utility: Add class to element
     */
    function addClass(el, className) {
        if (el.classList) {
            el.classList.add(className);
        }
    }

    /**
     * Utility: Remove class from element
     */
    function removeClass(el, className) {
        if (el.classList) {
            el.classList.remove(className);
        }
    }

    /**
     * Utility: Toggle class on element
     */
    function toggleClass(el, className) {
        if (el.classList) {
            el.classList.toggle(className);
        }
    }

})();
