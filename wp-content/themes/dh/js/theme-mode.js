/**
 * Color mode toggle for the dh theme.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'dh-color-scheme';
    var config = window.dhThemeMode || {};
    var labels = config.labels || {};
    var themeColors = config.colors || {
        light: '#ffffff',
        dark: '#161614',
    };

    function getStoredTheme() {
        try {
            var stored = localStorage.getItem(STORAGE_KEY);
            if (stored === 'light' || stored === 'dark') {
                return stored;
            }
        } catch (e) {
            // private mode / blocked storage
        }

        return null;
    }

    function getSystemTheme() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
            ? 'dark'
            : 'light';
    }

    function getTheme() {
        return (
            document.documentElement.getAttribute('data-theme') ||
            getStoredTheme() ||
            getSystemTheme()
        );
    }

    function labelForTheme(theme) {
        if (theme === 'dark') {
            return labels.toLight || 'Switch to light mode';
        }

        return labels.toDark || 'Switch to dark mode';
    }

    function updateToggle(theme) {
        var toggle = document.querySelector('[data-dh-theme-toggle]');
        if (!toggle) {
            return;
        }

        var nextLabel = labelForTheme(theme);

        toggle.setAttribute('aria-label', nextLabel);
        toggle.setAttribute('title', nextLabel);
        toggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
    }

    function updateThemeColor(theme) {
        var meta = document.querySelector('meta[name="theme-color"]');
        if (!meta) {
            return;
        }

        meta.setAttribute('content', themeColors[theme] || themeColors.light);
    }

    function applyTheme(theme, options) {
        var next = theme === 'dark' ? 'dark' : 'light';
        var persist = !options || options.persist !== false;

        document.documentElement.setAttribute('data-theme', next);
        document.documentElement.style.colorScheme = next;
        updateThemeColor(next);
        updateToggle(next);

        if (persist) {
            try {
                localStorage.setItem(STORAGE_KEY, next);
            } catch (e) {
                // ignore
            }
        }

        document.dispatchEvent(
            new CustomEvent('dh-theme-change', {
                detail: { theme: next },
            })
        );
    }

    function toggleTheme() {
        applyTheme(getTheme() === 'dark' ? 'light' : 'dark');
    }

    function initToggle() {
        var toggle = document.querySelector('[data-dh-theme-toggle]');
        if (!toggle) {
            return;
        }

        updateToggle(getTheme());

        toggle.addEventListener('click', function () {
            toggleTheme();
        });
    }

    function initSystemListener() {
        if (!window.matchMedia) {
            return;
        }

        var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        var onChange = function (event) {
            if (getStoredTheme()) {
                return;
            }

            applyTheme(event.matches ? 'dark' : 'light', { persist: false });
        };

        if (typeof mediaQuery.addEventListener === 'function') {
            mediaQuery.addEventListener('change', onChange);
        } else if (typeof mediaQuery.addListener === 'function') {
            mediaQuery.addListener(onChange);
        }
    }

    // Boot script in <head> already set data-theme; sync UI after DOM is ready.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            applyTheme(getTheme(), { persist: Boolean(getStoredTheme()) });
            initToggle();
            initSystemListener();
        });
    } else {
        applyTheme(getTheme(), { persist: Boolean(getStoredTheme()) });
        initToggle();
        initSystemListener();
    }
})();
