<?php

use PHPUnit\Framework\TestCase;

class SeriesOrderTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    public function test_series_archives_are_oldest_first() {
        $query = new WP_Query();

        dh_order_series_archive($query);

        $this->assertSame('date', $query->vars['orderby']);
        $this->assertSame('ASC', $query->vars['order']);
    }

    public function test_admin_queries_are_left_alone() {
        $GLOBALS['dh_test']['is_admin'] = true;
        $query = new WP_Query();

        dh_order_series_archive($query);

        $this->assertSame(array(), $query->vars);
    }

    public function test_non_series_archives_are_left_alone() {
        $query = new WP_Query();
        $query->taxonomy = 'category';

        dh_order_series_archive($query);

        $this->assertSame(array(), $query->vars);
    }

    public function test_secondary_queries_are_left_alone() {
        $query = new WP_Query();
        $query->is_main = false;

        dh_order_series_archive($query);

        $this->assertSame(array(), $query->vars);
    }
}
