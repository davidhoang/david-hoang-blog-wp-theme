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
 * Return inline SVG markup for a supported social icon.
 */
function dh_get_social_icon_svg($icon) {
    $icons = array(
        'twitter' => '<svg class="social-links__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false"><path d="M9.52 6.77 15.37 0h-1.39L8.9 5.88 4.94 0H0l6.11 8.9L0 16h1.39l5.52-6.44L11.06 16H16L9.52 6.77ZM7.58 9.35l-.62-.89L1.92 1.04h2.12l3.98 5.7.62.89 5.18 7.41H12.7L7.58 9.35Z"/></svg>',
        'github'  => '<svg class="social-links__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false"><path d="M8 0a8 8 0 0 0-2.53 15.59c.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 8 0Z"/></svg>',
    );

    return isset($icons[$icon]) ? $icons[$icon] : '';
}

/**
 * Return inline SVG markup for the search submit button.
 */
function dh_get_search_icon_svg() {
    return '<svg class="search-submit__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false"><circle cx="6.75" cy="6.75" r="3.75" stroke="currentColor" stroke-width="1.25"/><path d="M9.75 9.75 12.5 12.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"/></svg>';
}
