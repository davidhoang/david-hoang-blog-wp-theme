<?php
/**
 * Social icon markup.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Social networks available in the Customizer and site nav.
 *
 * @return array<string, array{setting: string, label: string, nav_label: string, default: string}>
 */
function dh_get_social_networks() {
    return array(
        'twitter' => array(
            'setting'   => 'dh_social_twitter',
            'label'     => esc_html__('Twitter / X URL', 'dh'),
            'nav_label' => __('Twitter', 'dh'),
            'default'   => 'https://twitter.com/davidhoang',
        ),
        'github' => array(
            'setting'   => 'dh_social_github',
            'label'     => esc_html__('GitHub URL', 'dh'),
            'nav_label' => __('GitHub', 'dh'),
            'default'   => 'https://github.com/davidhoang',
        ),
        'mastodon' => array(
            'setting'   => 'dh_social_mastodon',
            'label'     => esc_html__('Mastodon URL', 'dh'),
            'nav_label' => __('Mastodon', 'dh'),
            'default'   => 'https://indieweb.social/@dh',
        ),
        'bluesky' => array(
            'setting'   => 'dh_social_bluesky',
            'label'     => esc_html__('Bluesky URL', 'dh'),
            'nav_label' => __('Bluesky', 'dh'),
            'default'   => '',
        ),
        'linkedin' => array(
            'setting'   => 'dh_social_linkedin',
            'label'     => esc_html__('LinkedIn URL', 'dh'),
            'nav_label' => __('LinkedIn', 'dh'),
            'default'   => '',
        ),
    );
}

/**
 * Return inline SVG markup for a supported social icon.
 */
function dh_get_social_icon_svg($icon) {
    $icons = array(
        'twitter'  => '<svg class="social-links__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false"><path d="M9.52 6.77 15.37 0h-1.39L8.9 5.88 4.94 0H0l6.11 8.9L0 16h1.39l5.52-6.44L11.06 16H16L9.52 6.77ZM7.58 9.35l-.62-.89L1.92 1.04h2.12l3.98 5.7.62.89 5.18 7.41H12.7L7.58 9.35Z"/></svg>',
        'github'   => '<svg class="social-links__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false"><path d="M8 0a8 8 0 0 0-2.53 15.59c.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 8 0Z"/></svg>',
        'mastodon' => '<svg class="social-links__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false"><path d="M14.4 3.4c-.2-1.3-1-2.3-2.2-2.7C10.9.3 8.4.3 8.4.3s-2.5 0-3.8.4C3.4 1.1 2.6 2.1 2.4 3.4 2.3 4.2 2.3 5 2.3 5.8v.9c0 1.5.7 2.8 1.9 3.5.8.4 1.7.6 2.6.5.8-.1 1.6-.3 2.3-.7.1-.1.1-.2 0-.3l-.2-.9c0-.1-.1-.2-.2-.2-.6.2-1.2.3-1.8.3-1.1 0-1.6-.5-1.6-1.4V7.4h3.4V5.9c0-1.2.7-1.8 2-.1.8 0 1.5.3 1.5 1.1v2.4h3.4V6.4c0-.9-.2-1.5-.6-2-.4-.5-.9-.8-1.7-.9-.5-.1-.9-.1-1.3 0-.1 0-.2.1-.2.2v.5c0 .1.1.2.2.2.3 0 .6 0 .9.1.6.1 1 .4 1 1v.4H6.7c-1.3 0-1.9.7-1.9 1.9v1.8c0 1.5.9 2.2 2.5 2.2.8 0 1.5-.2 2-.4.1-.1.2 0 .2.1l.2.9c0 .1 0 .2-.1.3-.7.4-1.5.7-2.3.8-1 0-2-.2-2.9-.6-1.4-.7-2.2-2.2-2.2-3.9v-.9c0-.8 0-1.6.1-2.4.2-1.7 1.2-2.9 2.8-3.4 1.2-.4 2.7-.5 4-.4 1.3.1 2.5.7 3.2 1.7.4.7.6 1.4.6 2.2v3.6c0 1.7-.8 3.2-2.2 3.9-.4.2-.9.3-1.3.4-.1 0-.2-.1-.2-.2l-.2-.9c0-.1 0-.2.1-.3.6-.3 1.2-.4 1.8-.7.1-.1.1-.2 0-.3l-.2-.9c0-.1-.1-.2-.2-.2-.8.3-1.6.5-2.4.5-1.1 0-1.6-.5-1.6-1.4V7.4h3.4V5.9c0-1.2.7-1.8 2-.1.8 0 1.5.3 1.5 1.1v2.4h3.4V6.4c0-.9-.2-1.5-.6-2Z"/></svg>',
        'bluesky'  => '<svg class="social-links__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false"><path d="M3.5 2.2C5.6 4.1 7.8 7.9 8 7.9s2.4-3.8 4.5-5.7c1.3-1.2 3.5-2.1 3.5.8 0 .6-.3 5.2-.5 5.9-.6 2.2-2.9 2.8-4.9 2.4 3.5.6 4.4 2.6 2.5 4.6-3.7 3.8-5.3-1-5.7-2.2-.1-.3-.1-.4-.2-.4s-.1.1-.2.4c-.4 1.2-2 6-5.7 2.2-1.9-2-.9-4 2.5-4.6-2 .4-4.3-.2-4.9-2.4-.2-.7-.5-5.3-.5-5.9 0-2.9 2.2-2 3.5-.8Z"/></svg>',
        'linkedin' => '<svg class="social-links__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false"><path d="M3.6 2.2A1.4 1.4 0 1 1 2.2 3.6 1.4 1.4 0 0 1 3.6 2.2ZM2.5 5.6h2.2v8.2H2.5Zm4.1 0h2.1v1.1h.1c.3-.5 1-1.1 2.1-1.1 2.2 0 2.6 1.5 2.6 3.4v4.8H9.2V9.4c0-.8 0-1.8-1.1-1.8s-1.3.9-1.3 1.8v4.2H4.6Z"/></svg>',
    );

    return isset($icons[$icon]) ? $icons[$icon] : '';
}

/**
 * Return inline SVG markup for the search submit button.
 */
function dh_get_search_icon_svg() {
    return '<svg class="search-submit__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false"><circle cx="6.75" cy="6.75" r="3.75" stroke="currentColor" stroke-width="1.25"/><path d="M9.75 9.75 12.5 12.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"/></svg>';
}
