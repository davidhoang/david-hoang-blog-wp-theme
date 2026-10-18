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
 * Fetch related posts using progressively broader editorial context.
 *
 * Results prefer the same series, then shared tags, then categories, and
 * finally recent essays. Each pass excludes posts already selected.
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

    $limit = max(1, (int) apply_filters('dh_related_posts_limit', $limit));
    $base  = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'no_found_rows'       => true,
    );
    $found = array();

    $series = dh_get_post_series($post_id);

    if ($series) {
        $found = dh_related_posts_query($base, $found, $post_id, $limit, array(
            'orderby'   => 'date',
            'order'     => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'series',
                    'field'    => 'term_id',
                    'terms'    => (int) $series->term_id,
                ),
            ),
        ));
    }

    $tag_ids = wp_get_post_tags($post_id, array('fields' => 'ids'));

    if ($tag_ids && count($found) < $limit) {
        $found = dh_related_posts_query($base, $found, $post_id, $limit, array(
            'tag__in' => $tag_ids,
        ));
    }

    $category_ids = wp_get_post_categories($post_id, array('fields' => 'ids'));

    if ($category_ids && count($found) < $limit) {
        $found = dh_related_posts_query($base, $found, $post_id, $limit, array(
            'category__in' => $category_ids,
        ));
    }

    if (count($found) < $limit) {
        $found = dh_related_posts_query($base, $found, $post_id, $limit);
    }

    return array_slice($found, 0, $limit);
}

/**
 * Run one related-post pass and append unique results.
 *
 * @param array     $base    Shared query arguments.
 * @param WP_Post[] $found   Posts selected so far.
 * @param int       $post_id Current post ID.
 * @param int       $limit   Total desired posts.
 * @param array     $context Context-specific query arguments.
 * @return WP_Post[]
 */
function dh_related_posts_query($base, $found, $post_id, $limit, $context = array()) {
    $excluded = array_merge(array((int) $post_id), wp_list_pluck($found, 'ID'));
    $args     = array_merge($base, array(
        'posts_per_page' => $limit - count($found),
        'post__not_in'   => array_values(array_unique(array_map('intval', $excluded))),
    ), $context);

    foreach (get_posts($args) as $post) {
        $found[$post->ID] = $post;
    }

    return array_values($found);
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
