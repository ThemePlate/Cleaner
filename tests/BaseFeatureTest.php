<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use stdClass;
use ThemePlate\Cleaner\BaseFeature;
use WP_UnitTestCase;

class BaseFeatureTest extends WP_UnitTestCase {
	use DataProvider;

	protected BaseFeature $feature;

	protected function setUp(): void {
		parent::setUp();

		$this->feature = new class() extends BaseFeature {
			public const PREFIX = 'tpc_tester_';

			public function key(): string {
				return 'feature';
			}

			public function action(): void {}
		};
	}

	protected function tearDown(): void {
		parent::tearDown();

		remove_theme_support( $this->feature::PREFIX . 'feature' );
	}

	public function test_feature_option_is_enabled_with_nothing_passed(): void {
		add_theme_support( $this->feature::PREFIX . 'feature' );
		$this->feature->register();

		$this->assertTrue( $this->feature->enabled( 'test' ) );
	}

	public function test_feature_option_is_totally_not_supported(): void {
		$this->feature->register();

		$this->assertFalse( $this->feature->enabled( 'test' ) );
	}

	protected function do_feature_register_and_asserts( array $args, bool $passed ): void {
		add_theme_support( $this->feature::PREFIX . 'feature', ...$args );
		$this->feature->register();

		$enabled = $this->feature->enabled( 'test' );

		if ( $passed ) {
			$this->assertTrue( $enabled );
		} else {
			$this->assertFalse( $enabled );
		}
	}

	/**
	 * @dataProvider for_feature_option_is_enabled_with_arrays
	 */
	public function test_feature_option_is_enabled_with_arrays( array $args, bool $passed ): void {
		$this->do_feature_register_and_asserts( array( $args ), $passed );
	}

	/**
	 * @dataProvider for_feature_option_is_enabled_with_strings
	 */
	public function test_feature_option_is_enabled_with_strings( string $args, bool $passed ): void {
		$this->do_feature_register_and_asserts( array( $args ), $passed );
	}

	/**
	 * @dataProvider for_feature_option_is_enabled_with_multi_argument
	 */
	public function test_feature_option_is_enabled_with_multi_argument( $arg1, $arg2, bool $passed ): void {
		$this->do_feature_register_and_asserts( array( $arg1, $arg2 ), $passed );
	}
}
