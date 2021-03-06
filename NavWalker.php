<?php

/**
 * Clean Nav Walker
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate;

class NavWalker extends \Walker {

	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );


	public $db_fields = array(
		'parent' => 'menu_item_parent',
		'id'     => 'db_id',
	);


	public $class = array(
		'sub-menu' => 'sub-menu',
		'has-sub'  => 'has-sub',
		'active'   => 'active',
	);


	public function attributes( $item, $args ) {

		return array();

	}


	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		$classes = array( $this->class['sub-menu'] );

		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= '<ul' . $class_names . '>';

	}


	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		$output .= '</ul>';

	}


	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes = preg_replace( '/current[-_](menu|page)[-_](item|parent|ancestor)|(menu|page)[-_\w+]+/', '', $classes );

		/** @var stdClass $args */
		if ( $args->walker->has_children ) {
			$classes[] = $this->class['has-sub'];
		}

		if ( $item->current ) {
			$classes[] = $this->class['active'];
		}

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id_name = apply_filters( 'nav_menu_item_id', '', $item, $args, $depth );
		$id_name = $id_name ? ' id="' . esc_attr( $id_name ) . '"' : '';

		$output .= '<li' . $id_name . $class_names . '>';

		$atts = array();
		// phpcs:disable WordPress.WhiteSpace.OperatorSpacing.SpacingBefore
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		// phpcs:enable

		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $item->xfn;
		}

		$atts['aria-current'] = $item->current ? 'page' : '';

		$atts = array_merge( $atts, $this->attributes( $item, $args ) );
		$atts = array_filter( $atts );
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );

				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

	}


	public function end_el( &$output, $item, $depth = 0, $args = array() ) {

		$output .= '</li>';

	}


	public static function fallback( $args ) {

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return false;
		}

		$output = '';

		if ( $args['container'] ) {
			$output .= '<' . $args['container'];

			if ( $args['container_id'] ) {
				$output .= ' id="' . $args['container_id'] . '"';
			}

			if ( $args['container_class'] ) {
				$output .= ' class="' . $args['container_class'] . '"';
			}

			$output .= '>';
		}

		$output .= '<ul';

		if ( $args['menu_id'] ) {
			$output .= ' id="' . $args['menu_id'] . '"';
		}

		if ( $args['menu_class'] ) {
			$output .= ' class="' . $args['menu_class'] . '"';
		}

		$output .= '>';
		$output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">Click here</a></li>';
		$output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">to add</a></li>';
		$output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">a menu</a></li>';
		$output .= '</ul>';

		if ( $args['container'] ) {
			$output .= '</' . $args['container'] . '>';
		}

		if ( $args['echo'] ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $output;
		}

		return true;

	}

}
