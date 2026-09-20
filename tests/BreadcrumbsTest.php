<?php

use PHPUnit\Framework\TestCase;

class BreadcrumbsTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
        $GLOBALS['dh_test']['bloginfo_name'] = 'Example Blog';
    }

    /**
     * Put the request into a category-archive context that does not depend on
     * theme helpers defined outside the tested include set.
     */
    private function set_category_archive_context() {
        $GLOBALS['dh_test']['is_category']   = true;
        $GLOBALS['dh_test']['is_archive']    = true;
        $GLOBALS['dh_test']['archive_title'] = 'Category: Craft';
        $GLOBALS['dh_test']['term_link']     = 'https://example.com/category/craft/';
    }

    public function test_no_breadcrumbs_on_front_page() {
        $GLOBALS['dh_test']['is_front_page'] = true;

        $this->assertSame(array(), dh_get_breadcrumb_items());
    }

    public function test_no_breadcrumbs_on_blog_index() {
        $GLOBALS['dh_test']['is_home'] = true;

        $this->assertSame(array(), dh_get_breadcrumb_items());
    }

    public function test_no_breadcrumbs_on_search() {
        $GLOBALS['dh_test']['is_search'] = true;

        $this->assertSame(array(), dh_get_breadcrumb_items());
    }

    public function test_no_breadcrumbs_on_404() {
        $GLOBALS['dh_test']['is_404'] = true;

        $this->assertSame(array(), dh_get_breadcrumb_items());
    }

    public function test_archive_trail_starts_at_home() {
        $this->set_category_archive_context();

        $items = dh_get_breadcrumb_items();

        $this->assertCount(2, $items);
        $this->assertSame('Example Blog', $items[0]['name']);
        $this->assertSame('https://example.com/', $items[0]['url']);
        $this->assertSame('Category: Craft', $items[1]['name']);
        $this->assertSame('https://example.com/category/craft/', $items[1]['url']);
    }

    public function test_schema_is_null_without_breadcrumbs() {
        $GLOBALS['dh_test']['is_front_page'] = true;

        $this->assertNull(dh_get_breadcrumb_schema());
    }

    public function test_schema_builds_breadcrumb_list_with_positions() {
        $this->set_category_archive_context();

        $schema = dh_get_breadcrumb_schema();

        $this->assertSame('BreadcrumbList', $schema['@type']);
        $this->assertSame('https://example.com/category/craft/#breadcrumb', $schema['@id']);
        $this->assertCount(2, $schema['itemListElement']);

        $first = $schema['itemListElement'][0];
        $this->assertSame('ListItem', $first['@type']);
        $this->assertSame(1, $first['position']);
        $this->assertSame('Example Blog', $first['name']);
        $this->assertSame('https://example.com/', $first['item']);

        $second = $schema['itemListElement'][1];
        $this->assertSame('ListItem', $second['@type']);
        $this->assertSame(2, $second['position']);
        $this->assertSame('Category: Craft', $second['name']);
        $this->assertSame('https://example.com/category/craft/', $second['item']);
    }
}
