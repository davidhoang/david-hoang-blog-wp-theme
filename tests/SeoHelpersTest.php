<?php

use PHPUnit\Framework\TestCase;

class SeoHelpersTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    public function test_search_canonical_uses_search_url() {
        $GLOBALS['dh_test']['is_search'] = true;
        $GLOBALS['dh_test']['search_query'] = 'design systems';

        $this->assertSame(
            'https://example.com/?s=design%20systems',
            dh_get_canonical_url()
        );
    }

    public function test_social_title_describes_search_and_404_views() {
        $GLOBALS['dh_test']['is_search'] = true;
        $GLOBALS['dh_test']['search_query'] = 'craft';
        $this->assertSame('Search results for “craft”', dh_get_social_title());

        $GLOBALS['dh_test']['is_search'] = false;
        $GLOBALS['dh_test']['is_404'] = true;
        $this->assertSame('Page not found', dh_get_social_title());
    }
}
