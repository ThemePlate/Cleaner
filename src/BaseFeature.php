<?php

namespace ThemePlate\Cleaner;

abstract class BaseFeature {

	public const PREFIX = 'tpc_';

	public function __construct() {

		if ( current_theme_supports( $this->feature() ) ) {
			$this->action();
		}

	}

	protected function arguments() {

		return get_theme_support( $this->feature() );

	}

	protected function feature(): string {

		return self::PREFIX . $this->key();

	}

	abstract public function key(): string;

	abstract public function action(): void;

}
