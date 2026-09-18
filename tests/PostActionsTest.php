<?php

use PHPUnit\Framework\TestCase;

class PostActionsTest extends TestCase {
    protected function setUp(): void {
        dh_test_reset();
    }

    public function test_subscribe_cta_has_proof_of_concept_defaults() {
        $cta = dh_get_subscribe_cta();

        $this->assertTrue($cta['enabled']);
        $this->assertSame('Keep reading with Proof of Concept', $cta['title']);
        $this->assertSame('https://www.proofofconcept.pub/', $cta['url']);
        $this->assertSame('Subscribe', $cta['label']);
    }

    public function test_subscribe_cta_uses_customizer_values() {
        $GLOBALS['dh_test']['theme_mods'] = array(
            'dh_subscribe_enabled' => false,
            'dh_subscribe_title'   => 'A custom newsletter',
            'dh_subscribe_text'    => 'A custom description.',
            'dh_subscribe_url'     => 'https://example.com/newsletter',
            'dh_subscribe_label'   => 'Join now',
        );

        $this->assertSame(
            array(
                'enabled' => false,
                'title'   => 'A custom newsletter',
                'text'    => 'A custom description.',
                'url'     => 'https://example.com/newsletter',
                'label'   => 'Join now',
            ),
            dh_get_subscribe_cta()
        );
    }
}
