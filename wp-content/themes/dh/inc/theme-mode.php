<?php
/**
 * Light / dark color mode.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Browser chrome color meta tag (updated by the boot script / toggle).
 */
function dh_print_theme_color_meta() {
    echo '<meta name="theme-color" content="#ffffff">' . "\n";
}
add_action('wp_head', 'dh_print_theme_color_meta', 0);

/**
 * Inline script that applies the saved (or system) theme before first paint.
 */
function dh_print_theme_mode_boot_script() {
    ?>
    <script>
    (function () {
        try {
            var key = 'dh-color-scheme';
            var stored = localStorage.getItem(key);
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            var theme = stored === 'light' || stored === 'dark'
                ? stored
                : (prefersDark ? 'dark' : 'light');

            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;

            var meta = document.querySelector('meta[name="theme-color"]');
            if (meta) {
                meta.setAttribute('content', theme === 'dark' ? '#161614' : '#ffffff');
            }
        } catch (e) {
            // no-op
        }
    })();
    </script>
    <?php
}
add_action('wp_head', 'dh_print_theme_mode_boot_script', 1);

/**
 * Sun / moon icons for the theme toggle.
 */
function dh_get_theme_toggle_icons() {
    $sun = '<svg class="theme-toggle__icon theme-toggle__icon--sun" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false"><circle cx="8" cy="8" r="3.25" stroke="currentColor" stroke-width="1.25"/><path d="M8 1.5v1.25M8 13.25V14.5M1.5 8h1.25M13.25 8H14.5M3.4 3.4l.88.88M11.72 11.72l.88.88M3.4 12.6l.88-.88M11.72 4.28l.88-.88" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"/></svg>';

    $moon = '<svg class="theme-toggle__icon theme-toggle__icon--moon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false"><path d="M13.25 9.1A5.5 5.5 0 0 1 6.9 2.75 5.75 5.75 0 1 0 13.25 9.1Z" stroke="currentColor" stroke-width="1.25" stroke-linejoin="round"/></svg>';

    return $sun . $moon;
}

/**
 * Render the color-mode toggle control.
 */
function dh_render_theme_toggle() {
    printf(
        '<button type="button" class="theme-toggle" data-dh-theme-toggle aria-label="%1$s" title="%1$s">%2$s</button>',
        esc_attr__('Switch to dark mode', 'dh'),
        dh_get_theme_toggle_icons()
    );
}

/**
 * Enqueue color-mode script.
 */
function dh_enqueue_theme_mode_script() {
    wp_enqueue_script(
        'dh-theme-mode',
        get_template_directory_uri() . '/js/theme-mode.js',
        array(),
        '0.10.0',
        true
    );

    wp_localize_script(
        'dh-theme-mode',
        'dhThemeMode',
        array(
            'labels' => array(
                'toDark'  => __('Switch to dark mode', 'dh'),
                'toLight' => __('Switch to light mode', 'dh'),
            ),
            'colors' => array(
                'light' => '#ffffff',
                'dark'  => '#161614',
            ),
        )
    );
}
add_action('wp_enqueue_scripts', 'dh_enqueue_theme_mode_script');
