<?php

use PHPUnit\Framework\TestCase;

class RelatedPostsTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    private function post($id) {
        $post = new WP_Post();
        $post->ID = $id;

        return $post;
    }

    public function test_returns_empty_without_a_post() {
        $GLOBALS['dh_test']['current_id'] = 0;

        $this->assertSame(array(), dh_get_related_posts());
        $this->assertSame(array(), $GLOBALS['dh_test']['get_posts_args']);
    }

    public function test_falls_back_to_recent_posts_without_taxonomy_context() {
        $recent = array($this->post(7), $this->post(8));
        $GLOBALS['dh_test']['related_posts'] = $recent;

        $this->assertSame($recent, dh_get_related_posts(42));
        $this->assertCount(1, $GLOBALS['dh_test']['get_posts_args']);
        $this->assertArrayNotHasKey('tag__in', $GLOBALS['dh_test']['get_posts_args'][0]);
        $this->assertArrayNotHasKey('category__in', $GLOBALS['dh_test']['get_posts_args'][0]);
    }

    public function test_queries_related_posts_by_shared_tags() {
        $GLOBALS['dh_test']['post_tags'] = array(7, 8);
        $related = array($this->post(10), $this->post(11), $this->post(12));
        $GLOBALS['dh_test']['related_posts'] = $related;

        $result = dh_get_related_posts(42);

        $this->assertSame($related, $result);
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
        $GLOBALS['dh_test']['related_posts'] = array($this->post(7), $this->post(8), $this->post(9));

        dh_get_related_posts();

        $args = $GLOBALS['dh_test']['get_posts_args'][0];
        $this->assertSame(array(99), $args['post__not_in']);
    }

    public function test_respects_explicit_limit() {
        dh_get_related_posts(42, 5);

        $this->assertSame(5, $GLOBALS['dh_test']['get_posts_args'][0]['posts_per_page']);
    }

    public function test_limit_is_filterable() {
        $GLOBALS['dh_test']['filters']['dh_related_posts_limit'] = 8;

        dh_get_related_posts(42);

        $this->assertSame(8, $GLOBALS['dh_test']['get_posts_args'][0]['posts_per_page']);
    }

    public function test_limit_is_clamped_to_at_least_one() {
        $GLOBALS['dh_test']['filters']['dh_related_posts_limit'] = 0;

        dh_get_related_posts(42);

        $this->assertSame(1, $GLOBALS['dh_test']['get_posts_args'][0]['posts_per_page']);
    }

    public function test_prefers_series_before_other_context() {
        $series = new WP_Term();
        $series->term_id = 14;
        $GLOBALS['dh_test']['post_series'] = $series;
        $GLOBALS['dh_test']['post_tags'] = array(3);
        $GLOBALS['dh_test']['related_posts'] = array($this->post(1), $this->post(2), $this->post(3));

        dh_get_related_posts(42);

        $args = $GLOBALS['dh_test']['get_posts_args'][0];
        $this->assertSame('series', $args['tax_query'][0]['taxonomy']);
        $this->assertSame(14, $args['tax_query'][0]['terms']);
        $this->assertSame('ASC', $args['order']);
    }

    public function test_uses_categories_before_recent_fallback() {
        $GLOBALS['dh_test']['post_categories'] = array(5);
        $GLOBALS['dh_test']['related_posts_queue'] = array(
            array($this->post(10)),
            array($this->post(11), $this->post(12)),
        );

        $result = dh_get_related_posts(42);

        $this->assertSame(array(10, 11, 12), wp_list_pluck($result, 'ID'));
        $this->assertSame(array(5), $GLOBALS['dh_test']['get_posts_args'][0]['category__in']);
        $this->assertSame(array(42, 10), $GLOBALS['dh_test']['get_posts_args'][1]['post__not_in']);
    }
}
