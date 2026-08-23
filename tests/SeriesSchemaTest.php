<?php

use PHPUnit\Framework\TestCase;

class SeriesSchemaTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    public function test_rejects_non_series_terms() {
        $term = new WP_Term();
        $term->taxonomy = 'category';
        $term->name = 'Notes';

        $this->assertNull(dh_get_series_schema($term));
        $this->assertNull(dh_get_series_schema(null));
    }

    public function test_rejects_term_link_errors() {
        $GLOBALS['dh_test']['term_link_error'] = true;

        $term = new WP_Term();
        $term->taxonomy = 'series';
        $term->name = 'Craft';

        $this->assertNull(dh_get_series_schema($term));
    }

    public function test_builds_creative_work_series_node() {
        $term = new WP_Term();
        $term->taxonomy = 'series';
        $term->name = 'Craft';
        $term->description = '<p>A set of essays.</p>';

        $schema = dh_get_series_schema($term);

        $this->assertSame('CreativeWorkSeries', $schema['@type']);
        $this->assertSame('https://example.com/series/craft/#series', $schema['@id']);
        $this->assertSame('Craft', $schema['name']);
        $this->assertSame('https://example.com/series/craft/', $schema['url']);
        $this->assertSame(array('@id' => 'https://example.com/#website'), $schema['isPartOf']);
        $this->assertSame(array('@id' => 'https://example.com/#person'), $schema['publisher']);
        $this->assertSame('A set of essays.', $schema['description']);
    }
}
