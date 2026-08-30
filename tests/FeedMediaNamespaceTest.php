<?php

use PHPUnit\Framework\TestCase;

class FeedMediaNamespaceTest extends TestCase {
    public function test_rss2_namespace_echoes_media_xmlns() {
        ob_start();
        dh_feed_media_namespace();
        $output = ob_get_clean();

        $this->assertSame(' xmlns:media="http://search.yahoo.com/mrss/"', $output);
    }

    public function test_atom_namespace_echoes_media_xmlns() {
        ob_start();
        dh_feed_atom_media_namespace();
        $output = ob_get_clean();

        $this->assertSame(' xmlns:media="http://search.yahoo.com/mrss/"', $output);
    }
}
