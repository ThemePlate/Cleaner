<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use stdClass;
use ThemePlate\Cleaner\BaseFeature;
use WP_UnitTestCase;

class BaseFeatureTest extends WP_UnitTestCase {
	public function for_feature_option_is_enabled(): array {
		return array(
			'with nothing passed'   => array( array(), true ),
			'with test in an array' => array( array( 'test' ), true ),
			'with no test in array' => array( array( 'again' ), false ),
			'with test as a string' => array( 'test', true ),
		);
	}

	/**
	 * @dataProvider for_feature_option_is_enabled
	 */
	public function test_feature_option_is_enabled( $args, bool $passed ): void {
		$feature = new class() extends BaseFeature {
			public function key(): string {
				return 'feature';
			}

			public function action(): void {}
		};

		if ( empty( $args ) ) {
			add_theme_support( 'tpc_feature' );
		} else {
			add_theme_support( 'tpc_feature', $args );
		}

		$feature->register();

		$enabled = $feature->enabled( 'test' );

		if ( $passed ) {
			$this->assertTrue( $enabled );
		} else {
			$this->assertFalse( $enabled );
		}
	}
}
