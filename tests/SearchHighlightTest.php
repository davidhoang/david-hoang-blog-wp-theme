<?php

use PHPUnit\Framework\TestCase;

class SearchHighlightTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
        $GLOBALS['dh_test']['is_search'] = true;
        $GLOBALS['dh_test']['search_query'] = 'Design systems';
    }

    public function test_terms_are_unique_and_longest_first() {
        $GLOBALS['dh_test']['search_query'] = 'design design systems';

        $this->assertSame(
            array('systems', 'design'),
            dh_get_search_highlight_terms()
        );
    }

    public function test_empty_query_has_no_terms() {
        $GLOBALS['dh_test']['search_query'] = '   ';

        $this->assertSame(array(), dh_get_search_highlight_terms());
        $this->assertSame('', dh_get_search_highlight_pattern());
    }

    public function test_single_character_query_is_kept() {
        $GLOBALS['dh_test']['search_query'] = 'a';

        $this->assertSame(array('a'), dh_get_search_highlight_terms());
    }

    public function test_short_words_are_dropped_from_multi_term_queries() {
        $GLOBALS['dh_test']['search_query'] = 'a be craft';

        $this->assertSame(array('craft', 'be'), dh_get_search_highlight_terms());
    }

    public function test_escaped_text_wraps_matches() {
        $html = dh_highlight_escaped_text('On design systems and Design');

        $this->assertStringContainsString('<mark class="search-highlight">design</mark>', $html);
        $this->assertStringContainsString('<mark class="search-highlight">systems</mark>', $html);
        $this->assertStringContainsString('<mark class="search-highlight">Design</mark>', $html);
    }

    public function test_html_highlights_text_nodes_only() {
        $html = dh_highlight_search_in_html('<p class="design">A design <a href="/design">link</a></p>');

        $this->assertStringContainsString('class="design"', $html);
        $this->assertStringContainsString('href="/design"', $html);
        $this->assertStringContainsString('<mark class="search-highlight">design</mark>', $html);
        $this->assertSame(1, substr_count($html, '<mark class="search-highlight">design</mark>'));
    }

    public function test_inactive_outside_search() {
        $GLOBALS['dh_test']['is_search'] = false;
        $source = '<p>design</p>';

        $this->assertSame($source, dh_highlight_search_in_html($source));
    }
}
