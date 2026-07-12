<?php
/**
 * Theme default typography.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Whether the request is local development.
 */
function dh_is_local_dev() {
    if (defined('WP_ENVIRONMENT_TYPE') && 'local' === WP_ENVIRONMENT_TYPE) {
        return true;
    }

    $host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';

    if (false !== strpos($host, ':')) {
        $host = strtok($host, ':');
    }

    return in_array($host, array('localhost', '127.0.0.1'), true);
}

/**
 * Root path for local trial fonts.
 */
function dh_get_dev_fonts_root() {
    $candidates = array(
        dirname(ABSPATH) . '/_development/DINAMO Trial Fonts',
        ABSPATH . '../_development/DINAMO Trial Fonts',
    );

    foreach ($candidates as $path) {
        $resolved = realpath($path);

        if ($resolved && is_dir($resolved)) {
            return $resolved;
        }
    }

    return '';
}

/**
 * Serve a trial font file on localhost only.
 */
function dh_serve_dev_font() {
    if (empty($_GET['dh_dev_font']) || !dh_is_local_dev()) {
        return;
    }

    $root = dh_get_dev_fonts_root();

    if (!$root) {
        status_header(404);
        exit;
    }

    $relative = rawurldecode((string) wp_unslash($_GET['dh_dev_font']));
    $relative = str_replace('\\', '/', $relative);

    if (
        '' === $relative
        || '/' === $relative[0]
        || false !== strpos($relative, '..')
        || !preg_match('/\.(otf|ttf|woff2?)$/i', $relative)
    ) {
        status_header(400);
        exit;
    }

    $file = realpath($root . '/' . $relative);

    if (!$file || 0 !== strpos($file, $root) || !is_file($file)) {
        status_header(404);
        exit;
    }

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mime_types = array(
        'otf'   => 'font/otf',
        'ttf'   => 'font/ttf',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
    );

    header('Content-Type: ' . ($mime_types[$extension] ?? 'application/octet-stream'));
    header('Cache-Control: private, max-age=3600');
    readfile($file);
    exit;
}
add_action('template_redirect', 'dh_serve_dev_font', 0);

/**
 * Default theme font family name.
 */
function dh_get_theme_font_family() {
    return 'ABC Arizona Text Trial';
}

/**
 * CSS font stack for the theme default.
 */
function dh_get_theme_font_stack() {
    return '"' . dh_get_theme_font_family() . '", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';
}

/**
 * Default font files for ABC Arizona Text.
 */
function dh_get_theme_font_files() {
    $weights = array(
        400 => 'ABCArizonaTextTrial-Regular.otf',
        500 => 'ABCArizonaTextTrial-Medium.otf',
        700 => 'ABCArizonaTextTrial-Bold.otf',
    );

    $files = array();

    foreach ($weights as $weight => $filename) {
        $relative = 'Arizona/ABC Arizona Text/' . $filename;

        $files[] = array(
            'relative' => $relative,
            'url'      => home_url('/?dh_dev_font=' . rawurlencode($relative)),
            'weight'   => $weight,
            'style'    => 'normal',
        );
    }

    return $files;
}

/**
 * Build @font-face CSS for the theme default.
 */
function dh_get_theme_font_face_css() {
    if (!dh_is_local_dev() || !dh_get_dev_fonts_root()) {
        return '';
    }

    $family = dh_get_theme_font_family();
    $rules = array();

    foreach (dh_get_theme_font_files() as $file) {
        $rules[] = sprintf(
            '@font-face { font-family: "%1$s"; src: url("%2$s") format("opentype"); font-weight: %3$d; font-style: %4$s; font-display: swap; }',
            esc_attr($family),
            esc_url($file['url']),
            (int) $file['weight'],
            esc_attr($file['style'])
        );
    }

    return implode("\n", $rules);
}

/**
 * Enqueue the theme default font on localhost.
 */
function dh_enqueue_theme_font() {
    wp_register_style('dh-theme-font', false, array(), '0.8.1');
    wp_enqueue_style('dh-theme-font');

    $css = dh_get_theme_font_face_css();

    if ($css) {
        wp_add_inline_style('dh-theme-font', $css);
    }
}
add_action('wp_enqueue_scripts', 'dh_enqueue_theme_font', 5);

/**
 * Enqueue the theme default font in the block editor on localhost.
 */
function dh_enqueue_theme_font_editor() {
    wp_register_style('dh-theme-font-editor', false, array(), '0.8.1');
    wp_enqueue_style('dh-theme-font-editor');

    $css = dh_get_theme_font_face_css();

    if ($css) {
        wp_add_inline_style('dh-theme-font-editor', $css);
    }
}
add_action('enqueue_block_editor_assets', 'dh_enqueue_theme_font_editor', 5);
