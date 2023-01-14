<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use stdClass;
use ThemePlate\Cleaner\NavWalker;
use WP_UnitTestCase;

class NavWalkerTest extends WP_UnitTestCase {
	public function test_default_instance(): void {
		$walker = new NavWalker();

		$this->assertSame( PHP_INT_MAX, has_filter( 'nav_menu_submenu_css_class', array( $walker, 'submenu_css_class' ) ) );
		$this->assertSame( PHP_INT_MAX, has_filter( 'nav_menu_css_class', array( $walker, 'css_class' ) ) );
		$this->assertSame( PHP_INT_MAX, has_filter( 'nav_menu_item_id', array( $walker, 'item_id' ) ) );
		$this->assertSame( PHP_INT_MAX, has_filter( 'nav_menu_link_attributes', array( $walker, 'link_attributes' ) ) );
	}

	public function test_custom_classes(): void {
		$walker = new class() extends NavWalker {
			public array $classes = array(
				'sub-menu' => 'sub-menu-list',
				'has-sub'  => 'has-children',
				'active'   => 'current-item',
				'item'     => 'menu-item',
				'depth'    => 'level-',
			);
		};

		$submenu_classes = $walker->submenu_css_class( array(), (object) compact( 'walker' ), 0 );

		$this->assertContains( $walker->classes['sub-menu'], $submenu_classes );
		$this->assertContains( $walker->classes['depth'] . 0, $submenu_classes );

		$menu_item   = $this->factory()->post->create_and_get();
		$css_classes = $walker->css_class( array(), $menu_item, (object) compact( 'walker' ), 0 );

		$this->assertContains( $walker->classes['item'], $css_classes );
		$this->assertContains( $walker->classes['depth'] . 0, $css_classes );

		$menu_item->current   = true;
		$walker->has_children = true;

		$css_classes = $walker->css_class( array(), $menu_item, (object) compact( 'walker' ), 0 );

		$this->assertContains( $walker->classes['has-sub'], $css_classes );
		$this->assertContains( $walker->classes['active'], $css_classes );
	}

	public function test_custom_attributes(): void {
		$walker = new class() extends NavWalker {
			public function attributes( $atts, $item, $args ) {
				$atts['class'] = 'nav-link';

				if ( $args->walker->has_children ) {
					$atts['class']        .= ' dropdown-toggle';
					$atts['data-toggle']   = 'dropdown';
					$atts['aria-haspopup'] = 'true';
				}

				if ( $item->current ) {
					$atts['class'] .= ' active';
				}

				return $atts;
			}
		};

		$menu_item = $this->factory()->post->create_and_get();
		$item_args = array(
			'depth'     => 0,
			'args'      => (object) compact( 'walker' ),
			'menu_item' => $menu_item,
		);

		$item_args['menu_id'] = 'custom-id';

		$this->assertSame( 'custom-id', call_user_func_array( array( $walker, 'item_id' ), array_reverse( $item_args ) ) );
		unset( $item_args['menu_id'] );

		$item_args['menu_id'] = 'menu-item-' . $menu_item->ID;

		$this->assertSame( '', call_user_func_array( array( $walker, 'item_id' ), array_reverse( $item_args ) ) );
		unset( $item_args['menu_id'] );

		$item_args['atts'] = array();
		$link_attributes   = call_user_func_array( array( $walker, 'link_attributes' ), array_reverse( $item_args ) );

		$this->assertArrayHasKey( 'class', $link_attributes );
		$this->assertSame( $link_attributes['class'], 'nav-link' );
		$this->assertArrayNotHasKey( 'data-toggle', $link_attributes );
		$this->assertArrayNotHasKey( 'aria-haspopup', $link_attributes );

		$menu_item->current   = true;
		$walker->has_children = true;
		$link_attributes      = call_user_func_array( array( $walker, 'link_attributes' ), array_reverse( $item_args ) );

		$this->assertSame( $link_attributes['class'], 'nav-link dropdown-toggle active' );
		$this->assertArrayHasKey( 'data-toggle', $link_attributes );
		$this->assertArrayHasKey( 'aria-haspopup', $link_attributes );
		$this->assertSame( $link_attributes['data-toggle'], 'dropdown' );
		$this->assertSame( $link_attributes['aria-haspopup'], 'true' );
	}
}
