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
    // Enqueue EB Garamond font from Google Fonts
    wp_enqueue_style('david-hoang-eb-garamond', 'https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap', array(), null);
    
    // Enqueue main stylesheet
    wp_enqueue_style('david-hoang-style', get_stylesheet_uri(), array('david-hoang-eb-garamond'), '1.0.0');
    
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
        'description'   => esc_html__('Add widgets here.', 'david-hoang'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
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
