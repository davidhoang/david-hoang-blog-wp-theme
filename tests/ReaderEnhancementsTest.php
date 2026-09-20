<?php

use PHPUnit\Framework\TestCase;

class ReaderEnhancementsTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
        $GLOBALS['dh_test']['is_singular_post'] = true;
        $GLOBALS['dh_test']['in_the_loop'] = true;
        $GLOBALS['dh_test']['is_main_query'] = true;
    }

    public function test_adds_unique_heading_ids_and_table_of_contents() {
        $html = '<h2>Design systems</h2><p>Body</p><h3>Tokens</h3><h2>Design systems</h2>';
        $output = dh_reader_enhance_content($html);

        $this->assertStringContainsString('class="dh-toc"', $output);
        $this->assertStringContainsString('id="design-systems"', $output);
        $this->assertStringContainsString('id="design-systems-2"', $output);
        $this->assertStringContainsString('href="#tokens"', $output);
        $this->assertCount(3, $GLOBALS['dh_reader_headings']);
    }

    public function test_preserves_explicit_heading_ids() {
        $output = dh_reader_enhance_content('<h2 id="chosen">A heading</h2>');

        $this->assertStringContainsString('id="chosen"', $output);
        $this->assertStringNotContainsString('class="dh-toc"', $output);
    }

    public function test_skips_non_reader_views() {
        $GLOBALS['dh_test']['is_singular_post'] = false;
        $html = '<h2>Untouched</h2>';

        $this->assertSame($html, dh_reader_enhance_content($html));
    }

    public function test_heading_threshold_is_filterable_and_clamped() {
        $GLOBALS['dh_test']['filters']['dh_toc_min_headings'] = 1;

        $this->assertSame(2, dh_reader_toc_min_headings());
    }
}
