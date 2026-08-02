/**
 * Reading font switcher for the dh theme.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'dh-reading-font';
    var config = window.dhReadingFont || {};
    var fonts = config.fonts || {};
    var defaultFont = config.defaultFont || 'editorial';
    var allowed = Object.keys(fonts);

    if (!allowed.length) {
        allowed = ['editorial', 'book', 'sans', 'mono', 'clear'];
    }

    function isAllowed(font) {
        return allowed.indexOf(font) !== -1;
    }

    function getStoredFont() {
        try {
            var stored = localStorage.getItem(STORAGE_KEY);
            if (isAllowed(stored)) {
                return stored;
            }
        } catch (e) {
            // private mode / blocked storage
        }

        return null;
    }

    function getFont() {
        var current = document.documentElement.getAttribute('data-font');
        if (isAllowed(current)) {
            return current;
        }

        return getStoredFont() || defaultFont;
    }

    function updateOptions(font) {
        var options = document.querySelectorAll('[data-dh-font-option]');
        var i;

        for (i = 0; i < options.length; i += 1) {
            var option = options[i];
            var slug = option.getAttribute('data-dh-font-option');
            var selected = slug === font;

            option.setAttribute('aria-selected', selected ? 'true' : 'false');
            option.classList.toggle('is-active', selected);
        }
    }

    function applyFont(font, options) {
        var next = isAllowed(font) ? font : defaultFont;
        var persist = !options || options.persist !== false;

        document.documentElement.setAttribute('data-font', next);
        updateOptions(next);

        if (persist) {
            try {
                localStorage.setItem(STORAGE_KEY, next);
            } catch (e) {
                // ignore
            }
        }

        document.dispatchEvent(
            new CustomEvent('dh-font-change', {
                detail: { font: next },
            })
        );
    }

    function setMenuOpen(open) {
        var root = document.querySelector('.font-switcher');
        var toggle = document.querySelector('[data-dh-font-toggle]');
        var menu = document.querySelector('[data-dh-font-menu]');

        if (!root || !toggle || !menu) {
            return;
        }

        root.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');

        if (open) {
            menu.removeAttribute('hidden');
        } else {
            menu.setAttribute('hidden', '');
        }
    }

    function isMenuOpen() {
        var toggle = document.querySelector('[data-dh-font-toggle]');
        return toggle && toggle.getAttribute('aria-expanded') === 'true';
    }

    function initSwitcher() {
        var root = document.querySelector('.font-switcher');
        var toggle = document.querySelector('[data-dh-font-toggle]');
        var menu = document.querySelector('[data-dh-font-menu]');

        if (!root || !toggle || !menu) {
            return;
        }

        updateOptions(getFont());

        toggle.addEventListener('click', function (event) {
            event.stopPropagation();
            setMenuOpen(!isMenuOpen());
        });

        menu.addEventListener('click', function (event) {
            var option = event.target.closest('[data-dh-font-option]');
            if (!option || !menu.contains(option)) {
                return;
            }

            applyFont(option.getAttribute('data-dh-font-option'));
            setMenuOpen(false);
            toggle.focus();
        });

        document.addEventListener('click', function (event) {
            if (!isMenuOpen()) {
                return;
            }

            if (!root.contains(event.target)) {
                setMenuOpen(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && isMenuOpen()) {
                setMenuOpen(false);
                toggle.focus();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            applyFont(getFont(), { persist: Boolean(getStoredFont()) });
            initSwitcher();
        });
    } else {
        applyFont(getFont(), { persist: Boolean(getStoredFont()) });
        initSwitcher();
    }
})();
