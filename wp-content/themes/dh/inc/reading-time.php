<?php
/**
 * Estimated reading time helpers.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Words per minute used for reading-time estimates.
 *
 * @return int
 */
function dh_get_reading_words_per_minute() {
    return (int) apply_filters('dh_reading_words_per_minute', 200);
}

/**
 * Estimated reading time in minutes for a post.
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return int
 */
function dh_get_reading_time_minutes($post = null) {
    $post = get_post($post);

    if (!$post) {
        return 0;
    }

    $word_count = str_word_count(wp_strip_all_tags($post->post_content));

    if ($word_count <= 0) {
        return 0;
    }

    return max(1, (int) ceil($word_count / dh_get_reading_words_per_minute()));
}

/**
 * Human-readable reading time label.
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return string
 */
function dh_get_reading_time_label($post = null) {
    $minutes = dh_get_reading_time_minutes($post);

    if ($minutes <= 0) {
        return '';
    }

    return sprintf(
        /* translators: %d: number of minutes */
        _n('%d min read', '%d min read', $minutes, 'dh'),
        $minutes
    );
}
