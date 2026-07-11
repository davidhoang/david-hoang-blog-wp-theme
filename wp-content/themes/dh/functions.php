<?php
/**
 * dh theme functions.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup.
 */
function dh_setup() {
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

    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'dh'),
    ));

    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'dh'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Widgets shown beside posts.', 'dh'),
        'before_widget' => '<section id="%1$s" class="widget %2$s sidebar-section">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="sidebar-title widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('after_setup_theme', 'dh_setup');

/**
 * Site tagline shown below the title.
 */
function dh_get_tagline() {
    $tagline = get_bloginfo('description', 'display');

    if ($tagline) {
        return $tagline;
    }

    return __('Personal musings and my expressions on the internet.', 'dh');
}

/**
 * Default primary menu when none is assigned in WordPress.
 */
function dh_default_menu() {
    $items = array(
        array(
            'label' => __('About', 'dh'),
            'url'   => home_url('/about/'),
        ),
        array(
            'label' => 'davidhoang.com',
            'url'   => 'https://davidhoang.com',
        ),
        array(
            'label' => __('Proof of Concept', 'dh'),
            'url'   => 'https://www.proofofconcept.pub',
        ),
    );

    echo '<ul id="primary-menu" class="nav-links">';

    foreach ($items as $item) {
        printf(
            '<li class="menu-item"><a href="%s">%s</a></li>',
            esc_url($item['url']),
            esc_html($item['label'])
        );
    }

    echo '</ul>';
}

/**
 * Render the site header navigation.
 */
function dh_render_primary_menu() {
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'menu_id'        => 'primary-menu',
        'menu_class'     => 'nav-links',
        'container'      => false,
        'fallback_cb'    => 'dh_default_menu',
        'depth'          => 1,
    ));
}

/**
 * Enqueue theme assets.
 */
function dh_scripts() {
    wp_enqueue_style(
        'dh-font-geist',
        'https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap',
        array(),
        null
    );

    wp_enqueue_style('dh-style', get_stylesheet_uri(), array('dh-font-geist'), '0.4.1');
}
add_action('wp_enqueue_scripts', 'dh_scripts');

/**
 * Post meta line: posted in Category on Date by Author.
 */
function dh_entry_meta() {
    $parts = array();

    $categories = get_the_category();
    if (!empty($categories)) {
        $category_links = array();
        foreach ($categories as $category) {
            $category_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
        }

        $parts[] = sprintf(
            '<span class="post-category">%s %s</span>',
            esc_html__('posted in', 'dh'),
            implode(', ', $category_links)
        );
    }

    $parts[] = sprintf(
        '<span class="post-date">%s <a href="%s" rel="bookmark"><time datetime="%s">%s</time></a></span>',
        esc_html__('on', 'dh'),
        esc_url(get_permalink()),
        esc_attr(get_the_date('c')),
        esc_html(get_the_date())
    );

    $parts[] = sprintf(
        '<span class="post-author">%s <a href="%s">%s</a></span>',
        esc_html__('by', 'dh'),
        esc_url(get_author_posts_url(get_the_author_meta('ID'))),
        esc_html(get_the_author())
    );

    if (comments_open() || get_comments_number()) {
        ob_start();
        comments_popup_link(
            esc_html__('0 Comments', 'dh'),
            esc_html__('1 Comment', 'dh'),
            esc_html__('% Comments', 'dh')
        );
        $parts[] = '<span class="post-comments">' . ob_get_clean() . '</span>';
    }

    echo '<div class="entry-meta">' . implode(' ', $parts) . '</div>';
}
