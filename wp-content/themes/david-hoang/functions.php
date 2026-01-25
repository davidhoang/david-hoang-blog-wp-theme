<?php
/**
 * David Hoang Theme Functions
 *
 * @package DavidHoang
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup
 */
function david_hoang_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('automatic-feed-links');
    add_theme_support('customize-selective-refresh-widgets');
    
    // Set content width
    $GLOBALS['content_width'] = 800;
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary', 'david-hoang'),
    ));
}
add_action('after_setup_theme', 'david_hoang_setup');

/**
 * Enqueue Scripts and Styles
 */
function david_hoang_scripts() {
    // Enqueue main stylesheet (ABC Diatype fonts are self-hosted in /fonts/)
    wp_enqueue_style('david-hoang-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Parallax script removed - featured images are now inline in post content
    
    // Enqueue mobile menu script
    wp_enqueue_script('david-hoang-mobile-menu', get_template_directory_uri() . '/js/mobile-menu.js', array(), '1.0.0', true);

    // Enqueue theme toggle script
    wp_enqueue_script('david-hoang-theme-toggle', get_template_directory_uri() . '/js/theme-toggle.js', array(), '1.0.0', true);

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'david_hoang_scripts');

/**
 * Register Widget Areas
 */
function david_hoang_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'david-hoang'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in the sidebar.', 'david-hoang'),
        'before_widget' => '<div id="%1$s" class="widget %2$s sidebar-section">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="sidebar-title widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'david_hoang_widgets_init');

/**
 * Custom Excerpt Length
 */
function david_hoang_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'david_hoang_excerpt_length');

/**
 * Custom Excerpt More
 */
function david_hoang_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'david_hoang_excerpt_more');

/**
 * Get Post Date Formatted
 */
function david_hoang_posted_on() {
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
    
    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date('c')),
        esc_html(get_the_date())
    );
    
    return $time_string;
}

/**
 * Get Post Categories
 */
function david_hoang_post_categories() {
    $categories = get_the_category();
    if (empty($categories)) {
        return '';
    }
    
    $output = '<span class="post-categories">';
    foreach ($categories as $category) {
        $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
        if ($category !== end($categories)) {
            $output .= ', ';
        }
    }
    $output .= '</span>';
    
    return $output;
}

/**
 * Remove Home link from navigation menu
 */
