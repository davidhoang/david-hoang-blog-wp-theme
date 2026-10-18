<?php
/**
 * Generated PNG social cards with caching and theme typography.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Allowed social card color schemes.
 *
 * @return string[]
 */
function dh_get_social_card_schemes() {
    return array('light', 'dark');
}

/**
 * Sanitize a social card scheme slug.
 *
 * @param string $scheme Requested scheme.
 * @return string
 */
function dh_sanitize_social_card_scheme($scheme) {
    $scheme = sanitize_key($scheme);

    return in_array($scheme, dh_get_social_card_schemes(), true) ? $scheme : 'light';
}

/**
 * Active scheme for the current social-card request.
 *
 * @return string
 */
function dh_get_social_card_scheme() {
    $scheme = get_query_var('dh_card_scheme');

    if (!$scheme) {
        return 'light';
    }

    return dh_sanitize_social_card_scheme($scheme);
}

/**
 * Register social-card query vars.
 *
 * @param string[] $vars Public query vars.
 * @return string[]
 */
function dh_social_card_query_vars($vars) {
    $vars[] = 'dh_social_card';
    $vars[] = 'dh_card_scheme';

    return $vars;
}
add_filter('query_vars', 'dh_social_card_query_vars');

/**
 * Path to the bundled serif font used for card titles.
 *
 * @return string
 */
function dh_get_social_card_font_path() {
    $path = get_template_directory() . '/assets/fonts/dh-social-card-serif.ttf';

    return is_readable($path) ? $path : '';
}

/**
 * Directory for cached PNG social cards.
 *
 * @return string
 */
function dh_get_social_card_cache_dir() {
    $uploads = wp_upload_dir();

    if (!empty($uploads['error'])) {
        return '';
    }

    return trailingslashit($uploads['basedir']) . 'dh-social-cards';
}

/**
 * Cache file path for one post + scheme.
 *
 * @param int    $post_id Post ID.
 * @param string $scheme  Color scheme.
 * @return string
 */
function dh_get_social_card_cache_path($post_id, $scheme) {
    $dir = dh_get_social_card_cache_dir();

    if (!$dir) {
        return '';
    }

    $post_id = absint($post_id);
    $scheme  = dh_sanitize_social_card_scheme($scheme);

    return $dir . '/post-' . $post_id . '-' . $scheme . '.png';
}

/**
 * Whether a cached PNG is still fresh for the post.
 *
 * @param string  $path Cache file path.
 * @param WP_Post $post Post object.
 * @return bool
 */
function dh_social_card_cache_is_fresh($path, $post) {
    if (!$path || !is_readable($path) || !($post instanceof WP_Post)) {
        return false;
    }

    $modified = (int) get_post_modified_time('U', true, $post);

    return filemtime($path) >= $modified;
}

/**
 * Split a title into lines for the card canvas.
 *
 * @param string $title     Post title.
 * @param int    $max_chars Characters per line before wrapping.
 * @param int    $max_lines Maximum number of lines.
 * @return string[]
 */
function dh_social_card_title_lines($title, $max_chars = 34, $max_lines = 4) {
    $title = trim(html_entity_decode(wp_strip_all_tags((string) $title), ENT_QUOTES, get_bloginfo('charset')));

    if ('' === $title) {
        $title = __('Untitled', 'dh');
    }

    $wrapped = explode("\n", wordwrap($title, max(12, (int) $max_chars), "\n", true));

    return array_slice($wrapped, 0, max(1, (int) $max_lines));
}

/**
 * Colors for one card scheme.
 *
 * @param string $scheme Color scheme.
 * @return array{background: int[], foreground: int[], accent: int[]}
 */
function dh_get_social_card_palette($scheme) {
    $colors = dh_get_appearance_colors();
    $scheme = dh_sanitize_social_card_scheme($scheme);

    if ('dark' === $scheme) {
        return array(
            'background' => sscanf($colors['dark_background'], '#%02x%02x%02x'),
            'foreground' => sscanf($colors['dark_text'], '#%02x%02x%02x'),
            'accent'     => sscanf($colors['dark_accent'], '#%02x%02x%02x'),
        );
    }

    return array(
        'background' => sscanf($colors['light_background'], '#%02x%02x%02x'),
        'foreground' => sscanf($colors['light_text'], '#%02x%02x%02x'),
        'accent'     => sscanf($colors['light_accent'], '#%02x%02x%02x'),
    );
}

/**
 * Draw a social card and return a GD image handle.
 *
 * @param WP_Post $post   Post object.
 * @param string  $scheme Color scheme.
 * @return resource|null
 */
