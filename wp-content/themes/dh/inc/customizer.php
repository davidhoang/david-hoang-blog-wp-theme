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
 * Default site appearance colors.
 *
 * @return array<string, string>
 */
function dh_get_appearance_defaults() {
    return array(
        'light_text'       => '#333333',
        'light_background' => '#ffffff',
        'light_accent'     => '#333333',
        'dark_text'        => '#e8e6e1',
        'dark_background'  => '#161614',
        'dark_accent'      => '#e8e6e1',
    );
}

/**
 * Current site appearance colors, restricted to safe hex values.
 *
 * @return array<string, string>
 */
function dh_get_appearance_colors() {
    $colors = array();

    foreach (dh_get_appearance_defaults() as $key => $default) {
        $value = (string) get_theme_mod('dh_' . $key, $default);
        $colors[$key] = preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? $value : $default;
    }

    return $colors;
}

/**
 * CSS tokens for one color mode.
 *
 * Supporting surfaces are derived only when the foreground or background is
 * customized, preserving the theme's exact default palette otherwise.
 *
 * @param string $text         Foreground color.
 * @param string $background   Background color.
 * @param string $accent       Accent color.
 * @param string $default_text Default foreground color.
 * @param string $default_bg   Default background color.
 * @return string
 */
function dh_get_appearance_mode_css($text, $background, $accent, $default_text, $default_bg) {
    $css = '--dh-color-text:' . $text . ';--dh-color-bg:' . $background
        . ';--dh-color-accent:' . $accent . ';'
        . '--dh-color-link:var(--dh-color-accent);--dh-focus-ring:var(--dh-color-accent);'
        . '--dh-reading-progress:var(--dh-color-accent);';

    if ($text !== $default_text || $background !== $default_bg) {
        $css .= '--dh-color-muted:color-mix(in srgb,var(--dh-color-text) 68%,var(--dh-color-bg));'
            . '--dh-color-border:color-mix(in srgb,var(--dh-color-text) 18%,var(--dh-color-bg));'
            . '--dh-color-surface:color-mix(in srgb,var(--dh-color-text) 4%,var(--dh-color-bg));'
            . '--dh-chrome-bg:color-mix(in srgb,var(--dh-color-text) 3%,var(--dh-color-bg));'
            . '--dh-sidebar-border:color-mix(in srgb,var(--dh-color-text) 7%,transparent);'
            . '--dh-hero-border:color-mix(in srgb,var(--dh-color-text) 9%,transparent);'
            . '--dh-hero-fade-start:color-mix(in srgb,var(--dh-chrome-bg) 10%,transparent);'
            . '--dh-hero-fade-mid:color-mix(in srgb,var(--dh-chrome-bg) 55%,transparent);'
            . '--dh-hero-fade-end:var(--dh-chrome-bg);--dh-hero-shader-back:var(--dh-chrome-bg);'
            . '--dh-hero-shader-fill:color-mix(in srgb,var(--dh-color-text) 9%,transparent);'
            . '--dh-control-hover-bg:color-mix(in srgb,var(--dh-color-text) 6%,transparent);';
    }

    return $css;
}

/**
 * CSS overrides for Customizer colors.
 *
 * @return string
 */
function dh_get_appearance_css() {
    $colors   = dh_get_appearance_colors();
    $defaults = dh_get_appearance_defaults();
    $light    = dh_get_appearance_mode_css(
        $colors['light_text'],
        $colors['light_background'],
        $colors['light_accent'],
        $defaults['light_text'],
        $defaults['light_background']
    );
    $dark     = dh_get_appearance_mode_css(
        $colors['dark_text'],
        $colors['dark_background'],
        $colors['dark_accent'],
        $defaults['dark_text'],
        $defaults['dark_background']
    );

    return ':root{' . $light . '}[data-theme="dark"]{' . $dark . '}';
}

/**
 * Attach Customizer color overrides after the base stylesheet.
 */
function dh_enqueue_appearance_css() {
    wp_add_inline_style('dh-base', dh_get_appearance_css());
}
add_action('wp_enqueue_scripts', 'dh_enqueue_appearance_css', 20);

/**
 * Available hero density presets.
 *
 * @return array<string, array<string, string>>
 */
