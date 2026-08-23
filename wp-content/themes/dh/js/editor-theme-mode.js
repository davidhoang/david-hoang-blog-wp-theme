/**
 * Apply the site color mode to the block editor canvas.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'dh-color-scheme';

    function getTheme() {
        try {
            var stored = localStorage.getItem(STORAGE_KEY);
            if (stored === 'light' || stored === 'dark') {
                return stored;
            }
        } catch (e) {
            // private mode / blocked storage
        }

        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }

        return 'light';
    }

    function applyThemeToDocument(doc, theme) {
        if (!doc || !doc.documentElement) {
            return;
        }

        doc.documentElement.setAttribute('data-theme', theme);
        doc.documentElement.style.colorScheme = theme;
    }

    function getCanvasDocuments() {
        var docs = [document];
        var iframes = document.querySelectorAll(
            'iframe[name="editor-canvas"], iframe.editor-canvas__iframe'
        );
        var i;

        for (i = 0; i < iframes.length; i += 1) {
            try {
                if (iframes[i].contentDocument) {
                    docs.push(iframes[i].contentDocument);
                }
            } catch (e) {
                // cross-origin canvas
            }
        }

        return docs;
    }

    function applyTheme() {
        var theme = getTheme();
        var docs = getCanvasDocuments();
        var i;

        for (i = 0; i < docs.length; i += 1) {
            applyThemeToDocument(docs[i], theme);
        }
    }

    function watchCanvasIframes() {
        var observer = new MutationObserver(function () {
            applyTheme();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    }

    applyTheme();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyTheme);
    }

    if (window.matchMedia) {
        var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        var onChange = function () {
            applyTheme();
        };

        if (typeof mediaQuery.addEventListener === 'function') {
            mediaQuery.addEventListener('change', onChange);
        } else if (typeof mediaQuery.addListener === 'function') {
            mediaQuery.addListener(onChange);
        }
    }

    window.addEventListener('storage', function (event) {
        if (event.key === STORAGE_KEY) {
            applyTheme();
        }
    });

    if (document.body) {
        watchCanvasIframes();
    } else {
        document.addEventListener('DOMContentLoaded', watchCanvasIframes);
    }
})();
