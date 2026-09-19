<?php
/**
 * Minimal WordPress stubs so theme helpers can be unit-tested without WP core.
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir() . '/');
}

function dh_test_defaults() {
    return array(
        // Conditional tags (all false by default).
        'is_search'             => false,
        'is_admin'              => false,
        'is_feed'               => false,
        'is_front_page'         => false,
        'is_home'               => false,
        'is_404'                => false,
        'is_singular'           => false,
        'is_singular_post'      => false,
        'is_page'               => false,
        'is_attachment'         => false,
        'is_category'           => false,
        'is_tag'                => false,
        'is_tax'                => false,
        'is_author'             => false,
        'is_date'               => false,
        'is_post_type_archive'  => false,
        'is_archive'            => false,
        // Data used by stubs.
        'search_query'          => '',
        'term_link'             => 'https://example.com/series/craft/',
        'term_link_error'       => false,
        'bloginfo_name'         => 'Example Blog',
        'archive_title'         => 'Archive',
        'queried_object'        => null,
        'current_post'          => null,
        'current_id'            => 0,
        'post_tags'             => array(),
        'related_posts'         => array(),
        'get_posts_args'        => array(),
        'filters'               => array(),
        'theme_mods'            => array(),
    );
}

$GLOBALS['dh_test'] = dh_test_defaults();

function dh_test_reset() {
    $GLOBALS['dh_test'] = dh_test_defaults();
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

function __($text) {
    return $text;
}

function get_theme_mod($name, $default = false) {
    return array_key_exists($name, $GLOBALS['dh_test']['theme_mods'])
        ? $GLOBALS['dh_test']['theme_mods'][$name]
        : $default;
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

if (!class_exists('WP_Post')) {
    class WP_Post {
        public $ID = 0;
        public $post_title = '';
        public $post_content = '';
    }
}

function esc_attr($text) {
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function _x($text, $context, $domain = 'default') {
    return $text;
}

function _n($single, $plural, $number, $domain = 'default') {
    return 1 === (int) $number ? $single : $plural;
}

function apply_filters($tag, $value = null) {
    if (isset($GLOBALS['dh_test']['filters'][$tag])) {
        return $GLOBALS['dh_test']['filters'][$tag];
    }

    return $value;
}

function get_post($post = null) {
    if ($post instanceof WP_Post) {
        return $post;
    }

    if (null === $post) {
        return $GLOBALS['dh_test']['current_post'];
    }

    return null;
}

function get_the_ID() {
    return (int) $GLOBALS['dh_test']['current_id'];
}

function wp_get_post_tags($post_id = 0, $args = array()) {
    return $GLOBALS['dh_test']['post_tags'];
}

function get_posts($args = array()) {
    $GLOBALS['dh_test']['get_posts_args'][] = $args;

    return $GLOBALS['dh_test']['related_posts'];
}

function get_bloginfo($show = '', $filter = 'raw') {
    if ('name' === $show) {
        return $GLOBALS['dh_test']['bloginfo_name'];
    }

    return '';
}

function get_queried_object() {
    return $GLOBALS['dh_test']['queried_object'];
}

function get_the_archive_title() {
    return $GLOBALS['dh_test']['archive_title'];
}

function is_front_page() {
    return !empty($GLOBALS['dh_test']['is_front_page']);
}

function is_home() {
    return !empty($GLOBALS['dh_test']['is_home']);
}

function is_404() {
    return !empty($GLOBALS['dh_test']['is_404']);
}

function is_singular($post_types = '') {
    if ('post' === $post_types) {
        return !empty($GLOBALS['dh_test']['is_singular_post']);
    }

    return !empty($GLOBALS['dh_test']['is_singular']) || !empty($GLOBALS['dh_test']['is_singular_post']);
}

function is_page() {
    return !empty($GLOBALS['dh_test']['is_page']);
}

function is_attachment() {
    return !empty($GLOBALS['dh_test']['is_attachment']);
}

function is_category() {
    return !empty($GLOBALS['dh_test']['is_category']);
}

function is_tag() {
    return !empty($GLOBALS['dh_test']['is_tag']);
}

function is_tax() {
    return !empty($GLOBALS['dh_test']['is_tax']);
}

function is_author() {
    return !empty($GLOBALS['dh_test']['is_author']);
}

function is_date() {
    return !empty($GLOBALS['dh_test']['is_date']);
}

function is_post_type_archive() {
    return !empty($GLOBALS['dh_test']['is_post_type_archive']);
}

function is_archive() {
    return !empty($GLOBALS['dh_test']['is_archive']);
}

$theme_inc = dirname(__DIR__) . '/wp-content/themes/dh/inc';

require_once $theme_inc . '/search-highlight.php';
require_once $theme_inc . '/editorial-structure.php';
require_once $theme_inc . '/seo.php';
require_once $theme_inc . '/content-discovery.php';
require_once $theme_inc . '/post-actions.php';
require_once $theme_inc . '/customizer.php';
require_once $theme_inc . '/theme-mode.php';
require_once $theme_inc . '/reading-time.php';
require_once $theme_inc . '/related-posts.php';
