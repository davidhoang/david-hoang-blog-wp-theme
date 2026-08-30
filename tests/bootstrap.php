<?php
/**
 * Minimal WordPress stubs so theme helpers can be unit-tested without WP core.
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir() . '/');
}

$GLOBALS['dh_test'] = array(
    'is_search'        => false,
    'is_admin'         => false,
    'is_feed'          => false,
    'search_query'     => '',
    'term_link'        => 'https://example.com/series/craft/',
    'term_link_error'  => false,
);

function dh_test_reset() {
    $GLOBALS['dh_test'] = array(
        'is_search'       => false,
        'is_admin'        => false,
        'is_feed'         => false,
        'search_query'    => '',
        'term_link'       => 'https://example.com/series/craft/',
        'term_link_error' => false,
    );
}

function is_search() {
    return !empty($GLOBALS['dh_test']['is_search']);
}

function is_admin() {
    return !empty($GLOBALS['dh_test']['is_admin']);
}

function is_feed() {
    return !empty($GLOBALS['dh_test']['is_feed']);
}

function get_search_query($escaped = true) {
    $query = isset($GLOBALS['dh_test']['search_query']) ? $GLOBALS['dh_test']['search_query'] : '';

    return $escaped ? htmlspecialchars($query, ENT_QUOTES, 'UTF-8') : $query;
}

function add_action() {}

function add_filter() {}

function home_url($path = '') {
    return 'https://example.com' . $path;
}

function get_term_link($term) {
    if (!empty($GLOBALS['dh_test']['term_link_error'])) {
        return new WP_Error('term_link', 'Could not get term link');
    }

    return $GLOBALS['dh_test']['term_link'];
}

function is_wp_error($thing) {
    return $thing instanceof WP_Error;
}

function wp_strip_all_tags($string) {
    return trim(strip_tags((string) $string));
}

function esc_html($text) {
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

if (!class_exists('WP_Error')) {
    class WP_Error {
        public $errors = array();

        public function __construct($code = '', $message = '') {
            if ($code) {
                $this->errors[$code] = array($message);
            }
        }
    }
}

if (!class_exists('WP_Term')) {
    class WP_Term {
        public $taxonomy = '';
        public $name = '';
        public $description = '';
    }
}

if (!class_exists('WP_Query')) {
    class WP_Query {
        public $is_main = true;
        public $taxonomy = 'series';
        public $vars = array();

        public function is_main_query() {
            return (bool) $this->is_main;
        }

        public function is_tax($taxonomy = '') {
            if ('' === $taxonomy) {
                return '' !== $this->taxonomy;
            }

            return $this->taxonomy === $taxonomy;
        }

        public function set($key, $value) {
            $this->vars[$key] = $value;
        }
    }
}

$theme_inc = dirname(__DIR__) . '/wp-content/themes/dh/inc';

require_once $theme_inc . '/search-highlight.php';
require_once $theme_inc . '/editorial-structure.php';
require_once $theme_inc . '/seo.php';
require_once $theme_inc . '/content-discovery.php';
