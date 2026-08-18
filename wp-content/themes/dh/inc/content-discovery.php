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
 * Render a quiet topic browse list on the blog index.
 */
function dh_render_browse_topics() {
    if (!is_home() || is_paged()) {
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
 * @param string $output Existing namespace attributes.
 * @return string
 */
function dh_feed_media_namespace($output) {
    return $output . ' xmlns:media="http://search.yahoo.com/mrss/"';
}
add_filter('rss2_ns', 'dh_feed_media_namespace');

/**
 * Declare the Media RSS namespace on Atom feeds.
 *
 * @param string $output Existing namespace attributes.
 * @return string
 */
function dh_feed_atom_media_namespace($output) {
    return $output . ' xmlns:media="http://search.yahoo.com/mrss/"';
}
add_filter('atom_ns', 'dh_feed_atom_media_namespace');
