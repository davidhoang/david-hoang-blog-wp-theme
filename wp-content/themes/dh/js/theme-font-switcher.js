/**
 * Reading font switcher for the dh theme.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'dh-reading-font';
    var SIZE_STORAGE_KEY = 'dh-reading-size';
    var allowedSizes = ['small', 'medium', 'large'];
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

    function getStoredSize() {
        try {
            var stored = localStorage.getItem(SIZE_STORAGE_KEY);
            if (allowedSizes.indexOf(stored) !== -1) {
                return stored;
            }
        } catch (e) {
            // private mode / blocked storage
        }

        return null;
    }

    function getSize() {
        var current = document.documentElement.getAttribute('data-reading-size');
        return allowedSizes.indexOf(current) !== -1 ? current : getStoredSize() || 'medium';
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

    function applySize(size, options) {
        var next = allowedSizes.indexOf(size) !== -1 ? size : 'medium';
        var persist = !options || options.persist !== false;
        var controls = document.querySelectorAll('[data-dh-reading-size]');
        var i;

        document.documentElement.setAttribute('data-reading-size', next);

        for (i = 0; i < controls.length; i += 1) {
            controls[i].setAttribute(
                'aria-pressed',
                controls[i].getAttribute('data-dh-reading-size') === next ? 'true' : 'false'
            );
        }

        if (persist) {
            try {
                localStorage.setItem(SIZE_STORAGE_KEY, next);
            } catch (e) {
                // ignore
            }
        }
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
            focusSelectedOption(menu);
        } else {
            menu.setAttribute('hidden', '');
        }
    }

    function getOptions(menu) {
        return Array.prototype.slice.call(menu.querySelectorAll('[data-dh-font-option]'));
    }

    function focusSelectedOption(menu) {
        var options = getOptions(menu);
        var selected =
            menu.querySelector('[data-dh-font-option][aria-selected="true"]') || options[0];

        if (selected) {
            selected.focus();
        }
    }

    function moveOptionFocus(menu, current, delta) {
        var options = getOptions(menu);

        if (!options.length) {
            return;
        }

        var index = options.indexOf(current);
        if (index === -1) {
            index = 0;
        }

        var nextIndex = (index + delta + options.length) % options.length;
        options[nextIndex].focus();
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
            var size = event.target.closest('[data-dh-reading-size]');
            if (size && menu.contains(size)) {
                applySize(size.getAttribute('data-dh-reading-size'));
                return;
            }

            var option = event.target.closest('[data-dh-font-option]');
            if (!option || !menu.contains(option)) {
                return;
            }

            applyFont(option.getAttribute('data-dh-font-option'));
            setMenuOpen(false);
            toggle.focus();
        });

        menu.addEventListener('keydown', function (event) {
            if (!isMenuOpen()) {
                return;
            }

            var option = event.target.closest('[data-dh-font-option]');

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                moveOptionFocus(menu, option, 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                moveOptionFocus(menu, option, -1);
            } else if (event.key === 'Home') {
                event.preventDefault();
                getOptions(menu)[0].focus();
            } else if (event.key === 'End') {
                event.preventDefault();
                var options = getOptions(menu);
                options[options.length - 1].focus();
            } else if (event.key === 'Enter' || event.key === ' ') {
                if (option) {
                    event.preventDefault();
                    applyFont(option.getAttribute('data-dh-font-option'));
                    setMenuOpen(false);
                    toggle.focus();
                }
            }
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
            applySize(getSize(), { persist: Boolean(getStoredSize()) });
            initSwitcher();
        });
    } else {
        applyFont(getFont(), { persist: Boolean(getStoredFont()) });
        applySize(getSize(), { persist: Boolean(getStoredSize()) });
        initSwitcher();
    }
})();
