<?php
/**
 * Content discovery helpers: archive meta, topic browse, and richer feeds.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Feed URL for the current main query context.
 *
 * @return string
 */
function dh_get_context_feed_url() {
    if (is_category()) {
        return get_category_feed_link(get_queried_object_id());
    }

    if (is_tag()) {
        return get_tag_feed_link(get_queried_object_id());
    }

    if (is_tax()) {
        $term = get_queried_object();

        if ($term instanceof WP_Term) {
            return get_term_feed_link($term->term_id, $term->taxonomy);
        }
    }

    if (is_author()) {
        return get_author_feed_link(get_queried_object_id());
    }

    if (is_search()) {
        return get_search_feed_link();
    }

    return get_bloginfo('rss2_url');
}

/**
 * Whether the current view should show archive discovery chrome.
 *
 * @return bool
 */
function dh_should_show_archive_meta() {
    return is_category() || is_tag() || is_tax() || is_author() || is_date();
}

/**
 * Render count + subscribe meta under archive headers.
 */
function dh_render_archive_meta() {
    if (!dh_should_show_archive_meta()) {
        return;
    }

    global $wp_query;

    $count = isset($wp_query->found_posts) ? (int) $wp_query->found_posts : 0;
    $feed  = dh_get_context_feed_url();

    $count_label = sprintf(
        /* translators: %s: number of posts */
        _n('%s essay', '%s essays', $count, 'dh'),
        number_format_i18n($count)
    );
    ?>
    <div class="archive-meta">
        <?php if (is_author()) : ?>
            <?php
            $author_id = get_queried_object_id();
            $avatar    = get_avatar($author_id, 72, '', '', array(
                'class'         => 'archive-meta__avatar',
                'loading'       => 'lazy',
                'decoding'      => 'async',
                'force_display' => true,
            ));
            ?>
            <?php if ($avatar) : ?>
                <div class="archive-meta__identity">
                    <?php echo $avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

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
 * Topics worth listing on the posts index.
 *
 * @return array{series: WP_Term[], categories: WP_Term[], tags: WP_Term[]}
 */
function dh_get_browse_topics() {
    $series = get_terms(array(
        'taxonomy'   => 'series',
        'hide_empty' => true,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => 8,
    ));

    $categories = get_terms(array(
        'taxonomy'   => 'category',
        'hide_empty' => true,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => 8,
        'exclude'    => array((int) get_option('default_category')),
    ));

    $tags = get_terms(array(
        'taxonomy'   => 'post_tag',
        'hide_empty' => true,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => 12,
    ));

    return array(
        'series'     => is_wp_error($series) ? array() : $series,
        'categories' => is_wp_error($categories) ? array() : $categories,
        'tags'       => is_wp_error($tags) ? array() : $tags,
    );
}

/**
 * Recent essays used as recovery paths on empty views.
 *
 * @param int $limit Maximum number of posts.
 * @return WP_Post[]
 */
function dh_get_recent_essays($limit = 5) {
    $limit = max(1, (int) apply_filters('dh_recent_essays_limit', $limit));

    return get_posts(array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
        'orderby'             => 'date',
        'order'               => 'DESC',
    ));
}

/**
 * Render a compact list of recent essays.
 *
 * @param array $args {
 *     @type string $heading Heading text.
 *     @type int    $limit   Maximum number of posts.
 * }
 */
function dh_render_recent_essays($args = array()) {
    $args = wp_parse_args($args, array(
        'heading' => __('Recent essays', 'dh'),
        'limit'   => 5,
    ));

    $posts = dh_get_recent_essays($args['limit']);

    if (empty($posts)) {
        return;
    }
    ?>
    <section class="recovery-posts" aria-labelledby="recovery-posts-title">
        <h2 class="recovery-posts__title" id="recovery-posts-title"><?php echo esc_html($args['heading']); ?></h2>
        <ul class="recovery-posts__list">
            <?php foreach ($posts as $essay) : ?>
                <li class="recovery-posts__item">
                    <a class="recovery-posts__link" href="<?php echo esc_url(get_permalink($essay)); ?>">
                        <span class="recovery-posts__post-title"><?php echo esc_html(dh_get_display_title($essay)); ?></span>
                        <time class="recovery-posts__date" datetime="<?php echo esc_attr(get_the_date('c', $essay)); ?>">
                            <?php echo esc_html(get_the_date('', $essay)); ?>
                        </time>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php
}

