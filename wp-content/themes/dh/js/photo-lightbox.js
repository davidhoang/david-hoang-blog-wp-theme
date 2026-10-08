/**
 * Progressive enhancement: full-size photo preview in a dialog.
 */
(function () {
    'use strict';

    var root = document.querySelector('[data-dh-photo-lightbox]');

    if (!root) {
        return;
    }

    var dialog = root.querySelector('.photo-lightbox__dialog');
    var image = root.querySelector('.photo-lightbox__image');
    var title = root.querySelector('.photo-lightbox__title');
    var permalink = root.querySelector('[data-dh-lightbox-permalink]');
    var lastFocus = null;

    function openFromTrigger(trigger) {
        var src = trigger.getAttribute('data-dh-lightbox-src');
        var alt = trigger.getAttribute('data-dh-lightbox-alt') || '';
        var caption = trigger.getAttribute('data-dh-lightbox-caption') || '';
        var href = trigger.getAttribute('data-dh-lightbox-permalink') || '';

        if (!src || !image) {
            return;
        }

        lastFocus = document.activeElement instanceof HTMLElement ? document.activeElement : null;

        image.src = src;
        image.alt = alt;

        if (title) {
            title.textContent = caption;
            title.hidden = '' === caption;
        }

        if (permalink) {
            if (href) {
                permalink.href = href;
                permalink.hidden = false;
            } else {
                permalink.hidden = true;
            }
        }

        root.hidden = false;
        document.documentElement.classList.add('has-photo-lightbox');

        var closeButton = root.querySelector('.photo-lightbox__close');

        if (closeButton instanceof HTMLElement) {
            closeButton.focus();
        }
    }

    function closeLightbox() {
        root.hidden = true;
        document.documentElement.classList.remove('has-photo-lightbox');

        if (image) {
            image.removeAttribute('src');
            image.alt = '';
        }

        if (lastFocus instanceof HTMLElement) {
            lastFocus.focus();
        }
    }

    document.addEventListener('click', function (event) {
        var target = event.target;

        if (!(target instanceof Element)) {
            return;
        }

        var openTrigger = target.closest('[data-dh-lightbox-open]');

        if (openTrigger) {
            event.preventDefault();
            openFromTrigger(openTrigger);
            return;
        }

        if (target.closest('[data-dh-lightbox-close]')) {
            event.preventDefault();
            closeLightbox();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (root.hidden) {
            return;
        }

        if ('Escape' === event.key) {
            event.preventDefault();
            closeLightbox();
        }

        if ('Tab' === event.key && dialog instanceof HTMLElement) {
            var focusable = dialog.querySelectorAll(
                'button:not([disabled]), a[href], [tabindex]:not([tabindex="-1"])'
            );

            if (!focusable.length) {
                return;
            }

            var first = focusable[0];
            var last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
})();
