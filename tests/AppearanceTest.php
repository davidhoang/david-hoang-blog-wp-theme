<?php

use PHPUnit\Framework\TestCase;

class AppearanceTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    public function test_custom_colors_drive_site_tokens_and_browser_chrome() {
        $GLOBALS['dh_test']['theme_mods'] = array(
            'dh_light_text'       => '#102030',
            'dh_light_background' => '#fefcf8',
            'dh_light_accent'     => '#b04020',
            'dh_dark_text'        => '#f0eee8',
            'dh_dark_background'  => '#121820',
            'dh_dark_accent'      => '#f2a65a',
        );

        $css = dh_get_appearance_css();

        $this->assertStringContainsString('--dh-color-text:#102030', $css);
        $this->assertStringContainsString('--dh-color-bg:#fefcf8', $css);
        $this->assertStringContainsString('--dh-color-accent:#b04020', $css);
        $this->assertStringContainsString('--dh-color-bg:#121820', $css);
        $this->assertStringContainsString('--dh-color-accent:#f2a65a', $css);
        $this->assertSame(
            array('light' => '#fefcf8', 'dark' => '#121820'),
            dh_get_theme_colors()
        );
    }

    public function test_invalid_colors_fall_back_to_safe_defaults() {
        $GLOBALS['dh_test']['theme_mods']['dh_light_accent'] = 'red;display:none';

        $this->assertSame('#333333', dh_get_appearance_colors()['light_accent']);
    }

    public function test_hero_density_uses_named_presets() {
        $GLOBALS['dh_test']['theme_mods']['dh_hero_density'] = 'bold';

        $this->assertSame('12', dh_get_hero_density_settings()['gap-x']);
        $this->assertSame('bold', dh_sanitize_hero_density('bold'));
        $this->assertSame('balanced', dh_sanitize_hero_density('unknown'));
    }

    public function test_sidebar_link_sanitizer_keeps_valid_urls_and_rss_token() {
        $input = "Portfolio | https://example.com/work\nFeed | {{rss}}\nBroken line\nUnsafe | javascript:alert(1)";

        $this->assertSame(
            "Portfolio | https://example.com/work\nFeed | {{rss}}",
            dh_sanitize_sidebar_links($input)
        );
    }
}
