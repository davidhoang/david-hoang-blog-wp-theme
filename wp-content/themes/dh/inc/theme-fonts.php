<?php
/**
 * Theme typography (Google Fonts for reading styles).
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Google Fonts stylesheet URL for all reading styles.
 */
function dh_get_theme_font_url() {
    $families = array(
        'family=Newsreader:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700',
        'family=Literata:ital,opsz,wght@0,7..72,400;0,7..72,500;0,7..72,600;0,7..72,700;1,7..72,400;1,7..72,500;1,7..72,600;1,7..72,700',
        'family=Source+Sans+3:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700',
        'family=IBM+Plex+Mono:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500',
        'family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400;1,700',
    );

    return 'https://fonts.googleapis.com/css2?' . implode('&', $families) . '&display=swap';
}

/**
 * Enqueue the theme reading fonts.
 */
function dh_enqueue_theme_font() {
    wp_enqueue_style('dh-theme-font', dh_get_theme_font_url(), array(), '2.0.0');
}
add_action('wp_enqueue_scripts', 'dh_enqueue_theme_font', 5);

/**
 * Enqueue the theme reading fonts in the block editor.
 */
function dh_enqueue_theme_font_editor() {
    wp_enqueue_style('dh-theme-font-editor', dh_get_theme_font_url(), array(), '2.0.0');
}
add_action('enqueue_block_editor_assets', 'dh_enqueue_theme_font_editor', 5);

/**
 * Preconnect to the Google Fonts hosts so font CSS and font files start
 * downloading sooner. The crossorigin hint on fonts.gstatic.com is required
 * for the actual font-file connection to be reused.
 *
 * @param array  $urls          Resource hint URLs for the given relation.
 * @param string $relation_type Relation type being requested.
 * @return array
 */
function dh_font_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
        );
        $urls[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }

    return $urls;
}
add_filter('wp_resource_hints', 'dh_font_resource_hints', 10, 2);
