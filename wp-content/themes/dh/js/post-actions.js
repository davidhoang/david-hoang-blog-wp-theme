/**
 * Progressive enhancement for post sharing controls.
 */
(function () {
    'use strict';

    var strings = window.dhPostActions || {};
    var nativeShare = document.querySelector('[data-dh-native-share]');
    var copyLink = document.querySelector('[data-dh-copy-link]');
    var status = document.querySelector('[data-dh-share-status]');

    function announce(message) {
        if (!status) {
            return;
        }

        status.textContent = message;

        window.setTimeout(function () {
            status.textContent = '';
        }, 3000);
    }

    function legacyCopy(text) {
        var field = document.createElement('textarea');
        field.value = text;
        field.setAttribute('readonly', '');
        field.style.position = 'fixed';
        field.style.opacity = '0';
        document.body.appendChild(field);
        field.select();

        var copied = false;

        try {
            copied = document.execCommand('copy');
        } catch (error) {
            copied = false;
        }

        document.body.removeChild(field);
        return copied;
    }

    function copy(text) {
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

    function copyPostLink(button) {
        copy(button.getAttribute('data-url')).then(function (copied) {
            announce(copied ? strings.copied || 'Link copied' : strings.copyFailed || 'Could not copy the link');
        });
    }

    if (copyLink) {
        copyLink.addEventListener('click', function () {
            copyPostLink(copyLink);
        });
    }

    if (nativeShare && navigator.share) {
        nativeShare.hidden = false;
        nativeShare.addEventListener('click', function () {
            navigator
                .share({
                    title: nativeShare.getAttribute('data-title'),
                    url: nativeShare.getAttribute('data-url'),
                })
                .catch(function (error) {
                    if (error.name !== 'AbortError') {
                        copyPostLink(nativeShare);
                    }
                });
        });
    }
})();
