<?php

use PHPUnit\Framework\TestCase;

class ThemeVersionTest extends TestCase {
    public function test_theme_version_matches_style_css_header() {
        $root  = dirname(__DIR__);
        $style = file_get_contents($root . '/wp-content/themes/dh/style.css');
        $funcs = file_get_contents($root . '/wp-content/themes/dh/functions.php');

        preg_match('/Version:\\s*(\\S+)/', $style, $style_match);
        preg_match("/define\\('DH_THEME_VERSION',\\s*'([^']+)'\\)/", $funcs, $const_match);

        $this->assertSame($style_match[1], $const_match[1]);
    }
}
