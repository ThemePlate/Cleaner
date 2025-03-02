<?php

namespace ThemePlate\Cleaner\Features;

use ThemePlate\Cleaner\BaseFeature;

class FooterScripts extends BaseFeature {

	public function key(): string {

		return 'footer_scripts';

	}


	public function action(): void {

		add_action( 'wp_enqueue_scripts', array( $this, 'move' ) );

	}


	public function move(): void {

		remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
		remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
		remove_action( 'wp_head', 'wp_print_scripts' );

	}

}
