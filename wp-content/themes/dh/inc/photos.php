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
        'rewrite'      => array(
            'slug'   => 'photos',
            'feeds'  => true,
            'with_front' => true,
        ),
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
    $rewrite_version = '2';

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

/**
 * RSS feed URL for the photo archive.
 *
 * @return string
 */
function dh_get_photo_feed_url() {
    $link = get_post_type_archive_link('photo');

    if (!$link) {
        return '';
    }

    return trailingslashit($link) . 'feed/';
}

/**
 * Featured image data for a photo post (full size when available).
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return array{id: int, url: string, alt: string}|null
 */
function dh_get_photo_full_image($post = null) {
    $post = get_post($post);

    if (!$post || 'photo' !== $post->post_type) {
        return null;
    }

    $thumbnail_id = get_post_thumbnail_id($post);

    if (!$thumbnail_id) {
        return null;
    }

    $full = wp_get_attachment_image_src($thumbnail_id, 'full');
    $alt  = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

    if (!$full) {
        return null;
    }

    return array(
        'id'  => (int) $thumbnail_id,
        'url' => $full[0],
        'alt' => is_string($alt) ? $alt : '',
    );
}

/**
 * Archive meta for the photo index (count + RSS).
 */
function dh_render_photo_archive_meta() {
    if (!is_post_type_archive('photo')) {
        return;
    }

    global $wp_query;

    $count = isset($wp_query->found_posts) ? (int) $wp_query->found_posts : 0;
    $feed  = dh_get_photo_feed_url();

    $count_label = sprintf(
        /* translators: %s: number of photos */
        _n('%s photograph', '%s photographs', $count, 'dh'),
        number_format_i18n($count)
    );
    ?>
    <div class="archive-meta">
        <p class="archive-meta__summary">
            <span class="archive-meta__count"><?php echo esc_html($count_label); ?></span>
            <?php if ($feed) : ?>
                <span aria-hidden="true">&middot;</span>
                <a class="archive-meta__feed" href="<?php echo esc_url($feed); ?>">
                    <?php esc_html_e('Subscribe via RSS', 'dh'); ?>
                </a>
            <?php endif; ?>
        </p>
    </div>
    <?php
}

/**
 * Whether the photo lightbox should load on this request.
 *
 * @return bool
 */
function dh_should_enqueue_photo_lightbox() {
    if (is_admin() || is_feed() || wp_doing_ajax()) {
        return false;
    }

    return is_post_type_archive('photo') || is_singular('photo') || (is_front_page() && !is_paged());
}

/**
 * Enqueue progressive enhancement for the photo lightbox.
 */
function dh_enqueue_photo_lightbox_assets() {
    if (!dh_should_enqueue_photo_lightbox()) {
        return;
    }

    wp_enqueue_script(
        'dh-photo-lightbox',
        get_template_directory_uri() . '/js/photo-lightbox.js',
        array(),
        DH_THEME_VERSION,
        array(
            'in_footer' => true,
            'strategy'  => 'defer',
        )
    );
}
add_action('wp_enqueue_scripts', 'dh_enqueue_photo_lightbox_assets');

/**
 * Lightbox shell markup (one dialog reused for all photos).
 */
function dh_render_photo_lightbox_shell() {
    if (!dh_should_enqueue_photo_lightbox()) {
        return;
    }
    ?>
    <div class="photo-lightbox" data-dh-photo-lightbox hidden>
        <div class="photo-lightbox__backdrop" data-dh-lightbox-close tabindex="-1"></div>
        <div
            class="photo-lightbox__dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="dh-photo-lightbox-title"
        >
            <button class="photo-lightbox__close" type="button" data-dh-lightbox-close>
                <?php esc_html_e('Close', 'dh'); ?>
            </button>
            <figure class="photo-lightbox__figure">
                <img class="photo-lightbox__image" alt="" decoding="async" />
                <figcaption class="photo-lightbox__caption">
                    <p class="photo-lightbox__title" id="dh-photo-lightbox-title"></p>
                    <a class="photo-lightbox__permalink editorial-link" href="#" data-dh-lightbox-permalink>
                        <?php esc_html_e('View photograph', 'dh'); ?>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </figcaption>
            </figure>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'dh_render_photo_lightbox_shell', 5);
