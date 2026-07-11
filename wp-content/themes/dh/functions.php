<?php
/**
 * dh theme functions.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup.
 */
function dh_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('automatic-feed-links');

    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'dh'),
    ));
}
add_action('after_setup_theme', 'dh_setup');

/**
 * Enqueue theme assets.
 */
function dh_scripts() {
    wp_enqueue_style('dh-style', get_stylesheet_uri(), array(), '0.1.0');
}
add_action('wp_enqueue_scripts', 'dh_scripts');
