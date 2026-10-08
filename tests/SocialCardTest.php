<?php

use PHPUnit\Framework\TestCase;

class SocialCardTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    public function test_scheme_sanitization_defaults_to_light() {
        $this->assertSame('light', dh_sanitize_social_card_scheme(''));
        $this->assertSame('dark', dh_sanitize_social_card_scheme('dark'));
        $this->assertSame('light', dh_sanitize_social_card_scheme('neon'));
    }

    public function test_title_lines_wrap_and_limit() {
        $title = 'Design systems need editorial voice, not just component libraries';

        $lines = dh_social_card_title_lines($title, 28, 3);

        $this->assertCount(3, $lines);
        $this->assertStringContainsString('Design systems', $lines[0]);
    }

    public function test_generated_card_url_includes_version_and_dark_scheme() {
        $post = new WP_Post();
        $post->ID = 12;
        $GLOBALS['dh_test']['posts_by_id'] = array(12 => $post);
        $GLOBALS['dh_test']['post_modified_time'] = 1735776000;

        $url = dh_get_generated_social_card_url(12, 'dark');

        $this->assertStringContainsString('dh_social_card=12', $url);
        $this->assertStringContainsString('dh_card_scheme=dark', $url);
        $this->assertStringContainsString('v=1735776000', $url);
    }

    public function test_cache_path_includes_post_and_scheme() {
        $GLOBALS['dh_test']['upload_basedir'] = '/tmp/uploads';

        $this->assertSame(
            '/tmp/uploads/dh-social-cards/post-7-dark.png',
            dh_get_social_card_cache_path(7, 'dark')
        );
    }
}
