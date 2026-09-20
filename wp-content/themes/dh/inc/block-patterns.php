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

    register_block_pattern('dh/photo-comparison', array(
        'title'       => esc_html__('Photo comparison', 'dh'),
        'description' => esc_html__('Two captioned, lightbox-ready images shown side by side.', 'dh'),
        'categories'  => array('dh', 'media'),
        'content'     => '<!-- wp:group {"align":"wide","className":"dh-photo-comparison","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide dh-photo-comparison"><!-- wp:heading {"level":3,"className":"dh-photo-comparison__title"} -->
<h3 class="wp-block-heading dh-photo-comparison__title">Who did it better?</h3>
<!-- /wp:heading -->

<!-- wp:columns {"className":"dh-photo-comparison__images"} -->
<div class="wp-block-columns dh-photo-comparison__images"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none","lightbox":{"enabled":true}} -->
<figure class="wp-block-image size-large"><img alt="" /><figcaption class="wp-element-caption">First image</figcaption></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none","lightbox":{"enabled":true}} -->
<figure class="wp-block-image size-large"><img alt="" /><figcaption class="wp-element-caption">Second image</figcaption></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
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

    register_block_pattern('dh/essay-opener', array(
        'title'       => esc_html__('Essay opener', 'dh'),
        'description' => esc_html__('A short standfirst followed by a quiet divider.', 'dh'),
        'categories'  => array('dh', 'text'),
        'content'     => '<!-- wp:paragraph {"className":"dh-standfirst","fontSize":"lede"} -->
<p class="dh-standfirst has-lede-font-size">Set the scene with one concise paragraph that invites the reader into the essay.</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->',
    ));

    register_block_pattern('dh/editorial-aside', array(
        'title'       => esc_html__('Editorial aside', 'dh'),
        'description' => esc_html__('A compact note for context, definitions, or useful tangents.', 'dh'),
        'categories'  => array('dh', 'text'),
        'content'     => '<!-- wp:group {"className":"dh-editorial-aside","layout":{"type":"constrained"}} -->
<div class="wp-block-group dh-editorial-aside"><!-- wp:paragraph {"className":"dh-editorial-aside__label"} -->
<p class="dh-editorial-aside__label">A brief aside</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Add context without interrupting the main thread of the essay.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
    ));

    register_block_pattern('dh/numbered-steps', array(
        'title'       => esc_html__('Numbered steps', 'dh'),
        'description' => esc_html__('A clear three-step sequence for processes and guides.', 'dh'),
        'categories'  => array('dh', 'text'),
        'content'     => '<!-- wp:group {"className":"dh-numbered-steps","layout":{"type":"constrained"}} -->
<div class="wp-block-group dh-numbered-steps"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">How it works</h2>
<!-- /wp:heading -->

<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list"><li><strong>Start with the question.</strong> Describe what needs to change.</li><li><strong>Work through the evidence.</strong> Make the reasoning visible.</li><li><strong>Share what changed.</strong> Close with the result and what comes next.</li></ol>
<!-- /wp:list --></div>
<!-- /wp:group -->',
    ));

    register_block_pattern('dh/last-tended', array(
        'title'       => esc_html__('Last tended note', 'dh'),
        'description' => esc_html__('A small maintenance note for living documents.', 'dh'),
        'categories'  => array('dh', 'text'),
        'content'     => '<!-- wp:paragraph {"className":"dh-last-tended"} -->
<p class="dh-last-tended"><strong>Last tended:</strong> Add the date and a short note about what changed.</p>
<!-- /wp:paragraph -->',
    ));
}
add_action('init', 'dh_register_block_patterns');