function dh_get_hero_density_presets() {
    return array(
        'subtle'   => array(
            'dot-size'          => '1.2',
            'gap-x'             => '20',
            'gap-y'             => '28',
            'opacity-range'     => '0.05',
            'halftone-radius'   => '0.95',
            'halftone-contrast' => '0.34',
        ),
        'balanced' => array(
            'dot-size'          => '1.6',
            'gap-x'             => '16',
            'gap-y'             => '24',
            'opacity-range'     => '0.08',
            'halftone-radius'   => '1.15',
            'halftone-contrast' => '0.42',
        ),
        'bold'     => array(
            'dot-size'          => '2',
            'gap-x'             => '12',
            'gap-y'             => '18',
            'opacity-range'     => '0.12',
            'halftone-radius'   => '1.35',
            'halftone-contrast' => '0.5',
        ),
    );
}

/**
 * Current hero density data attributes.
 *
 * @return array<string, string>
 */
function dh_get_hero_density_settings() {
    $presets = dh_get_hero_density_presets();
    $density = get_theme_mod('dh_hero_density', 'balanced');

    return isset($presets[$density]) ? $presets[$density] : $presets['balanced'];
}

/**
 * Sanitize a hero density preset name.
 *
 * @param string $value Selected preset.
 * @return string
 */
function dh_sanitize_hero_density($value) {
    return isset(dh_get_hero_density_presets()[$value]) ? $value : 'balanced';
}

/**
 * Register Customizer sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function dh_customizer_register($wp_customize) {
    $wp_customize->add_section('dh_appearance', array(
        'title'       => esc_html__('Appearance', 'dh'),
        'description' => esc_html__('A compact palette for the site. Supporting surfaces are generated automatically.', 'dh'),
        'priority'    => 31,
    ));

    $color_controls = array(
        'light_text'       => __('Light text', 'dh'),
        'light_background' => __('Light background', 'dh'),
        'light_accent'     => __('Light accent', 'dh'),
        'dark_text'        => __('Dark text', 'dh'),
        'dark_background'  => __('Dark background', 'dh'),
        'dark_accent'      => __('Dark accent', 'dh'),
    );

    foreach ($color_controls as $key => $label) {
        $setting = 'dh_' . $key;

        $wp_customize->add_setting($setting, array(
            'default'           => dh_get_appearance_defaults()[$key],
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $setting, array(
            'label'   => $label,
            'section' => 'dh_appearance',
        )));
    }

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

    foreach (dh_get_social_networks() as $network) {
        $wp_customize->add_setting($network['setting'], array(
            'default'           => $network['default'],
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control($network['setting'], array(
            'label'   => $network['label'],
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

    $wp_customize->add_section('dh_subscribe', array(
        'title'       => esc_html__('Newsletter', 'dh'),
        'description' => esc_html__('Proof of Concept call to action shown after single posts.', 'dh'),
        'priority'    => 38,
    ));

    $wp_customize->add_setting('dh_subscribe_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));

    $wp_customize->add_control('dh_subscribe_enabled', array(
        'label'   => esc_html__('Show newsletter call to action', 'dh'),
        'section' => 'dh_subscribe',
        'type'    => 'checkbox',
    ));

    $newsletter_fields = array(
        'dh_subscribe_title' => array(
            'label'    => __('Heading', 'dh'),
            'default'  => __('Keep reading with Proof of Concept', 'dh'),
            'type'     => 'text',
            'sanitize' => 'sanitize_text_field',
        ),
        'dh_subscribe_text' => array(
            'label'    => __('Description', 'dh'),
            'default'  => __('Essays on design, technology, and entrepreneurship, delivered by email.', 'dh'),
            'type'     => 'textarea',
            'sanitize' => 'sanitize_textarea_field',
        ),
        'dh_subscribe_label' => array(
            'label'    => __('Button label', 'dh'),
            'default'  => __('Subscribe', 'dh'),
            'type'     => 'text',
            'sanitize' => 'sanitize_text_field',
        ),
    );

    foreach ($newsletter_fields as $setting => $field) {
        $wp_customize->add_setting($setting, array(
            'default'           => $field['default'],
            'sanitize_callback' => $field['sanitize'],
        ));

        $wp_customize->add_control($setting, array(
            'label'   => $field['label'],
            'section' => 'dh_subscribe',
            'type'    => $field['type'],
        ));
    }

    $wp_customize->add_setting('dh_subscribe_url', array(
        'default'           => 'https://www.proofofconcept.pub/',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('dh_subscribe_url', array(
        'label'   => esc_html__('Subscription URL', 'dh'),
        'section' => 'dh_subscribe',
        'type'    => 'url',
    ));
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
