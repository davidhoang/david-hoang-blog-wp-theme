<?php

use PHPUnit\Framework\TestCase;

class LinkPostsTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    public function test_link_url_is_empty_without_meta() {
        $post = new WP_Post();
        $post->ID = 3;

        $this->assertSame('', dh_get_link_url($post));
        $this->assertFalse(dh_is_link_post($post));
    }

    public function test_view_url_prefers_external_link() {
        $post = new WP_Post();
        $post->ID = 4;

        $GLOBALS['dh_test']['post_meta'][4] = array(
            '_dh_link_url' => 'https://example.org/story',
        );
        $GLOBALS['dh_test']['permalink'] = 'https://example.com/notes/story/';

        $this->assertTrue(dh_is_link_post($post));
        $this->assertSame('https://example.org/story', dh_get_post_view_url($post));
    }

    public function test_view_url_falls_back_to_permalink() {
        $post = new WP_Post();
        $post->ID = 5;
        $GLOBALS['dh_test']['permalink'] = 'https://example.com/essay/';

        $this->assertSame('https://example.com/essay/', dh_get_post_view_url($post));
    }

    public function test_link_host_label_strips_www() {
        $post = new WP_Post();
        $post->ID = 6;
        $GLOBALS['dh_test']['post_meta'][6] = array(
            '_dh_link_url' => 'https://www.example.net/post',
        );

        $this->assertSame('example.net', dh_get_link_host_label($post));
    }
}
