<?php

use PHPUnit\Framework\TestCase;

class PhotosTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    public function test_photo_feed_url_appends_feed_path() {
        $GLOBALS['dh_test']['post_type_archive_link'] = 'https://example.com/photos/';

        $this->assertSame(
            'https://example.com/photos/feed/',
            dh_get_photo_feed_url()
        );
    }

    public function test_photo_feed_url_is_empty_without_archive_link() {
        $GLOBALS['dh_test']['post_type_archive_link'] = '';

        $this->assertSame('', dh_get_photo_feed_url());
    }

    public function test_photo_full_image_returns_null_for_non_photo() {
        $post = new WP_Post();
        $post->ID = 5;
        $post->post_type = 'post';

        $this->assertNull(dh_get_photo_full_image($post));
    }

    public function test_photo_full_image_returns_attachment_data() {
        $post = new WP_Post();
        $post->ID = 9;
        $post->post_type = 'photo';

        $GLOBALS['dh_test']['post_thumbnail_id'] = 42;
        $GLOBALS['dh_test']['attachment_src'] = array('https://example.com/photo-full.jpg', 2400, 1600);
        $GLOBALS['dh_test']['attachment_alt'] = 'Sunset over the bay';

        $this->assertSame(
            array(
                'id'  => 42,
                'url' => 'https://example.com/photo-full.jpg',
                'alt' => 'Sunset over the bay',
            ),
            dh_get_photo_full_image($post)
        );
    }

    public function test_photo_lightbox_enqueue_on_photo_archive() {
        $GLOBALS['dh_test']['is_post_type_archive_photo'] = true;

        $this->assertTrue(dh_should_enqueue_photo_lightbox());
    }
}
