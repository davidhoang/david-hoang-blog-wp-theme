<?php
/**
 * Localhost-only DINAMO trial font switcher.
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
 * Map DINAMO filename tokens to font weights.
 */
function dh_dev_font_weight_from_filename($filename) {
    $weights = array(
        'Hairline'   => 100,
        'Thin'       => 100,
        'ExtraLight' => 200,
        'Light'      => 300,
        'Book'       => 400,
        'Regular'    => 400,
        'Medium'     => 500,
        'SemiBold'   => 600,
        'Bold'       => 700,
        'ExtraBold'  => 800,
        'Heavy'      => 800,
        'Black'      => 900,
    );

    foreach ($weights as $token => $weight) {
        if (false !== strpos($filename, $token)) {
            return $weight;
        }
    }

    return 400;
}

/**
 * Build a CSS font-family label from a variant folder name.
 */
function dh_dev_font_family_name($variant_name) {
    $name = trim($variant_name);

    if (substr($name, -6) !== ' Trial') {
        $name .= ' Trial';
    }

    return $name;
}

/**
 * Score variant folders so base styles appear first in the switcher.
 */
function dh_dev_font_variant_sort_score($family_name, $variant_name) {
    $score = 100;
    $base = 'ABC ' . $family_name;

    if ($variant_name === $base) {
        return 0;
    }

    if ($variant_name === $base . ' Variable') {
        return 1;
    }

    if (false !== strpos($variant_name, 'Variable')) {
        $score += 5;
    }

    if (false !== strpos($variant_name, 'Mono')) {
        $score += 20;
    }

    foreach (array('Greek', 'Cyrillic', 'Arabic', 'Armenian', 'Hangul', 'Thai', 'Georgian', 'Devanagari', 'Tifinagh') as $script) {
        if (false !== strpos($variant_name, $script)) {
            $score += 40;
        }
    }

    return $score + strlen($variant_name);
}

/**
 * Scan DINAMO trial fonts into a manifest for the switcher UI.
 */
function dh_get_dev_font_manifest() {
    static $manifest = null;

    if (null !== $manifest) {
        return $manifest;
    }

    $manifest = array(
        'fonts' => array(),
    );

    $root = dh_get_dev_fonts_root();

    if (!$root) {
        return $manifest;
    }

    $families = array_diff(scandir($root), array('.', '..'));
    sort($families, SORT_NATURAL | SORT_FLAG_CASE);

    foreach ($families as $family_name) {
        $family_path = $root . '/' . $family_name;

        if (!is_dir($family_path)) {
            continue;
        }

        $variants = array_diff(scandir($family_path), array('.', '..'));
        $variant_entries = array();

        foreach ($variants as $variant_name) {
            $variant_path = $family_path . '/' . $variant_name;

            if (!is_dir($variant_path)) {
                continue;
            }

            $files = array();
            $is_variable = false;

            foreach (array_diff(scandir($variant_path), array('.', '..')) as $filename) {
                if (!preg_match('/\.(otf|ttf|woff2?)$/i', $filename)) {
                    continue;
                }

                if (false !== strpos($filename, 'Italic')) {
                    continue;
                }

                if (false !== strpos($filename, 'Variable')) {
                    $is_variable = true;
                }

                $relative = $family_name . '/' . $variant_name . '/' . $filename;

                $files[] = array(
                    'relative' => $relative,
                    'url'      => home_url('/?dh_dev_font=' . rawurlencode($relative)),
                    'weight'   => dh_dev_font_weight_from_filename($filename),
                    'style'    => 'normal',
                );
            }

            if (empty($files)) {
                continue;
            }

            usort(
                $files,
                static function ($a, $b) {
                    return $a['weight'] <=> $b['weight'];
                }
            );

            if ($is_variable) {
                $files = array(array(
                    'relative' => $files[0]['relative'],
                    'url'      => $files[0]['url'],
                    'weight'   => 100,
                    'style'    => 'normal',
                    'max'      => 900,
                ));
            } else {
                $preferred = array();
                $seen_weights = array();

                foreach ($files as $file) {
                    if (in_array($file['weight'], array(400, 500, 700), true) && !isset($seen_weights[$file['weight']])) {
                        $preferred[] = $file;
                        $seen_weights[$file['weight']] = true;
                    }
                }

                if (!empty($preferred)) {
                    $files = $preferred;
                } else {
                    $files = array_slice($files, 0, 3);
                }
            }

            $variant_entries[] = array(
                'id'         => sanitize_title($family_name . '-' . $variant_name),
                'family'     => $family_name,
                'variant'    => $variant_name,
                'label'      => $family_name . ' / ' . $variant_name,
                'css_family' => dh_dev_font_family_name($variant_name),
                'variable'   => $is_variable,
                'files'      => $files,
                'sort'       => dh_dev_font_variant_sort_score($family_name, $variant_name),
            );
        }

        if (empty($variant_entries)) {
            continue;
        }

        usort(
            $variant_entries,
            static function ($a, $b) {
                return $a['sort'] <=> $b['sort'];
            }
        );

        foreach ($variant_entries as $entry) {
            unset($entry['sort']);
            $manifest['fonts'][] = $entry;
        }
    }

    return $manifest;
}

/**
 * Enqueue localhost font switcher assets.
 */
function dh_enqueue_dev_font_switcher() {
    if (!dh_is_local_dev() || !dh_get_dev_fonts_root()) {
        return;
    }

    wp_enqueue_style(
        'dh-font-switcher',
        get_template_directory_uri() . '/css/font-switcher.css',
        array(),
        '0.7.4'
    );

    wp_enqueue_script(
        'dh-font-switcher',
        get_template_directory_uri() . '/js/font-switcher.js',
        array(),
        '0.7.4',
        true
    );

    wp_localize_script(
        'dh-font-switcher',
        'dhFontSwitcher',
        array(
            'storageKey' => 'dh-font-switcher-choice',
            'default'    => dh_get_default_font_switcher_config(),
            'fonts' => dh_get_dev_font_manifest()['fonts'],
        )
    );
}
add_action('wp_enqueue_scripts', 'dh_enqueue_dev_font_switcher', 20);

/**
 * Render the localhost font switcher panel.
 */
function dh_render_dev_font_switcher() {
    if (!dh_is_local_dev() || !dh_get_dev_fonts_root()) {
        return;
    }

    ?>
    <div id="dh-font-switcher" class="dh-font-switcher" hidden>
        <button type="button" class="dh-font-switcher__toggle" aria-expanded="false" aria-controls="dh-font-switcher-panel">
            Fonts
        </button>
        <div id="dh-font-switcher-panel" class="dh-font-switcher__panel" hidden>
            <p class="dh-font-switcher__hint">Hover or focus to preview. Click to apply.</p>

            <p class="dh-font-switcher__label" id="dh-font-switcher-family-label">Family</p>
            <div
                id="dh-font-switcher-family"
                class="dh-font-switcher__list"
                role="listbox"
                aria-labelledby="dh-font-switcher-family-label"
                tabindex="0"
            ></div>

            <p class="dh-font-switcher__label" id="dh-font-switcher-variant-label">Style</p>
            <div
                id="dh-font-switcher-variant"
                class="dh-font-switcher__list"
                role="listbox"
                aria-labelledby="dh-font-switcher-variant-label"
                tabindex="0"
            ></div>

            <p class="dh-font-switcher__meta" id="dh-font-switcher-meta"></p>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'dh_render_dev_font_switcher', 5);
