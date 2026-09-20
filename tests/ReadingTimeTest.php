<?php

use PHPUnit\Framework\TestCase;

class ReadingTimeTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    private function make_post($content) {
        $post = new WP_Post();
        $post->post_content = $content;

        return $post;
    }

    public function test_default_words_per_minute() {
        $this->assertSame(200, dh_get_reading_words_per_minute());
    }

    public function test_words_per_minute_is_filterable() {
        $GLOBALS['dh_test']['filters']['dh_reading_words_per_minute'] = 300;

        $this->assertSame(300, dh_get_reading_words_per_minute());
    }

    public function test_missing_post_has_no_reading_time() {
        $GLOBALS['dh_test']['current_post'] = null;

        $this->assertSame(0, dh_get_reading_time_minutes());
    }

    public function test_empty_content_has_no_reading_time() {
        $this->assertSame(0, dh_get_reading_time_minutes($this->make_post('   ')));
    }

    public function test_short_content_rounds_up_to_one_minute() {
        $post = $this->make_post(implode(' ', array_fill(0, 10, 'word')));

        $this->assertSame(1, dh_get_reading_time_minutes($post));
    }

    public function test_reading_time_rounds_up_partial_minutes() {
        // 250 words at 200 wpm => ceil(1.25) => 2 minutes.
        $post = $this->make_post(implode(' ', array_fill(0, 250, 'word')));

        $this->assertSame(2, dh_get_reading_time_minutes($post));
    }

    public function test_reading_time_respects_words_per_minute_filter() {
        $GLOBALS['dh_test']['filters']['dh_reading_words_per_minute'] = 250;
        // 500 words at 250 wpm => 2 minutes.
        $post = $this->make_post(implode(' ', array_fill(0, 500, 'word')));

        $this->assertSame(2, dh_get_reading_time_minutes($post));
    }

    public function test_reading_time_ignores_html_markup() {
        $post = $this->make_post('<p><strong>one</strong> two three</p>');

        $this->assertSame(1, dh_get_reading_time_minutes($post));
    }

    public function test_label_is_empty_without_reading_time() {
        $this->assertSame('', dh_get_reading_time_label($this->make_post('')));
    }

    public function test_label_uses_singular_for_one_minute() {
        $post = $this->make_post(implode(' ', array_fill(0, 10, 'word')));

        $this->assertSame('1 min read', dh_get_reading_time_label($post));
    }

    public function test_label_uses_plural_for_multiple_minutes() {
        $post = $this->make_post(implode(' ', array_fill(0, 250, 'word')));

        $this->assertSame('2 min read', dh_get_reading_time_label($post));
    }
}
