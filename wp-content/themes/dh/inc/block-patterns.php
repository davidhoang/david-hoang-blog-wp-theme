<?php
/**
 * Block pattern registration.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register a custom pattern category and theme patterns.
 */
function dh_register_block_patterns() {
    if (!function_exists('register_block_pattern')) {
        return;
    }

    register_block_pattern_category('dh', array(
        'label' => esc_html__('dh Theme', 'dh'),
    ));

    register_block_pattern('dh/pull-quote', array(
        'title'       => esc_html__('Editorial pull quote', 'dh'),
        'description' => esc_html__('A centered pull quote with attribution.', 'dh'),
        'categories'  => array('dh', 'text'),
        'content'     => '<!-- wp:quote {"align":"wide","className":"is-style-default"} -->
<blockquote class="wp-block-quote alignwide"><p>A short quote that deserves to stand apart from the body text.</p><cite>Attribution</cite></blockquote>
<!-- /wp:quote -->',
    ));

    register_block_pattern('dh/polaroid-image', array(
        'title'       => esc_html__('Polaroid image', 'dh'),
        'description' => esc_html__('Image with caption styled like a polaroid print.', 'dh'),
        'categories'  => array('dh', 'media'),
        'content'     => '<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img alt="" /><figcaption class="wp-element-caption">Caption goes here.</figcaption></figure>
<!-- /wp:image -->',
    ));

    register_block_pattern('dh/about-sidebar', array(
        'title'       => esc_html__('About sidebar block', 'dh'),
        'description' => esc_html__('Intro text and link list for the sidebar.', 'dh'),
        'categories'  => array('dh', 'text'),
        'content'     => '<!-- wp:heading {"level":3,"className":"sidebar-title"} -->
<h3 class="wp-block-heading sidebar-title">About</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A short introduction and links to other places you publish.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><li><a href="https://davidhoang.com">davidhoang.com</a></li><li><a href="#">RSS feed</a></li></ul>
<!-- /wp:list -->',
    ));
}
add_action('init', 'dh_register_block_patterns');