/**
 * Recent essays and topic browse for 404 and empty result views.
 */
function dh_render_recovery_paths() {
    if (is_home()) {
        return;
    }

    ob_start();
    dh_render_recent_essays();
    dh_render_browse_topics(array('force' => true));
    $html = trim(ob_get_clean());

    if ('' === $html) {
        return;
    }

    echo '<div class="recovery">' . $html . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Render a quiet topic browse list.
 *
 * @param array $args {
 *     @type bool $force Render even when not on the first blog index page.
 * }
 */
function dh_render_browse_topics($args = array()) {
    $args = wp_parse_args($args, array(
        'force' => false,
    ));

    if (!$args['force'] && (!is_home() || is_paged())) {
        return;
    }

    $topics = dh_get_browse_topics();

    if (empty($topics['series']) && empty($topics['categories']) && empty($topics['tags'])) {
        return;
    }
    ?>
    <aside class="browse-topics" aria-labelledby="browse-topics-title">
        <h2 class="browse-topics__title" id="browse-topics-title"><?php esc_html_e('Browse', 'dh'); ?></h2>

        <?php if (!empty($topics['series'])) : ?>
            <div class="browse-topics__group">
                <h3 class="browse-topics__heading"><?php esc_html_e('Series', 'dh'); ?></h3>
                <ul class="browse-topics__list">
                    <?php foreach ($topics['series'] as $term) : ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                            <span class="browse-topics__count"><?php echo esc_html(number_format_i18n($term->count)); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($topics['categories'])) : ?>
            <div class="browse-topics__group">
                <h3 class="browse-topics__heading"><?php esc_html_e('Categories', 'dh'); ?></h3>
                <ul class="browse-topics__list">
                    <?php foreach ($topics['categories'] as $term) : ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                            <span class="browse-topics__count"><?php echo esc_html(number_format_i18n($term->count)); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($topics['tags'])) : ?>
            <div class="browse-topics__group">
                <h3 class="browse-topics__heading"><?php esc_html_e('Tags', 'dh'); ?></h3>
                <ul class="browse-topics__list browse-topics__list--inline">
                    <?php foreach ($topics['tags'] as $term) : ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </aside>
    <?php
}

/**
 * Append the featured image to RSS2 feed items.
 */
function dh_feed_featured_image_rss2() {
    if (!has_post_thumbnail()) {
        return;
    }

    $image_id  = get_post_thumbnail_id();
    $image     = wp_get_attachment_image_src($image_id, 'large');
    $file_path = $image_id ? get_attached_file($image_id) : '';

    if (!$image) {
        return;
    }

    $mime   = get_post_mime_type($image_id);
    $length = ($file_path && is_readable($file_path)) ? (int) filesize($file_path) : 0;

    printf(
        '<enclosure url="%1$s" length="%2$d" type="%3$s" />' . "\n",
        esc_url($image[0]),
        $length,
        esc_attr($mime ? $mime : 'image/jpeg')
    );

    printf(
        '<media:content url="%1$s" medium="image" width="%2$d" height="%3$d" />' . "\n",
        esc_url($image[0]),
        (int) $image[1],
        (int) $image[2]
    );
}
add_action('rss2_item', 'dh_feed_featured_image_rss2');

/**
 * Append the featured image to Atom feed entries.
 */
function dh_feed_featured_image_atom() {
    if (!has_post_thumbnail()) {
        return;
    }

    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');

    if (!$image) {
        return;
    }

    printf(
        '<media:content url="%1$s" medium="image" width="%2$d" height="%3$d" />' . "\n",
        esc_url($image[0]),
        (int) $image[1],
        (int) $image[2]
    );
}
add_action('atom_entry', 'dh_feed_featured_image_atom');

/**
 * Declare the Media RSS namespace on RSS2 feeds.
 *
 * rss2_ns is an action that prints attributes onto the <rss> root.
 * Returning a string from a filter is discarded, so this must echo.
 */
function dh_feed_media_namespace() {
    echo ' xmlns:media="http://search.yahoo.com/mrss/"';
}
add_action('rss2_ns', 'dh_feed_media_namespace');

/**
 * Declare the Media RSS namespace on Atom feeds.
 *
 * atom_ns is an action that prints attributes onto the <feed> root.
 */
function dh_feed_atom_media_namespace() {
    echo ' xmlns:media="http://search.yahoo.com/mrss/"';
}
add_action('atom_ns', 'dh_feed_atom_media_namespace');
