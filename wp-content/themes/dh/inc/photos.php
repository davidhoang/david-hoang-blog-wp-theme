<?php
/**
 * Photography post type and editorial homepage queries.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register photos as first-class, independently browsable content.
 */
function dh_register_photo_post_type() {
    register_post_type('photo', array(
        'labels' => array(
            'name'          => __('Photos', 'dh'),
            'singular_name' => __('Photo', 'dh'),
            'add_new_item'  => __('Add photo', 'dh'),
            'edit_item'     => __('Edit photo', 'dh'),
            'all_items'     => __('All photos', 'dh'),
            'archives'      => __('Photo archive', 'dh'),
        ),
        'public'       => true,
        'has_archive'  => 'photos',
        'menu_icon'    => 'dashicons-format-image',
        'rewrite'      => array('slug' => 'photos'),
        'show_in_rest' => true,
        'supports'     => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
        'taxonomies'   => array('category', 'post_tag'),
    ));
}
add_action('init', 'dh_register_photo_post_type');

/**
 * Refresh rewrite rules once when the photo archive is introduced.
 */
function dh_maybe_flush_photo_rewrites() {
    $rewrite_version = '1';

    if (get_option('dh_photo_rewrite_version') === $rewrite_version) {
        return;
    }

    flush_rewrite_rules();
    update_option('dh_photo_rewrite_version', $rewrite_version, false);
}
add_action('init', 'dh_maybe_flush_photo_rewrites', 20);

/**
 * Featured homepage essay: sticky post first, latest essay otherwise.
 *
 * @return WP_Post|null
 */
function dh_get_featured_essay() {
    $sticky = array_values(array_filter(array_map('absint', (array) get_option('sticky_posts', array()))));
    $args   = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 1,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    );

    if ($sticky) {
        $args['post__in'] = $sticky;
        $args['orderby']  = 'post__in';
    }

    $posts = get_posts($args);

    return $posts ? reset($posts) : null;
}

/**
 * Recent essays excluding the homepage feature.
 *
 * @param int $limit Number of essays.
 * @param int $exclude Post ID to omit.
 * @return WP_Post[]
 */
function dh_get_home_essays($limit = 4, $exclude = 0) {
    return get_posts(array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => max(1, (int) $limit),
        'post__not_in'        => $exclude ? array((int) $exclude) : array(),
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    ));
}

/**
 * Latest photos for the homepage.
 *
 * @param int $limit Number of photos.
 * @return WP_Post[]
 */
function dh_get_home_photos($limit = 4) {
    return get_posts(array(
        'post_type'      => 'photo',
        'post_status'    => 'publish',
        'posts_per_page' => max(1, (int) $limit),
        'no_found_rows'  => true,
    ));
}
