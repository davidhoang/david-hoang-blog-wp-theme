<?php

use PHPUnit\Framework\TestCase;

class RelatedPostsTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    public function test_returns_empty_without_a_post() {
        $GLOBALS['dh_test']['current_id'] = 0;

        $this->assertSame(array(), dh_get_related_posts());
        $this->assertSame(array(), $GLOBALS['dh_test']['get_posts_args']);
    }

    public function test_returns_empty_when_post_has_no_tags() {
        $GLOBALS['dh_test']['post_tags'] = array();

        $this->assertSame(array(), dh_get_related_posts(42));
        $this->assertSame(array(), $GLOBALS['dh_test']['get_posts_args']);
    }

    public function test_queries_related_posts_by_shared_tags() {
        $GLOBALS['dh_test']['post_tags']     = array(7, 8);
        $GLOBALS['dh_test']['related_posts'] = array('a', 'b');

        $result = dh_get_related_posts(42);

        $this->assertSame(array('a', 'b'), $result);
        $this->assertCount(1, $GLOBALS['dh_test']['get_posts_args']);

        $args = $GLOBALS['dh_test']['get_posts_args'][0];
        $this->assertSame('post', $args['post_type']);
        $this->assertSame('publish', $args['post_status']);
        $this->assertSame(3, $args['posts_per_page']);
        $this->assertSame(array(42), $args['post__not_in']);
        $this->assertSame(array(7, 8), $args['tag__in']);
        $this->assertSame('date', $args['orderby']);
        $this->assertSame('DESC', $args['order']);
    }

    public function test_falls_back_to_current_post_id() {
        $GLOBALS['dh_test']['current_id'] = 99;
        $GLOBALS['dh_test']['post_tags'] = array(3);

        dh_get_related_posts();

        $args = $GLOBALS['dh_test']['get_posts_args'][0];
        $this->assertSame(array(99), $args['post__not_in']);
    }

    public function test_respects_explicit_limit() {
        $GLOBALS['dh_test']['post_tags'] = array(1);

        dh_get_related_posts(42, 5);

        $this->assertSame(5, $GLOBALS['dh_test']['get_posts_args'][0]['posts_per_page']);
    }

    public function test_limit_is_filterable() {
        $GLOBALS['dh_test']['post_tags'] = array(1);
        $GLOBALS['dh_test']['filters']['dh_related_posts_limit'] = 8;

        dh_get_related_posts(42);

        $this->assertSame(8, $GLOBALS['dh_test']['get_posts_args'][0]['posts_per_page']);
    }

    public function test_limit_is_clamped_to_at_least_one() {
        $GLOBALS['dh_test']['post_tags'] = array(1);
        $GLOBALS['dh_test']['filters']['dh_related_posts_limit'] = 0;

        dh_get_related_posts(42);

        $this->assertSame(1, $GLOBALS['dh_test']['get_posts_args'][0]['posts_per_page']);
    }
}
