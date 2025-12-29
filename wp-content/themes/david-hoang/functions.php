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
    
    // Parallax script removed - featured images are now inline in post content
    
    // Enqueue mobile menu script
    wp_enqueue_script('david-hoang-mobile-menu', get_template_directory_uri() . '/js/mobile-menu.js', array(), '1.0.0', true);
    
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
