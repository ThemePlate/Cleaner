<?php

namespace ThemePlate\Cleaner\Features;

use ThemePlate\Cleaner\BaseFeature;

class DefaultViews extends BaseFeature {

	public const TAGS = array(
		'search',
		'tax',
	);


	public function key(): string {

		return 'default_views';

	}


	public function action(): void {

		add_action( 'template_redirect', array( $this, 'disable_views' ) );

	}


	public function disable_views(): void {

		global $wp_query;

		foreach ( static::TAGS as $tag ) {
			$callback = 'is_' . $tag;

			if ( call_user_func( $callback ) && $this->enabled( $tag ) ) {
				$wp_query->set_404();
				status_header( 404 );
			}
		}

	}

}
