<?php
/**
 * Open Graph, Twitter Card, and JSON-LD output.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Social share / schema image URL.
 *
 * Prefers the featured image on singular views, then the Customizer hero image,
 * then the site icon.
 *
 * @return string
 */
function dh_get_social_image_url() {
    if (is_singular()) {
        $thumbnail_url = get_the_post_thumbnail_url(null, 'large');

        if ($thumbnail_url) {
            return $thumbnail_url;
        }
    }

    $hero_image_url = dh_get_hero_image_url();

    if ($hero_image_url) {
        return $hero_image_url;
    }

    $site_icon_url = get_site_icon_url(512);

    return $site_icon_url ? $site_icon_url : '';
}

/**
 * Canonical URL for the current request.
 *
 * @return string
 */
function dh_get_canonical_url() {
    if (is_singular()) {
        return get_permalink();
    }

    if (is_front_page()) {
        return home_url('/');
    }

    if (is_home()) {
        $posts_page_id = (int) get_option('page_for_posts');

        if ($posts_page_id) {
            return get_permalink($posts_page_id);
        }

        return home_url('/');
    }

    if (is_search()) {
        return get_search_link();
    }

    if (is_post_type_archive()) {
        return get_post_type_archive_link(get_query_var('post_type'));
    }

    if (is_category() || is_tag() || is_tax()) {
        $term_link = get_term_link(get_queried_object());

        return !is_wp_error($term_link) ? $term_link : home_url('/');
    }

    if (is_author()) {
        return get_author_posts_url(get_queried_object_id());
    }

    if (is_day()) {
        return get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
    }

    if (is_month()) {
        return get_month_link(get_query_var('year'), get_query_var('monthnum'));
    }

    if (is_year()) {
        return get_year_link(get_query_var('year'));
    }

    global $wp;
    $request = isset($wp->request) ? $wp->request : '';

    return home_url(user_trailingslashit($request));
}

/**
 * Document title used for social cards (without site name suffix).
 *
 * @return string
 */
function dh_get_social_title() {
    if (is_singular()) {
        return get_the_title();
    }

    if (is_front_page() && is_home()) {
        return get_bloginfo('name', 'display');
    }

    if (is_home()) {
        $posts_page_id = (int) get_option('page_for_posts');

        if ($posts_page_id) {
            return get_the_title($posts_page_id);
        }

        return get_bloginfo('name', 'display');
    }

    if (is_search()) {
        return sprintf(
            /* translators: %s: search query */
            __('Search results for “%s”', 'dh'),
            get_search_query()
        );
    }

    if (is_archive()) {
        return get_the_archive_title();
    }

    if (is_404()) {
        return __('Page not found', 'dh');
    }

    return wp_get_document_title();
}

/**
 * Description for social cards and schema.
 *
 * @return string
 */
function dh_get_social_description() {
    if (is_singular()) {
        $post = get_queried_object();

        if ($post instanceof WP_Post) {
            if (has_excerpt($post)) {
                return wp_strip_all_tags(get_the_excerpt($post));
            }

            $content = wp_strip_all_tags($post->post_content);
            $content = preg_replace('/\s+/', ' ', $content);

            return wp_html_excerpt(trim($content), 160, '&hellip;');
        }
    }

    if (is_category() || is_tag() || is_tax()) {
        $description = term_description();

        if ($description) {
            return wp_strip_all_tags($description);
        }
    }

    if (is_author()) {
        $author = get_queried_object();

        if ($author instanceof WP_User && $author->description) {
            return wp_strip_all_tags($author->description);
        }
    }

    return dh_get_tagline();
}

/**
 * Whether a dedicated SEO plugin is already handling meta/schema.
 *
 * @return bool
 */
function dh_has_seo_plugin() {
    return defined('WPSEO_VERSION')
        || defined('RANK_MATH_VERSION')
        || defined('AIOSEO_VERSION')
        || class_exists('AIOSEO\\Plugin\\AIOSEO');
}

/**
 * Print Open Graph and Twitter Card meta tags.
 */
