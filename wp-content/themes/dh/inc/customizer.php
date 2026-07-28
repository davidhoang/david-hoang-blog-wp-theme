<?php
/**
 * Theme Customizer settings.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Customizer sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function dh_customizer_register($wp_customize) {
    $wp_customize->add_section('dh_sidebar', array(
        'title'       => esc_html__('Sidebar', 'dh'),
        'description' => esc_html__('Default sidebar content shown when no widgets are assigned.', 'dh'),
        'priority'    => 35,
    ));

    $wp_customize->add_setting('dh_sidebar_title', array(
        'default'           => __("I'm David", 'dh'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('dh_sidebar_title', array(
        'label'   => esc_html__('Sidebar heading', 'dh'),
        'section' => 'dh_sidebar',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('dh_sidebar_bio', array(
        'default'           => __("This is my personal blog. In 2025 I'm taking web domains more seriously. Links to other places you'll find me:", 'dh'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('dh_sidebar_bio', array(
        'label'   => esc_html__('Sidebar intro', 'dh'),
        'section' => 'dh_sidebar',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('dh_sidebar_links', array(
        'default'           => "davidhoang.com | https://davidhoang.com\nindieweb.social/@dh | https://indieweb.social/@dh\nLetterboxd | https://letterboxd.com/davidhoang/\nRSS of this blog | {{rss}}",
        'sanitize_callback' => 'dh_sanitize_sidebar_links',
    ));

    $wp_customize->add_control('dh_sidebar_links', array(
        'label'       => esc_html__('Sidebar links', 'dh'),
        'description' => esc_html__('One link per line: Label | URL. Use {{rss}} for the site feed URL.', 'dh'),
        'section'     => 'dh_sidebar',
        'type'        => 'textarea',
    ));

    $wp_customize->add_section('dh_social', array(
        'title'       => esc_html__('Social links', 'dh'),
        'description' => esc_html__('Links shown in the site navigation.', 'dh'),
        'priority'    => 36,
    ));

    $social_defaults = array(
        'dh_social_twitter' => 'https://twitter.com/davidhoang',
        'dh_social_github'  => 'https://github.com/davidhoang',
    );

    foreach ($social_defaults as $setting_id => $default_url) {
        $label = 'dh_social_twitter' === $setting_id
            ? esc_html__('Twitter / X URL', 'dh')
            : esc_html__('GitHub URL', 'dh');

        $wp_customize->add_setting($setting_id, array(
            'default'           => $default_url,
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control($setting_id, array(
            'label'   => $label,
            'section' => 'dh_social',
            'type'    => 'url',
        ));
    }

    $wp_customize->add_section('dh_author', array(
        'title'       => esc_html__('Author (IndieWeb)', 'dh'),
        'description' => esc_html__('h-card markup for the sidebar author profile.', 'dh'),
        'priority'    => 37,
    ));

    $wp_customize->add_setting('dh_author_name', array(
        'default'           => 'David Hoang',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('dh_author_name', array(
        'label'   => esc_html__('Author name', 'dh'),
        'section' => 'dh_author',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('dh_author_url', array(
        'default'           => 'https://davidhoang.com',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('dh_author_url', array(
        'label'   => esc_html__('Author URL', 'dh'),
        'section' => 'dh_author',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('dh_author_photo', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'dh_author_photo', array(
        'label'     => esc_html__('Author photo', 'dh'),
        'section'   => 'dh_author',
        'mime_type' => 'image',
    )));
}
add_action('customize_register', 'dh_customizer_register', 20);

/**
 * Sanitize sidebar link textarea.
 *
 * @param string $value Raw textarea value.
 * @return string
 */
function dh_sanitize_sidebar_links($value) {
    $lines  = preg_split('/\r\n|\r|\n/', (string) $value);
    $clean  = array();

    foreach ($lines as $line) {
        $line = trim($line);

        if ('' === $line) {
            continue;
        }

        $parts = array_map('trim', explode('|', $line, 2));

        if (count($parts) < 2 || '' === $parts[0]) {
            continue;
        }

        $url = '{{rss}}' === $parts[1] ? '{{rss}}' : esc_url_raw($parts[1]);

        if ('{{rss}}' !== $url && '' === $url) {
            continue;
        }

        $clean[] = sanitize_text_field($parts[0]) . ' | ' . $url;
    }

    return implode("\n", $clean);
}

/**
 * Parsed sidebar links from the Customizer.
 *
 * @return array<int, array{label: string, url: string}>
 */
function dh_get_sidebar_links() {
    $raw   = get_theme_mod('dh_sidebar_links', '');
    $lines = preg_split('/\r\n|\r|\n/', (string) $raw);
    $links = array();

    foreach ($lines as $line) {
        $line = trim($line);

        if ('' === $line) {
            continue;
        }

        $parts = array_map('trim', explode('|', $line, 2));

        if (count($parts) < 2) {
            continue;
        }

        $url = '{{rss}}' === $parts[1] ? get_bloginfo('rss2_url') : $parts[1];

        if (!$url) {
            continue;
        }

        $links[] = array(
            'label' => $parts[0],
            'url'   => $url,
        );
    }

    return $links;
}

/**
 * Sidebar fallback content when no widgets are active.
 *
 * @return array{title: string, bio: string, links: array<int, array{label: string, url: string}>}
 */
function dh_get_sidebar_fallback() {
    return array(
        'title' => get_theme_mod('dh_sidebar_title', __("I'm David", 'dh')),
        'bio'   => get_theme_mod('dh_sidebar_bio', __("This is my personal blog. In 2025 I'm taking web domains more seriously. Links to other places you'll find me:", 'dh')),
        'links' => dh_get_sidebar_links(),
    );
}