function david_hoang_remove_home_from_menu($items, $args) {
    if ($args->theme_location == 'primary') {
        foreach ($items as $key => $item) {
            // Remove items that link to the home page
            if ($item->url == home_url('/') || $item->url == home_url()) {
                unset($items[$key]);
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'david_hoang_remove_home_from_menu', 10, 2);

/**
 * Preload Critical Fonts
 */
function david_hoang_preload_fonts() {
    ?>
    <link rel="preload" href="<?php echo esc_url(get_template_directory_uri()); ?>/fonts/ABCDiatypeVariable.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo esc_url(get_template_directory_uri()); ?>/fonts/ABCDiatypeMonoVariable.woff2" as="font" type="font/woff2" crossorigin>
    <?php
}
add_action('wp_head', 'david_hoang_preload_fonts', 1);

/**
 * Add Open Graph and Twitter Card Meta Tags
 */
function david_hoang_social_meta_tags() {
    $title = '';
    $description = '';
    $image = '';
    $url = '';
    $type = 'website';

    if (is_singular()) {
        global $post;
        $title = get_the_title();
        $description = has_excerpt() ? get_the_excerpt() : wp_trim_words(strip_tags($post->post_content), 30);
        $url = get_permalink();
        $type = is_single() ? 'article' : 'website';

        if (has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url($post->ID, 'large');
        }
    } elseif (is_home() || is_front_page()) {
        $title = get_bloginfo('name');
        $description = get_bloginfo('description');
        $url = home_url('/');
    } elseif (is_archive()) {
        $title = get_the_archive_title();
        $description = get_the_archive_description() ?: get_bloginfo('description');
        $url = get_permalink();
    }

    if (empty($description)) {
        $description = get_bloginfo('description');
    }

    $description = wp_strip_all_tags($description);
    $description = str_replace(array("\r", "\n"), ' ', $description);
    ?>

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:type" content="<?php echo esc_attr($type); ?>">
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
    <?php if ($image) : ?>
    <meta property="og:image" content="<?php echo esc_url($image); ?>">
    <?php endif; ?>

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="<?php echo $image ? 'summary_large_image' : 'summary'; ?>">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <?php if ($image) : ?>
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>">
    <?php endif; ?>

    <?php
}
add_action('wp_head', 'david_hoang_social_meta_tags', 5);

/**
 * Add Canonical URL
 */
function david_hoang_canonical_url() {
    if (is_singular()) {
        $url = get_permalink();
    } elseif (is_home() || is_front_page()) {
        $url = home_url('/');
    } elseif (is_category() || is_tag() || is_tax()) {
        $url = get_term_link(get_queried_object());
    } elseif (is_author()) {
        $url = get_author_posts_url(get_queried_object_id());
    } elseif (is_archive()) {
        $url = get_permalink();
    } else {
        $url = home_url($_SERVER['REQUEST_URI']);
    }

    if (!is_wp_error($url)) {
        echo '<link rel="canonical" href="' . esc_url($url) . '">' . "\n";
    }
}
add_action('wp_head', 'david_hoang_canonical_url', 5);

/**
 * Add Schema.org Structured Data
 */
function david_hoang_schema_markup() {
    if (is_singular('post')) {
        global $post;
        $author = get_the_author();
        $date_published = get_the_date('c');
        $date_modified = get_the_modified_date('c');
        $title = get_the_title();
        $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(strip_tags($post->post_content), 30);
        $url = get_permalink();
        $image = has_post_thumbnail() ? get_the_post_thumbnail_url($post->ID, 'large') : '';

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $title,
            'description' => wp_strip_all_tags($excerpt),
            'url' => $url,
            'datePublished' => $date_published,
            'dateModified' => $date_modified,
            'author' => array(
                '@type' => 'Person',
                'name' => $author,
            ),
            'publisher' => array(
                '@type' => 'Person',
                'name' => get_bloginfo('name'),
            ),
        );

        if ($image) {
            $schema['image'] = $image;
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    } elseif (is_home() || is_front_page()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url('/'),
        );

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'david_hoang_schema_markup', 10);

/**
 * Add Lazy Loading to Images
 */
function david_hoang_lazy_load_images($content) {
    if (is_admin() || is_feed()) {
        return $content;
    }

    // Add loading="lazy" to images that don't already have it
    $content = preg_replace(
        '/<img((?!loading=)[^>]*)>/i',
        '<img$1 loading="lazy">',
        $content
    );

    return $content;
}
add_filter('the_content', 'david_hoang_lazy_load_images');
add_filter('post_thumbnail_html', 'david_hoang_lazy_load_images');

/**
 * Add Lazy Loading Attribute to Post Thumbnails
 */
function david_hoang_lazy_load_thumbnail_attr($attr) {
    $attr['loading'] = 'lazy';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'david_hoang_lazy_load_thumbnail_attr');

/**
 * Custom Comment Callback
 */
function david_hoang_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    
    if ('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type) :
        ?>
        <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
            <p>
                <?php esc_html_e('Pingback:', 'david-hoang'); ?> <?php comment_author_link(); ?>
                <?php edit_comment_link(esc_html__('(Edit)', 'david-hoang'), '<span class="edit-link">', '</span>'); ?>
            </p>
        <?php
    else :
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <div id="comment-<?php comment_ID(); ?>" class="comment-body">
                <div class="comment-author">
                    <?php echo get_avatar($comment, 60); ?>
                </div>
                
                <div class="comment-content-wrapper">
                    <div class="comment-meta">
                        <cite class="comment-author-name fn">
                            <?php comment_author_link(); ?>
                        </cite>
                        <span class="comment-metadata">
                            <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                <time datetime="<?php comment_time('c'); ?>">
                                    <?php
                                    printf(
                                        esc_html__('%1$s at %2$s', 'david-hoang'),
                                        get_comment_date(),
                                        get_comment_time()
                                    );
                                    ?>
                                </time>
                            </a>
                        </span>
                    </div>
                    
                    <?php if ('0' == $comment->comment_approved) : ?>
                        <p class="comment-awaiting-moderation">
                            <?php esc_html_e('Your comment is awaiting moderation.', 'david-hoang'); ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="comment-content">
                        <?php comment_text(); ?>
                    </div>
                    
                    <div class="comment-footer">
                        <?php edit_comment_link(esc_html__('Edit', 'david-hoang'), '<span class="edit-link">', '</span>'); ?>
                        <?php
                        comment_reply_link(array_merge($args, array(
                            'reply_text' => esc_html__('REPLY', 'david-hoang'),
                            'depth' => $depth,
                            'max_depth' => $args['max_depth'],
                        )));
                        ?>
                    </div>
                </div>
            </div>
        <?php
    endif;
}
