<?php

namespace ThemePlate\Cleaner;

abstract class BaseFeature implements FeatureInterface {

	public const PREFIX = 'tpc_';

	public function register(): void {

		if ( current_theme_supports( $this->feature() ) ) {
			$this->action();
		}

	}

	protected function arguments() {

		return get_theme_support( $this->feature() );

	}

	public function feature(): string {

		return static::PREFIX . $this->key();

	}

	public function enabled( string $option ): bool {

		$args = $this->arguments();

		if ( is_bool( $args ) ) {
			return $args;
		}

		foreach ( $args as $arg ) {
			if (
				(
					is_array( $arg ) &&
					(
						in_array( $option, $arg, true ) ||
						array_key_exists( $option, $arg )
					)
				) || (
					is_string( $arg ) &&
					$arg === $option
				)
			) {
				return true;
			}
		}

		return false;

	}

	abstract public function key(): string;

	abstract public function action(): void;

}
