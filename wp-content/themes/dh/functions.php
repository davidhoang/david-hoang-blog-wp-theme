<?php
/**
 * dh theme functions.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/theme-fonts.php';
require_once get_template_directory() . '/inc/social-icons.php';

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
    add_theme_support('editor-styles');
    add_editor_style(array(
        'editor-style.css',
    ));

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
 * Hero background image URL from the customizer.
 */
function dh_get_hero_image_url() {
    $image_id = (int) get_theme_mod('dh_hero_image', 0);

    if (!$image_id) {
        return '';
    }

    $image_url = wp_get_attachment_image_url($image_id, 'large');

    return $image_url ? $image_url : '';
}

/**
 * Register hero customizer settings.
 */
function dh_customize_register($wp_customize) {
    $wp_customize->add_section('dh_hero', array(
        'title'       => esc_html__('Hero', 'dh'),
        'description' => esc_html__('Upload an image for the halftone dot hero background.', 'dh'),
        'priority'    => 30,
    ));

    $wp_customize->add_setting('dh_hero_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'dh_hero_image', array(
        'label'       => esc_html__('Hero image', 'dh'),
        'description' => esc_html__('Shown as a halftone dot grid behind the site title.', 'dh'),
        'section'     => 'dh_hero',
        'mime_type'   => 'image',
    )));
}
add_action('customize_register', 'dh_customize_register');

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
 * Social links shown in the site nav.
 */
function dh_get_social_links() {
    return array(
        array(
            'label' => __('Twitter', 'dh'),
            'url'   => 'https://twitter.com/davidhoang',
            'icon'  => 'twitter',
        ),
        array(
            'label' => __('GitHub', 'dh'),
            'url'   => 'https://github.com/davidhoang',
            'icon'  => 'github',
        ),
    );
}

/**
 * Render social links for the site nav.
 */
function dh_render_social_links() {
    echo '<ul class="social-links">';

    foreach (dh_get_social_links() as $link) {
        $icon = dh_get_social_icon_svg($link['icon']);

        printf(
            '<li><a href="%s" target="_blank" rel="noopener noreferrer" aria-label="%s">%s</a></li>',
            esc_url($link['url']),
            esc_attr($link['label']),
            $icon
        );
    }

    echo '</ul>';
}

/**
 * Unify core search block markup with the theme search form.
 */
function dh_customize_search_block($block_content, $block) {
    if (empty($block['blockName']) || 'core/search' !== $block['blockName']) {
        return $block_content;
    }

    $block_content = str_replace(
        'wp-block-search__label',
        'wp-block-search__label screen-reader-text',
        $block_content
    );

    $icon = dh_get_search_icon_svg();

    $block_content = preg_replace(
        '/(<button[^>]*class="[^"]*wp-block-search__button[^"]*"[^>]*>)(.*?)(<\/button>)/s',
        '$1' . $icon . '$3',
        $block_content,
        1
    );

    if (false === strpos($block_content, 'placeholder=')) {
        return $block_content;
    }

    return preg_replace(
        '/placeholder=""/',
        'placeholder="' . esc_attr__('Search', 'dh') . '"',
        $block_content,
        1
    );
}
add_filter('render_block', 'dh_customize_search_block', 10, 2);

/**
 * Enqueue theme assets.
 */
function dh_scripts() {
    wp_enqueue_style('dh-style', get_stylesheet_uri(), array('dh-theme-font'), '0.8.4');

    $hero_script = get_template_directory() . '/js/hero-halftone.js';

    if (file_exists($hero_script)) {
        wp_enqueue_script(
            'dh-hero-halftone',
            get_template_directory_uri() . '/js/hero-halftone.js',
            array(),
            '0.8.0',
            true
        );
    }
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

/**
 * Custom comment markup.
 */
function dh_comment($comment, $args, $depth) {
    if ('pingback' === $comment->comment_type || 'trackback' === $comment->comment_type) {
        ?>
        <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
            <p>
                <?php esc_html_e('Pingback:', 'dh'); ?>
                <?php comment_author_link(); ?>
                <?php edit_comment_link(esc_html__('(Edit)', 'dh'), '<span class="edit-link">', '</span>'); ?>
            </p>
        </li>
        <?php
        return;
    }

    $avatar_size = !empty($args['avatar_size']) ? (int) $args['avatar_size'] : 48;
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-avatar">
                <?php echo get_avatar($comment, $avatar_size); ?>
            </div>

            <div class="comment-main">
                <header class="comment-meta">
                    <cite class="comment-author-name">
                        <?php comment_author_link(); ?>
                    </cite>
                    <a class="comment-metadata" href="<?php echo esc_url(get_comment_link($comment)); ?>">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php
                            printf(
                                esc_html__('%1$s at %2$s', 'dh'),
                                get_comment_date(),
                                get_comment_time()
                            );
                            ?>
                        </time>
                    </a>
                </header>

                <?php if ('0' === $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'dh'); ?></p>
                <?php endif; ?>

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>

                <footer class="comment-footer">
                    <?php
                    edit_comment_link(esc_html__('Edit', 'dh'), '<span class="edit-link">', '</span>');
                    comment_reply_link(array_merge($args, array(
                        'reply_text' => esc_html__('Reply', 'dh'),
                        'depth'      => $depth,
                        'max_depth'  => $args['max_depth'],
                        'before'     => '<span class="reply-link">',
                        'after'      => '</span>',
                    )));
                    ?>
                </footer>
            </div>
        </article>
    </li>
    <?php
}