function dh_print_social_meta() {
    if (dh_has_seo_plugin()) {
        return;
    }

    $title       = dh_get_social_title();
    $description = dh_get_social_description();
    $url         = dh_get_canonical_url();
    $image       = dh_get_social_image_url();
    $site_name   = get_bloginfo('name', 'display');
    $type        = is_singular('post') ? 'article' : 'website';

    if (!$title) {
        return;
    }

    echo "\n<!-- dh social meta -->\n";

    printf('<meta property="og:locale" content="%s">' . "\n", esc_attr(str_replace('-', '_', get_bloginfo('language'))));
    printf('<meta property="og:type" content="%s">' . "\n", esc_attr($type));
    printf('<meta property="og:title" content="%s">' . "\n", esc_attr($title));
    printf('<meta property="og:url" content="%s">' . "\n", esc_url($url));
    printf('<meta property="og:site_name" content="%s">' . "\n", esc_attr($site_name));

    if ($description) {
        printf('<meta property="og:description" content="%s">' . "\n", esc_attr($description));
        printf('<meta name="description" content="%s">' . "\n", esc_attr($description));
    }

    if ($image) {
        printf('<meta property="og:image" content="%s">' . "\n", esc_url($image));
        printf('<meta name="twitter:image" content="%s">' . "\n", esc_url($image));
    }

    if (is_singular('post')) {
        $published = get_the_date(DATE_W3C);
        $modified  = get_the_modified_date(DATE_W3C);

        if ($published) {
            printf('<meta property="article:published_time" content="%s">' . "\n", esc_attr($published));
        }

        if ($modified) {
            printf('<meta property="article:modified_time" content="%s">' . "\n", esc_attr($modified));
        }
    }

    echo '<meta name="twitter:card" content="' . esc_attr($image ? 'summary_large_image' : 'summary') . '">' . "\n";
    printf('<meta name="twitter:title" content="%s">' . "\n", esc_attr($title));

    if ($description) {
        printf('<meta name="twitter:description" content="%s">' . "\n", esc_attr($description));
    }

    echo "<!-- /dh social meta -->\n";
}
add_action('wp_head', 'dh_print_social_meta', 5);

/**
 * Publisher Person node (the site owner), reused across schema graphs.
 *
 * Social profile URLs from the Customizer become `sameAs` entries so search
 * engines can connect the site to its off-site profiles.
 *
 * @return array
 */
function dh_get_person_schema() {
    $person = array(
        '@type' => 'Person',
        '@id'   => home_url('/#person'),
        'name'  => get_bloginfo('name', 'display'),
        'url'   => home_url('/'),
    );

    $same_as = array();

    foreach (dh_get_social_links() as $link) {
        if (!empty($link['url'])) {
            $same_as[] = $link['url'];
        }
    }

    if ($same_as) {
        $person['sameAs'] = array_values(array_unique($same_as));
    }

    return $person;
}

/**
 * Breadcrumb trail for the current request, as name/url pairs.
 *
 * Returns an empty array for views where a breadcrumb adds no value (the front
 * page, the blog index, search, and 404).
 *
 * @return array<int, array{name: string, url: string}>
 */
function dh_get_breadcrumb_items() {
    if (is_front_page() || is_home() || is_search() || is_404()) {
        return array();
    }

    $items = array(
        array(
            'name' => get_bloginfo('name', 'display'),
            'url'  => home_url('/'),
        ),
    );

    if (is_singular('post')) {
        $categories = get_the_category();

        if (!empty($categories)) {
            $primary  = $categories[0];
            $cat_link = get_category_link($primary);

            if (!is_wp_error($cat_link)) {
                $items[] = array(
                    'name' => $primary->name,
                    'url'  => $cat_link,
                );
            }
        }

        $items[] = array(
            'name' => dh_get_display_title(),
            'url'  => get_permalink(),
        );
    } elseif (is_page()) {
        $ancestors = array_reverse(get_post_ancestors(get_queried_object_id()));

        foreach ($ancestors as $ancestor_id) {
            $items[] = array(
                'name' => dh_get_display_title($ancestor_id),
                'url'  => get_permalink($ancestor_id),
            );
        }

        $items[] = array(
            'name' => dh_get_display_title(),
            'url'  => get_permalink(),
        );
    } elseif (is_category() || is_tag() || is_tax() || is_author() || is_date() || is_post_type_archive()) {
        $items[] = array(
            'name' => wp_strip_all_tags(dh_get_social_title()),
            'url'  => dh_get_canonical_url(),
        );
    }

    return count($items) > 1 ? $items : array();
}

