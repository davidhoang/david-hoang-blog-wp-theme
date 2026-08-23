<?php
/**
 * Highlight search terms in result titles and excerpts.
 *
 * @package dh
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Whether search term highlighting should run for the current request.
 *
 * @return bool
 */
function dh_search_highlight_is_active() {
    return is_search() && !is_admin() && !is_feed();
}

/**
 * Allowed markup when printing highlighted text.
 *
 * @return array<string, array<string, bool>>
 */
function dh_search_highlight_allowed_html() {
    return array(
        'mark' => array(
            'class' => true,
        ),
    );
}

/**
 * Search terms to highlight, longest first.
 *
 * @return string[]
 */
function dh_get_search_highlight_terms() {
    $query = trim((string) get_search_query(false));

    if ('' === $query) {
        return array();
    }

    $parts = preg_split('/\s+/u', $query, -1, PREG_SPLIT_NO_EMPTY);

    if (!is_array($parts) || empty($parts)) {
        $parts = array($query);
    }

    $parts = array_values(array_unique($parts));
    $min_length = (1 === count($parts)) ? 1 : 2;
    $terms = array();

    foreach ($parts as $part) {
        if (strlen($part) >= $min_length) {
            $terms[] = $part;
        }
    }

    usort(
        $terms,
        function ($a, $b) {
            return strlen($b) - strlen($a);
        }
    );

    return $terms;
}

/**
 * Regular expression that matches any highlight term.
 *
 * @return string
 */
function dh_get_search_highlight_pattern() {
    $terms = dh_get_search_highlight_terms();

    if (empty($terms)) {
        return '';
    }

    $quoted = array();

    foreach ($terms as $term) {
        $quoted[] = preg_quote($term, '/');
    }

    return '/(' . implode('|', $quoted) . ')/iu';
}

/**
 * Wrap matching terms in a mark element. Input must already be escaped text.
 *
 * @param string $escaped_text Escaped plain text.
 * @return string
 */
function dh_highlight_escaped_text($escaped_text) {
    $pattern = dh_get_search_highlight_pattern();

    if ('' === $pattern || '' === $escaped_text) {
        return $escaped_text;
    }

    $highlighted = preg_replace_callback(
        $pattern,
        function ($matches) {
            return '<mark class="search-highlight">' . $matches[0] . '</mark>';
        },
        $escaped_text
    );

    if (null === $highlighted) {
        return $escaped_text;
    }

    return $highlighted;
}

/**
 * Highlight terms in HTML by wrapping matches in text nodes only.
 *
 * @param string $html HTML fragment.
 * @return string
 */
function dh_highlight_search_in_html($html) {
    if (!dh_search_highlight_is_active() || '' === $html) {
        return $html;
    }

    $pattern = dh_get_search_highlight_pattern();

    if ('' === $pattern) {
        return $html;
    }

    $highlighted = preg_replace_callback(
        '/(<[^>]+>)|([^<]+)/',
        function ($matches) use ($pattern) {
            if ('' !== $matches[1]) {
                return $matches[1];
            }

            $replaced = preg_replace_callback(
                $pattern,
                function ($term_matches) {
                    return '<mark class="search-highlight">' . $term_matches[0] . '</mark>';
                },
                $matches[2]
            );

            return null === $replaced ? $matches[2] : $replaced;
        },
        $html
    );

    return null === $highlighted ? $html : $highlighted;
}

/**
 * Escaped title with search matches marked.
 *
 * @param int|WP_Post|null $post Post to title.
 * @return string
 */
function dh_get_highlighted_display_title($post = null) {
    $title = esc_html(dh_get_display_title($post));

    if (!dh_search_highlight_is_active()) {
        return $title;
    }

    return dh_highlight_escaped_text($title);
}

/**
 * Highlight matches inside the excerpt on search result views.
 *
 * @param string $excerpt Excerpt HTML.
 * @return string
 */
function dh_highlight_search_excerpt($excerpt) {
    if (!dh_search_highlight_is_active() || !in_the_loop()) {
        return $excerpt;
    }

    return dh_highlight_search_in_html($excerpt);
}
add_filter('the_excerpt', 'dh_highlight_search_excerpt', 20);
