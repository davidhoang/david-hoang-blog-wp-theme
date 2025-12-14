/**
 * Mobile Menu Toggle
 * Handles hamburger menu functionality for mobile navigation
 */
(function() {
    'use strict';

    const menuToggle = document.querySelector('.menu-toggle');
    const navigation = document.querySelector('#site-navigation');
    const navLinks = document.querySelector('.nav-links');
    const body = document.body;

    if (!menuToggle || !navigation || !navLinks) {
        return;
    }

    // Toggle menu function
    function toggleMenu() {
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
        const newState = !isExpanded;

        menuToggle.setAttribute('aria-expanded', newState);
        navigation.classList.toggle('menu-open', newState);
        body.classList.toggle('menu-open', newState);

        // Prevent body scroll when menu is open
        if (newState) {
            body.style.overflow = 'hidden';
        } else {
            body.style.overflow = '';
        }
    }

    // Close menu when clicking outside
    function closeMenuOnClickOutside(event) {
        if (navigation.classList.contains('menu-open')) {
            if (!navigation.contains(event.target) && !menuToggle.contains(event.target)) {
                toggleMenu();
            }
        }
    }

    // Close menu when clicking on a link
    function closeMenuOnLinkClick(event) {
        if (event.target.closest('.nav-links a')) {
            toggleMenu();
        }
    }

    // Close menu on escape key
    function closeMenuOnEscape(event) {
        if (event.key === 'Escape' && navigation.classList.contains('menu-open')) {
            toggleMenu();
        }
    }

    // Event listeners
    menuToggle.addEventListener('click', toggleMenu);
    document.addEventListener('click', closeMenuOnClickOutside);
    navLinks.addEventListener('click', closeMenuOnLinkClick);
    document.addEventListener('keydown', closeMenuOnEscape);

    // Close menu on window resize if it's larger than mobile breakpoint
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768 && navigation.classList.contains('menu-open')) {
                toggleMenu();
            }
        }, 250);
    });
})();
