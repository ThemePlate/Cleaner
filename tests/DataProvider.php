<?php

/**
 * @package ThemePlate
 */

namespace Tests;

trait DataProvider {
	public function for_feature_option_is_enabled_with_arrays(): array {
		return array(
			'with test as key'    => array( array( 'test' => 'this' ), true ),
			'with test as value'  => array( array( 'test' ), true ),
			'with no test passed' => array( array( 'again' ), false ),
			'with empty string'   => array( array( '' ), false ),
		);
	}

	public function for_feature_option_is_enabled_with_strings(): array {
		return array(
			'with exact test' => array( 'test', true ),
			'with no test'    => array( 'again', false ),
			'with empty'      => array( '', false ),
		);
	}

	public function for_feature_option_is_enabled_with_multi_argument(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'with strings has test' => array(
				'maybe',
				'test',
				true,
			),
			'with strings no test' => array(
				'sorry',
				'nothing',
				false,
			),
			'with arrays has test' => array(
				array(
					'test',
					'this',
				),
				array(
					'again',
				),
				true,
			),
			'with arrays no test' => array(
				array(
					'sorry',
				),
				array(
					'nothing',
					'here',
				),
				false,
			),
			'with arrays as key' => array(
				array(
					'maybe' => 'now',
					'test' => 'this',
				),
				array(
					'again' => 'please',
				),
				true,
			),
			'with arrays no key' => array(
				array(
					'really' => '',
					'sorry' => 'nothing',
				),
				array(
					'',
				),
				false,
			),
			'with string and array no test' => array(
				'but',
				array(
					'hopefully',
				),
				false,
			),
			'with string and array has test' => array(
				array(
					'finally',
				),
				'test',
				true,
			),
		);
	}
}