/**
 * Build a BreadcrumbList schema node, or null when there is nothing to show.
 *
 * @return array|null
 */
function dh_get_breadcrumb_schema() {
    $items = dh_get_breadcrumb_items();

    if (empty($items)) {
        return null;
    }

    $list = array();
    $position = 1;

    foreach ($items as $item) {
        $list[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $item['name'],
            'item'     => $item['url'],
        );

        $position++;
    }

    return array(
        '@type'           => 'BreadcrumbList',
        '@id'             => dh_get_canonical_url() . '#breadcrumb',
        'itemListElement' => $list,
    );
}

/**
 * Print JSON-LD structured data for the homepage and blog posts.
 */
function dh_print_schema_jsonld() {
    if (dh_has_seo_plugin()) {
        return;
    }

    $graph = array();

    $person = dh_get_person_schema();
    $graph[] = $person;

    $website = array(
        '@type'     => 'WebSite',
        '@id'       => home_url('/#website'),
        'url'       => home_url('/'),
        'name'      => get_bloginfo('name', 'display'),
        'publisher' => array('@id' => $person['@id']),
    );

    $description = dh_get_tagline();

    if ($description) {
        $website['description'] = $description;
    }

    $website['potentialAction'] = array(
        '@type'       => 'SearchAction',
        'target'      => array(
            '@type'       => 'EntryPoint',
            'urlTemplate' => home_url('/?s={search_term_string}'),
        ),
        'query-input' => 'required name=search_term_string',
    );

    $graph[] = $website;

    if (is_home()) {
        $blog_url = dh_get_canonical_url();

        $blog = array(
            '@type'     => 'Blog',
            '@id'       => $blog_url . '#blog',
            'url'       => $blog_url,
            'name'      => dh_get_social_title(),
            'isPartOf'  => array('@id' => home_url('/#website')),
            'publisher' => array('@id' => $person['@id']),
        );

        if ($description) {
            $blog['description'] = $description;
        }

        $graph[] = $blog;
    }

    if (is_singular('post')) {
        $post_id = get_queried_object_id();
        $author  = get_userdata((int) get_post_field('post_author', $post_id));

        $blog_posting = array(
            '@type'            => 'BlogPosting',
            '@id'              => get_permalink($post_id) . '#article',
            'mainEntityOfPage' => get_permalink($post_id),
            'headline'         => get_the_title($post_id),
            'datePublished'    => get_the_date(DATE_W3C, $post_id),
            'dateModified'     => get_the_modified_date(DATE_W3C, $post_id),
            'isPartOf'         => array('@id' => home_url('/#website')),
            'publisher'        => array('@id' => $person['@id']),
        );

        $post_description = dh_get_social_description();

        if ($post_description) {
            $blog_posting['description'] = $post_description;
        }

        $image = dh_get_social_image_url();

        if ($image) {
            $blog_posting['image'] = array($image);
        }

        $word_count = str_word_count(wp_strip_all_tags(get_post_field('post_content', $post_id)));

        if ($word_count > 0) {
            $blog_posting['wordCount'] = $word_count;
        }

        $category_names = wp_get_post_categories($post_id, array('fields' => 'names'));

        if (!empty($category_names) && !is_wp_error($category_names)) {
            $blog_posting['articleSection'] = array_values($category_names);
        }

        $tag_names = wp_get_post_tags($post_id, array('fields' => 'names'));

        if (!empty($tag_names) && !is_wp_error($tag_names)) {
            $blog_posting['keywords'] = array_values($tag_names);
        }

        if ($author instanceof WP_User) {
            $blog_posting['author'] = array(
                '@type' => 'Person',
                'name'  => $author->display_name,
                'url'   => get_author_posts_url($author->ID),
            );
        }

        $graph[] = $blog_posting;
    }

    $breadcrumb = dh_get_breadcrumb_schema();

    if ($breadcrumb) {
        $graph[] = $breadcrumb;
    }

    $payload = array(
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    );

    echo "\n<script type=\"application/ld+json\">";
    echo wp_json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo "</script>\n";
}
add_action('wp_head', 'dh_print_schema_jsonld', 6);
