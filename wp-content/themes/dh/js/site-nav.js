/**
 * Mobile navigation toggle for the dh theme.
 */
(function () {
    'use strict';

    var MQ = '(max-width: 768px)';
    var OPEN_CLASS = 'is-nav-open';

    function getElements() {
        var toggle = document.querySelector('[data-dh-nav-toggle]');
        var panel = document.querySelector('[data-dh-nav-panel]');
        var nav = document.getElementById('site-navigation');

        if (!toggle || !panel || !nav) {
            return null;
        }

        return {
            toggle: toggle,
            panel: panel,
            nav: nav,
        };
    }

    function isMobile() {
        return window.matchMedia && window.matchMedia(MQ).matches;
    }

    function setOpen(els, open) {
        var next = Boolean(open);
        var label = next
            ? els.toggle.getAttribute('data-label-close') || 'Close menu'
            : els.toggle.getAttribute('data-label-open') || 'Open menu';

        els.nav.classList.toggle(OPEN_CLASS, next);
        els.toggle.setAttribute('aria-expanded', next ? 'true' : 'false');
        els.toggle.setAttribute('aria-label', label);
    }

    function close(els) {
        setOpen(els, false);
    }

    function toggle(els) {
        setOpen(els, !els.nav.classList.contains(OPEN_CLASS));
    }

    function init() {
        var els = getElements();
        if (!els) {
            return;
        }

        setOpen(els, false);

        els.toggle.addEventListener('click', function () {
            if (!isMobile()) {
                return;
            }

            toggle(els);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && els.nav.classList.contains(OPEN_CLASS)) {
                close(els);
                els.toggle.focus();
            }
        });

        document.addEventListener('click', function (event) {
            if (!els.nav.classList.contains(OPEN_CLASS) || !isMobile()) {
                return;
            }

            if (!els.nav.contains(event.target)) {
                close(els);
            }
        });

        els.panel.addEventListener('click', function (event) {
            var link = event.target.closest('a');
            if (link && isMobile()) {
                close(els);
            }
        });

        if (window.matchMedia) {
            var mediaQuery = window.matchMedia(MQ);
            var onChange = function () {
                if (!mediaQuery.matches) {
                    close(els);
                }
            };

            if (typeof mediaQuery.addEventListener === 'function') {
                mediaQuery.addEventListener('change', onChange);
            } else if (typeof mediaQuery.addListener === 'function') {
                mediaQuery.addListener(onChange);
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
