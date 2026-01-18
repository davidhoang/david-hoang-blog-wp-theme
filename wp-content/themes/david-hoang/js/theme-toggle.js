/**
 * Theme Toggle - Dark/Light Mode
 * Matches davidhoang-web implementation
 */
(function() {
    'use strict';

    // Get theme toggle button
    const themeToggle = document.querySelector('.theme-toggle');

    if (!themeToggle) return;

    // Get current theme
    function getTheme() {
        return document.documentElement.getAttribute('data-theme') || 'light';
    }

    // Set theme
    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);

        // Update browser theme-color
        const themeColor = theme === 'dark' ? '#111111' : '#ffffff';
        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
            metaThemeColor.setAttribute('content', themeColor);
        }

        // Dispatch custom event for other components
        document.dispatchEvent(new CustomEvent('theme-changed', {
            detail: { theme: theme }
        }));
    }

    // Toggle theme
    function toggleTheme() {
        const currentTheme = getTheme();
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
    }

    // Add click event listener
    themeToggle.addEventListener('click', toggleTheme);

    // Add keyboard support
    themeToggle.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleTheme();
        }
    });

    // Listen for system preference changes
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    mediaQuery.addEventListener('change', function(e) {
        // Only update if user hasn't set a preference
        if (!localStorage.getItem('theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });
})();
