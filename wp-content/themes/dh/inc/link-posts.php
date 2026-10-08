<?php
/**
 * Link posts: short notes that point to an external URL.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Post meta key for the outbound link URL.
 */
function dh_get_link_url_meta_key() {
    return '_dh_link_url';
}

/**
 * External link URL for a post, if configured.
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return string
 */
function dh_get_link_url($post = null) {
    $post = get_post($post);

    if (!$post) {
        return '';
    }

    $url = get_post_meta($post->ID, dh_get_link_url_meta_key(), true);

    if (!is_string($url) || '' === $url) {
        return '';
    }

    $url = esc_url_raw($url);

    return $url ? $url : '';
}

/**
 * Whether the post is a link-style note.
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return bool
 */
function dh_is_link_post($post = null) {
    return '' !== dh_get_link_url($post);
}

/**
 * Primary URL visitors should follow for a post (external when link post).
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return string
 */
function dh_get_post_view_url($post = null) {
    $link = dh_get_link_url($post);

    if ($link) {
        return $link;
    }

    $post = get_post($post);

    return $post ? get_permalink($post) : '';
}

/**
 * Hostname label for a link post destination.
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return string
 */
function dh_get_link_host_label($post = null) {
    $url = dh_get_link_url($post);

    if (!$url) {
        return '';
    }

    $host = wp_parse_url($url, PHP_URL_HOST);

    if (!$host) {
        return '';
    }

    return preg_replace('/^www\./', '', $host);
}

/**
 * Register the link URL meta box.
 */
function dh_add_link_url_meta_box() {
    add_meta_box(
        'dh-link-url',
        __('Link post', 'dh'),
        'dh_render_link_url_meta_box',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'dh_add_link_url_meta_box');

/**
 * Render the link URL field.
 *
 * @param WP_Post $post Current post.
 */
function dh_render_link_url_meta_box($post) {
    $url = dh_get_link_url($post);

    wp_nonce_field('dh_save_link_url', 'dh_link_url_nonce');
    ?>
    <p>
        <label for="dh-link-url-field">
            <?php esc_html_e('Optional external URL. When set, list views link out directly and the permalink becomes a short note page.', 'dh'); ?>
        </label>
    </p>
    <input
        class="widefat"
        type="url"
        id="dh-link-url-field"
        name="dh_link_url"
        value="<?php echo esc_attr($url); ?>"
        placeholder="https://"
    />
    <?php
}

/**
 * Save the link URL meta field.
 *
 * @param int $post_id Post ID.
 */
function dh_save_link_url_meta($post_id) {
    if (
        !isset($_POST['dh_link_url_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dh_link_url_nonce'])), 'dh_save_link_url')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $raw = isset($_POST['dh_link_url']) ? wp_unslash($_POST['dh_link_url']) : '';
    $url = esc_url_raw(trim((string) $raw));

    if ('' === $url) {
        delete_post_meta($post_id, dh_get_link_url_meta_key());
        return;
    }

    update_post_meta($post_id, dh_get_link_url_meta_key(), $url);
}
add_action('save_post_post', 'dh_save_link_url_meta');

/**
 * Mark link posts in the body class list.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function dh_link_post_body_class($classes) {
    if (is_singular('post') && dh_is_link_post()) {
        $classes[] = 'is-link-post';
    }

    return $classes;
}
add_filter('body_class', 'dh_link_post_body_class');

/**
 * Add a list/single class hook for link posts.
 *
 * @param string[] $classes Post classes.
 * @param string[] $class   Additional classes.
 * @param int      $post_id Post ID.
 * @return string[]
 */
function dh_link_post_post_class($classes, $class, $post_id) {
    if ('post' === get_post_type($post_id) && dh_is_link_post($post_id)) {
        $classes[] = 'post--link';
    }

    return $classes;
}
add_filter('post_class', 'dh_link_post_post_class', 10, 3);

/**
 * Outbound link call-to-action on singular link posts.
 */
function dh_render_link_post_outbound() {
    if (!is_singular('post') || !dh_is_link_post()) {
        return;
    }

    $url   = dh_get_link_url();
    $label = dh_get_link_host_label();

    if (!$url) {
        return;
    }
    ?>
    <div class="link-post-outbound">
        <a class="link-post-outbound__button" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
            <?php
            if ($label) {
                printf(
                    /* translators: %s: hostname */
                    esc_html__('Read on %s', 'dh'),
                    esc_html($label)
                );
            } else {
                esc_html_e('Read linked article', 'dh');
            }
            ?>
            <span aria-hidden="true">&rarr;</span>
        </a>
    </div>
    <?php
}
