<?php
/**
 * Theme default typography.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default theme font family name.
 */
function dh_get_theme_font_family() {
    return 'ABC Arizona Text Trial';
}

/**
 * CSS font stack for the theme default.
 */
function dh_get_theme_font_stack() {
    return '"' . dh_get_theme_font_family() . '", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';
}

/**
 * Default font files for ABC Arizona Text.
 */
function dh_get_theme_font_files() {
    $weights = array(
        400 => 'ABCArizonaTextTrial-Regular.otf',
        500 => 'ABCArizonaTextTrial-Medium.otf',
        700 => 'ABCArizonaTextTrial-Bold.otf',
    );

    $files = array();

    foreach ($weights as $weight => $filename) {
        $relative = 'Arizona/ABC Arizona Text/' . $filename;

        $files[] = array(
            'relative' => $relative,
            'url'      => home_url('/?dh_dev_font=' . rawurlencode($relative)),
            'weight'   => $weight,
            'style'    => 'normal',
        );
    }

    return $files;
}

/**
 * Build @font-face CSS for the theme default.
 */
function dh_get_theme_font_face_css() {
    if (!function_exists('dh_is_local_dev') || !dh_is_local_dev() || !dh_get_dev_fonts_root()) {
        return '';
    }

    $family = dh_get_theme_font_family();
    $rules = array();

    foreach (dh_get_theme_font_files() as $file) {
        $rules[] = sprintf(
            '@font-face { font-family: "%1$s"; src: url("%2$s") format("opentype"); font-weight: %3$d; font-style: %4$s; font-display: swap; }',
            esc_attr($family),
            esc_url($file['url']),
            (int) $file['weight'],
            esc_attr($file['style'])
        );
    }

    return implode("\n", $rules);
}

/**
 * Font switcher config for the active theme default.
 */
function dh_get_default_font_switcher_config() {
    return array(
        'id'         => 'arizona-abc-arizona-text',
        'family'     => 'Arizona',
        'label'      => 'Arizona / ABC Arizona Text',
        'css_family' => dh_get_theme_font_family(),
        'files'      => dh_get_theme_font_files(),
        'variable'   => false,
    );
}

/**
 * Enqueue the theme default font on localhost.
 */
function dh_enqueue_theme_font() {
    wp_register_style('dh-theme-font', false, array(), '0.7.4');
    wp_enqueue_style('dh-theme-font');

    $css = dh_get_theme_font_face_css();

    if ($css) {
        wp_add_inline_style('dh-theme-font', $css);
    }
}
add_action('wp_enqueue_scripts', 'dh_enqueue_theme_font', 5);

/**
 * Enqueue the theme default font in the block editor on localhost.
 */
function dh_enqueue_theme_font_editor() {
    wp_register_style('dh-theme-font-editor', false, array(), '0.7.4');
    wp_enqueue_style('dh-theme-font-editor');

    $css = dh_get_theme_font_face_css();

    if ($css) {
        wp_add_inline_style('dh-theme-font-editor', $css);
    }
}
add_action('enqueue_block_editor_assets', 'dh_enqueue_theme_font_editor', 5);
