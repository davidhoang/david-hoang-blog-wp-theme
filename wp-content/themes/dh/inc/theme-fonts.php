<?php
/**
 * Self-hosted theme typography.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Local stylesheet URL for all reading styles.
 */
function dh_get_theme_font_url() {
    return get_template_directory_uri() . '/css/fonts.css';
}

/**
 * Enqueue the theme reading fonts.
 */
function dh_enqueue_theme_font() {
    wp_enqueue_style('dh-theme-font', dh_get_theme_font_url(), array(), DH_THEME_VERSION);
}
add_action('wp_enqueue_scripts', 'dh_enqueue_theme_font', 5);

/**
 * Enqueue the theme reading fonts in the block editor.
 */
function dh_enqueue_theme_font_editor() {
    wp_enqueue_style('dh-theme-font-editor', dh_get_theme_font_url(), array(), DH_THEME_VERSION);
}
add_action('enqueue_block_editor_assets', 'dh_enqueue_theme_font_editor', 5);
