<?php

use PHPUnit\Framework\TestCase;

class ContentDiscoveryTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
        $GLOBALS['dh_test']['queried_object_id'] = 12;
    }

    public function test_category_archive_uses_its_own_feed() {
        $GLOBALS['dh_test']['is_category'] = true;

        $this->assertSame('https://example.com/category/12/feed/', dh_get_context_feed_url());
        $this->assertTrue(dh_should_show_archive_meta());
    }

    public function test_series_archive_uses_taxonomy_feed() {
        $term = new WP_Term();
        $term->term_id = 8;
        $term->taxonomy = 'series';
        $GLOBALS['dh_test']['is_tax'] = true;
        $GLOBALS['dh_test']['queried_object'] = $term;

        $this->assertSame('https://example.com/series/8/feed/', dh_get_context_feed_url());
    }

    public function test_search_uses_search_feed_without_archive_meta() {
        $GLOBALS['dh_test']['is_search'] = true;

        $this->assertSame('https://example.com/search/feed/', dh_get_context_feed_url());
        $this->assertFalse(dh_should_show_archive_meta());
    }

    public function test_default_context_uses_site_feed() {
        $this->assertSame('https://example.com/feed/', dh_get_context_feed_url());
    }
}
