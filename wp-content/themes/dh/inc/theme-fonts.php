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
 * Google Fonts stylesheet URL for the theme font.
 */
function dh_get_theme_font_url() {
    return 'https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap';
}

/**
 * Enqueue the theme default font.
 */
function dh_enqueue_theme_font() {
    wp_enqueue_style('dh-theme-font', dh_get_theme_font_url(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'dh_enqueue_theme_font', 5);

/**
 * Enqueue the theme default font in the block editor.
 */
function dh_enqueue_theme_font_editor() {
    wp_enqueue_style('dh-theme-font-editor', dh_get_theme_font_url(), array(), '1.0.0');
}
add_action('enqueue_block_editor_assets', 'dh_enqueue_theme_font_editor', 5);
