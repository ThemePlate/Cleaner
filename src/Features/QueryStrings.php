<?php

namespace ThemePlate\Cleaner\Features;

use ThemePlate\Cleaner\BaseFeature;

class QueryStrings extends BaseFeature {

	public function key(): string {

		return 'query_strings';

	}


	public function action(): void {

		if ( is_admin() ) {
			return;
		}

		// Query strings from static resources
		$args = $this->arguments();

		if ( empty( $args[0] ) || in_array( 'style', $args[0], true ) ) {
			add_filter( 'style_loader_src', array( $this, 'query_strings' ), 15 );
		}

		if ( empty( $args[0] ) || in_array( 'script', $args[0], true ) ) {
			add_filter( 'script_loader_src', array( $this, 'query_strings' ), 15 );
		}

	}


	public function query_strings( $src ) {

		return remove_query_arg( 'ver', $src );

	}

}