function dh_build_social_card_image($post, $scheme = 'light') {
    if (!function_exists('imagecreatetruecolor') || !($post instanceof WP_Post)) {
        return null;
    }

    $width  = 1200;
    $height = 630;
    $canvas = imagecreatetruecolor($width, $height);

    if (!$canvas) {
        return null;
    }

    $palette = dh_get_social_card_palette($scheme);
    $bg      = imagecolorallocate($canvas, $palette['background'][0], $palette['background'][1], $palette['background'][2]);
    $fg      = imagecolorallocate($canvas, $palette['foreground'][0], $palette['foreground'][1], $palette['foreground'][2]);
    $accent  = imagecolorallocate($canvas, $palette['accent'][0], $palette['accent'][1], $palette['accent'][2]);

    imagefilledrectangle($canvas, 0, 0, $width, $height, $bg);
    imagefilledrectangle($canvas, 72, 70, 78, 560, $accent);

    $title = get_the_title($post);
    $lines = dh_social_card_title_lines($title);
    $font  = dh_get_social_card_font_path();
    $y     = 150;

    if ($font && function_exists('imagettftext')) {
        $title_size = 52;

        foreach ($lines as $line) {
            imagettftext($canvas, $title_size, 0, 120, $y, $fg, $font, $line);
            $y += 72;
        }

        imagettftext($canvas, 28, 0, 120, 500, $fg, $font, get_bloginfo('name', 'display'));
        imagettftext($canvas, 22, 0, 120, 545, $fg, $font, (string) wp_parse_url(home_url('/'), PHP_URL_HOST));
    } else {
        $y = 120;

        foreach ($lines as $line) {
            imagestring($canvas, 5, 120, $y, $line, $fg);
            $y += 36;
        }

        imagestring($canvas, 3, 120, 490, get_bloginfo('name', 'display'), $fg);
        imagestring($canvas, 2, 120, 530, (string) wp_parse_url(home_url('/'), PHP_URL_HOST), $fg);
    }

    return $canvas;
}

/**
 * Write a PNG social card to disk.
 *
 * @param WP_Post $post   Post object.
 * @param string  $scheme Color scheme.
 * @return string Saved file path, or empty string on failure.
 */
function dh_write_social_card_cache($post, $scheme = 'light') {
    $path = dh_get_social_card_cache_path($post->ID, $scheme);

    if (!$path) {
        return '';
    }

    $dir = dirname($path);

    if (!wp_mkdir_p($dir)) {
        return '';
    }

    $canvas = dh_build_social_card_image($post, $scheme);

    if (!$canvas) {
        return '';
    }

    $saved = imagepng($canvas, $path, 8);
    imagedestroy($canvas);

    return $saved ? $path : '';
}

/**
 * Public URL for a generated PNG social card.
 *
 * @param int    $post_id Post ID.
 * @param string $scheme  Optional color scheme.
 * @return string
 */
function dh_get_generated_social_card_url($post_id, $scheme = 'light') {
    $post = get_post($post_id);

    if (!$post) {
        return '';
    }

    $args = array(
        'dh_social_card' => (int) $post->ID,
        'v'              => (int) get_post_modified_time('U', true, $post),
    );

    $scheme = dh_sanitize_social_card_scheme($scheme);

    if ('light' !== $scheme) {
        $args['dh_card_scheme'] = $scheme;
    }

    return add_query_arg($args, home_url('/'));
}

/**
 * Render (or serve cached) social card PNG for the current request.
 */
function dh_render_social_card() {
    $post_id = absint(get_query_var('dh_social_card'));

    if (!$post_id) {
        return;
    }

    $post = get_post($post_id);

    if (!$post || 'publish' !== $post->post_status || !function_exists('imagecreatetruecolor')) {
        status_header(404);
        exit;
    }

    $scheme = dh_get_social_card_scheme();
    $path   = dh_get_social_card_cache_path($post_id, $scheme);

    if (!$path || !dh_social_card_cache_is_fresh($path, $post)) {
        $path = dh_write_social_card_cache($post, $scheme);
    }

    if ($path && is_readable($path)) {
        $modified = (int) get_post_modified_time('U', true, $post);
        header('Content-Type: image/png');
        header('Content-Disposition: inline; filename="dh-' . $post_id . '-' . $scheme . '-social-card.png"');
        header('Cache-Control: public, max-age=31536000, immutable');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $modified) . ' GMT');
        readfile($path);
        exit;
    }

    $canvas = dh_build_social_card_image($post, $scheme);

    if (!$canvas) {
        status_header(404);
        exit;
    }

    nocache_headers();
    header('Content-Type: image/png');
    header('Content-Disposition: inline; filename="dh-' . $post_id . '-' . $scheme . '-social-card.png"');
    imagepng($canvas, null, 8);
    imagedestroy($canvas);
    exit;
}
add_action('template_redirect', 'dh_render_social_card', 0);
