/**
 * Reader experience enhancements for the dh theme.
 *
 * Progressive enhancement for single posts:
 *   - copy-to-clipboard buttons on code blocks
 *   - hover/focus anchor links on headings
 *   - active-section highlighting in the table of contents
 */
(function () {
    'use strict';

    var strings = window.dhReader || {};

    var COPY_ICON =
        '<svg class="dh-code__icon dh-code__icon--copy" xmlns="http://www.w3.org/2000/svg" ' +
        'width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false">' +
        '<rect x="5.5" y="5.5" width="8" height="8" rx="1.25" stroke="currentColor" stroke-width="1.4"/>' +
        '<path d="M10.5 5.5V3.75A1.25 1.25 0 0 0 9.25 2.5H3.75A1.25 1.25 0 0 0 2.5 3.75v5.5A1.25 1.25 0 0 0 3.75 10.5H5.5" ' +
        'stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    var CHECK_ICON =
        '<svg class="dh-code__icon dh-code__icon--check" xmlns="http://www.w3.org/2000/svg" ' +
        'width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false">' +
        '<path d="M3.5 8.5 6.5 11.5 12.5 4.5" stroke="currentColor" stroke-width="1.6" ' +
        'stroke-linecap="round" stroke-linejoin="round"/></svg>';

    var LINK_ICON =
        '<svg class="dh-heading-anchor__icon" xmlns="http://www.w3.org/2000/svg" ' +
        'width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false">' +
        '<path d="M6.5 9.5a2.5 2.5 0 0 0 3.6.1l2-2a2.5 2.5 0 0 0-3.5-3.5l-1 1" ' +
        'stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>' +
        '<path d="M9.5 6.5a2.5 2.5 0 0 0-3.6-.1l-2 2a2.5 2.5 0 0 0 3.5 3.5l1-1" ' +
        'stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    function label(key, fallback) {
        return strings[key] || fallback;
    }

    /**
     * Copy text to the clipboard, resolving true on success.
     */
    function copyText(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text).then(
                function () {
                    return true;
                },
                function () {
                    return legacyCopy(text);
                }
            );
        }

        return Promise.resolve(legacyCopy(text));
    }

    function legacyCopy(text) {
        var area = document.createElement('textarea');
        area.value = text;
        area.setAttribute('readonly', '');
        area.style.position = 'absolute';
        area.style.left = '-9999px';
        document.body.appendChild(area);
        area.select();

        var ok = false;
        try {
            ok = document.execCommand('copy');
        } catch (e) {
            ok = false;
        }

        document.body.removeChild(area);
        return ok;
    }

    function flash(button, addClass, text, resetLabel) {
        button.classList.add(addClass);
        if (text) {
            button.setAttribute('aria-label', text);
            button.setAttribute('title', text);
        }

        window.setTimeout(function () {
            button.classList.remove(addClass);
            if (resetLabel) {
                button.setAttribute('aria-label', resetLabel);
                button.setAttribute('title', resetLabel);
            }
        }, 1600);
    }

    function initCodeCopy() {
        var blocks = document.querySelectorAll('.entry-content pre');

        Array.prototype.forEach.call(blocks, function (pre) {
            var code = pre.querySelector('code') || pre;
            var source = code.innerText || code.textContent || '';
            if (!source.trim()) {
                return;
            }

            var wrapper = document.createElement('div');
            wrapper.className = 'dh-code';
            pre.parentNode.insertBefore(wrapper, pre);
            wrapper.appendChild(pre);

            var copyLabel = label('copyCode', 'Copy code');
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'dh-code__copy';
            button.setAttribute('aria-label', copyLabel);
            button.setAttribute('title', copyLabel);
            button.innerHTML = COPY_ICON + CHECK_ICON;
            wrapper.appendChild(button);

            button.addEventListener('click', function () {
                copyText(code.innerText || code.textContent || '').then(function (ok) {
                    if (ok) {
                        flash(button, 'is-copied', label('copied', 'Copied'), copyLabel);
                    } else {
                        button.setAttribute('aria-label', label('copyFailed', 'Copy failed'));
                        button.setAttribute('title', label('copyFailed', 'Copy failed'));
                    }
                });
            });
        });
    }

    function initHeadingAnchors() {
        var headings = document.querySelectorAll('.entry-content h2[id], .entry-content h3[id]');

        Array.prototype.forEach.call(headings, function (heading) {
            if (heading.closest('.dh-toc')) {
                return;
            }

            var linkLabel = label('copyLink', 'Copy link to this section');
            var anchor = document.createElement('a');
            anchor.className = 'dh-heading-anchor';
            anchor.href = '#' + heading.id;
            anchor.setAttribute('aria-label', linkLabel);
            anchor.setAttribute('title', linkLabel);
            anchor.innerHTML = LINK_ICON;
            heading.appendChild(anchor);

            anchor.addEventListener('click', function (event) {
                event.preventDefault();

                var url =
                    window.location.origin +
                    window.location.pathname +
                    window.location.search +
                    '#' +
                    heading.id;

                copyText(url).then(function (ok) {
                    if (history.replaceState) {
                        history.replaceState(null, '', '#' + heading.id);
                    } else {
                        window.location.hash = heading.id;
                    }

                    heading.scrollIntoView();

                    if (ok) {
                        flash(anchor, 'is-copied', label('linkCopied', 'Link copied'), linkLabel);
                    }
                });
            });
        });
    }

    function initTocScrollSpy() {
        var toc = document.querySelector('.dh-toc');
        if (!toc || !('IntersectionObserver' in window)) {
            return;
        }

        var links = {};
        var targets = [];

        Array.prototype.forEach.call(toc.querySelectorAll('.dh-toc__link'), function (link) {
            var id = decodeURIComponent((link.getAttribute('href') || '').replace(/^#/, ''));
            if (!id) {
                return;
            }

            var target = document.getElementById(id);
            if (!target) {
                return;
            }

            links[id] = link;
            targets.push(target);
        });

        if (!targets.length) {
            return;
        }

        var current = null;

        function setActive(id) {
            if (id === current || !links[id]) {
                return;
            }

            if (current && links[current]) {
                links[current].classList.remove('is-active');
            }

            links[id].classList.add('is-active');
            current = id;
        }

        var observer = new IntersectionObserver(
            function (entries) {
                var visible = entries
                    .filter(function (entry) {
                        return entry.isIntersecting;
                    })
                    .sort(function (a, b) {
                        return a.target.offsetTop - b.target.offsetTop;
                    });

                if (visible.length) {
                    setActive(visible[0].target.id);
                }
            },
            {
                rootMargin: '0px 0px -70% 0px',
                threshold: 0,
            }
        );

        targets.forEach(function (target) {
            observer.observe(target);
        });
    }

    function initReadingProgress() {
        var root = document.querySelector('.dh-reading-progress');
        var bar = root ? root.querySelector('.dh-reading-progress__bar') : null;
        var article =
            document.querySelector('article.post .entry-content') ||
            document.querySelector('article.post');

        if (!root || !bar || !article) {
            return;
        }

        root.hidden = false;

        var ticking = false;

        function update() {
            ticking = false;

            var rect = article.getBoundingClientRect();
            var articleTop = rect.top + window.scrollY;
            var articleHeight = article.offsetHeight;
            var viewport = window.innerHeight || document.documentElement.clientHeight;
            var readable = Math.max(articleHeight - viewport, 1);
            var scrolled = window.scrollY - articleTop;
            var progress = Math.min(100, Math.max(0, (scrolled / readable) * 100));

            bar.style.transform = 'scaleX(' + progress / 100 + ')';
            root.setAttribute('aria-valuenow', String(Math.round(progress)));
        }

        function onScroll() {
            if (ticking) {
                return;
            }

            ticking = true;
            window.requestAnimationFrame(update);
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);
        update();
    }

    function init() {
        initCodeCopy();
        initHeadingAnchors();
        initTocScrollSpy();
        initReadingProgress();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
