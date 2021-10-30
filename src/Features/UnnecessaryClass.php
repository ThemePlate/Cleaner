<?php

namespace ThemePlate\Cleaner\Features;

use ThemePlate\Cleaner\BaseFeature;

class UnnecessaryClass extends BaseFeature {

	public function key(): string {

		return 'unnecessary_class';

	}


	public function action(): void {

		// Remove unnecessary body and post classes
		$args = $this->arguments();

		if ( empty( $args[0] ) || in_array( 'body', $args[0], true ) ) {
			add_filter( 'body_class', array( $this, 'body_class' ) );
		}

		if ( empty( $args[0] ) || in_array( 'post', $args[0], true ) ) {
			add_filter( 'post_class', array( $this, 'post_class' ) );
		}

	}


	public function body_class( $classes ) {

		$match  = '(^(postid|attachmentid|page-id|parent-pageid|category|tag|term)-\d+$|(attachment|page-parent|page-child)$)';
		$match .= '|(^(page|post|single|category|tag|archive|post-type-archive)$)';
		$match .= '|(^.*-(template(-default)?(-page-templates)?(-[\w-]+-php)?)$)';

		foreach ( $classes as $key => $value ) {
			if ( preg_match( '/' . $match . '/', $value ) ) {
				unset( $classes[ $key ] );
			}
		}
		return $classes;

	}


	public function post_class( $classes ) {

		$match = '/(post-\d+$|(type|status|format)-[\w-]+$)/';

		foreach ( $classes as $key => $value ) {
			if ( preg_match( $match, $value ) ) {
				unset( $classes[ $key ] );
			}
		}
		return $classes;

	}

}
