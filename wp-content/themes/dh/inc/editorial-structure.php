<?php
/**
 * Editorial structures for post series, index dividers, and end notes.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register a lightweight series taxonomy for ordered groups of posts.
 */
function dh_register_series_taxonomy() {
    register_taxonomy('series', array('post'), array(
        'labels'            => array(
            'name'                       => _x('Series', 'taxonomy general name', 'dh'),
            'singular_name'              => _x('Series', 'taxonomy singular name', 'dh'),
            'search_items'               => __('Search series', 'dh'),
            'all_items'                  => __('All series', 'dh'),
            'edit_item'                  => __('Edit series', 'dh'),
            'update_item'                => __('Update series', 'dh'),
            'add_new_item'               => __('Add new series', 'dh'),
            'new_item_name'              => __('New series name', 'dh'),
            'menu_name'                  => __('Series', 'dh'),
            'popular_items'              => __('Popular series', 'dh'),
            'separate_items_with_commas' => __('Separate series with commas', 'dh'),
            'add_or_remove_items'         => __('Add or remove series', 'dh'),
            'choose_from_most_used'       => __('Choose from the most used series', 'dh'),
            'not_found'                  => __('No series found', 'dh'),
        ),
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,
        'rewrite'           => array('slug' => 'series'),
    ));
}
add_action('init', 'dh_register_series_taxonomy');

/**
 * Refresh rewrite rules once after the Series archive is introduced.
 */
function dh_maybe_flush_editorial_rewrites() {
    $rewrite_version = '1';

    if (get_option('dh_editorial_rewrite_version') === $rewrite_version) {
        return;
    }

    flush_rewrite_rules();
    update_option('dh_editorial_rewrite_version', $rewrite_version, false);
}
add_action('init', 'dh_maybe_flush_editorial_rewrites', 20);

/**
 * Keep series archives in chapter order.
 *
 * Posts use their publication date as the reading order, oldest first.
 *
 * @param WP_Query $query Current query.
 */
function dh_order_series_archive($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_tax('series')) {
        $query->set('orderby', 'date');
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'dh_order_series_archive');

/**
 * Get the primary series assigned to a post.
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return WP_Term|null
 */
function dh_get_post_series($post = null) {
    $terms = get_the_terms($post, 'series');

    if (!$terms || is_wp_error($terms)) {
        return null;
    }

    return reset($terms);
}

/**
 * Get a post's position and neighbors within its primary series.
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return array<string, mixed>|null
 */
function dh_get_series_context($post = null) {
    static $series_posts = array();

    $post = get_post($post);

    if (!$post) {
        return null;
    }

    $series = dh_get_post_series($post);

    if (!$series) {
        return null;
    }

    if (!isset($series_posts[$series->term_id])) {
        $series_posts[$series->term_id] = get_posts(array(
            'post_type'              => 'post',
            'post_status'            => 'publish',
            'posts_per_page'         => -1,
            'fields'                 => 'ids',
            'orderby'                => 'date',
            'order'                  => 'ASC',
            'ignore_sticky_posts'    => true,
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'tax_query'              => array(
                array(
                    'taxonomy' => 'series',
                    'field'    => 'term_id',
                    'terms'    => $series->term_id,
                ),
            ),
        ));
    }

    $post_ids = $series_posts[$series->term_id];

    $index = array_search($post->ID, $post_ids, true);

    if (false === $index) {
        return null;
    }

    return array(
        'term'     => $series,
        'position' => $index + 1,
        'total'    => count($post_ids),
        'previous' => $index > 0 ? get_post($post_ids[$index - 1]) : null,
        'next'     => $index < count($post_ids) - 1 ? get_post($post_ids[$index + 1]) : null,
    );
}

/**
 * Print a year marker when the post index crosses into a new year.
 */
function dh_the_year_divider() {
    global $wp_query;

    $current_index = (int) $wp_query->current_post;
    $current_year  = get_the_date('Y');
    $previous_year = '';

    if ($current_index > 0 && isset($wp_query->posts[$current_index - 1])) {
        $previous_year = get_the_date('Y', $wp_query->posts[$current_index - 1]);
    }

    if ($current_index > 0 && $current_year === $previous_year) {
        return;
    }

    printf(
        '<div class="post-year-divider" aria-label="%1$s"><span>%2$s</span></div>',
        esc_attr(sprintf(__('Posts from %s', 'dh'), $current_year)),
        esc_html($current_year)
    );
}

/**
 * Add the optional postscript field to posts.
 */
function dh_add_postscript_meta_box() {
    add_meta_box(
        'dh-postscript',
        __('Post end note', 'dh'),
        'dh_render_postscript_meta_box',
        'post',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'dh_add_postscript_meta_box');

/**
 * Render the postscript editor.
 *
 * @param WP_Post $post Current post.
 */
function dh_render_postscript_meta_box($post) {
    $postscript = get_post_meta($post->ID, '_dh_postscript', true);

    wp_nonce_field('dh_save_postscript', 'dh_postscript_nonce');
    ?>
    <p>
        <label for="dh-postscript-field">
            <?php esc_html_e('An optional personal note shown after the essay. Basic formatting and links are supported.', 'dh'); ?>
        </label>
    </p>
    <textarea class="widefat" rows="5" id="dh-postscript-field" name="dh_postscript"><?php echo esc_textarea($postscript); ?></textarea>
    <?php
}

/**
 * Save the optional postscript.
 *
 * @param int $post_id Post ID.
 */
function dh_save_postscript($post_id) {
    if (
        !isset($_POST['dh_postscript_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dh_postscript_nonce'])), 'dh_save_postscript')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $postscript = isset($_POST['dh_postscript'])
        ? wp_kses_post(wp_unslash($_POST['dh_postscript']))
        : '';

    if ('' === trim($postscript)) {
        delete_post_meta($post_id, '_dh_postscript');
        return;
    }

    update_post_meta($post_id, '_dh_postscript', $postscript);
}
add_action('save_post_post', 'dh_save_postscript');
