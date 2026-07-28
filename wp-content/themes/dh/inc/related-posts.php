<?php
/**
 * Related posts by shared tags.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fetch related posts that share tags with the current post.
 *
 * @param int|null $post_id Post ID.
 * @param int      $limit   Maximum number of posts.
 * @return WP_Post[]
 */
function dh_get_related_posts($post_id = null, $limit = 3) {
    $post_id = $post_id ? (int) $post_id : get_the_ID();

    if (!$post_id) {
        return array();
    }

    $tag_ids = wp_get_post_tags($post_id, array('fields' => 'ids'));

    if (empty($tag_ids)) {
        return array();
    }

    $limit = max(1, (int) apply_filters('dh_related_posts_limit', $limit));

    return get_posts(array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'post__not_in'        => array($post_id),
        'tag__in'             => $tag_ids,
        'ignore_sticky_posts' => true,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'no_found_rows'       => true,
    ));
}

/**
 * Render related posts for a single post view.
 *
 * @param int|null $post_id Post ID.
 */
function dh_render_related_posts($post_id = null) {
    $related_posts = dh_get_related_posts($post_id);

    if (empty($related_posts)) {
        return;
    }

    get_template_part('template-parts/related-posts', null, array(
        'related_posts' => $related_posts,
    ));
}
