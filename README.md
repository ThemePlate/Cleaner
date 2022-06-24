# ThemePlate Cleaner

> A markup cleaner

## Usage

```php
add_action( 'after_setup_theme', array( 'ThemePlate\Cleaner', 'instance' ) );

add_theme_support( 'tpc_wp_head' );
add_theme_support( 'tpc_emoji_detection' );
add_theme_support( 'tpc_query_strings' );
add_theme_support( 'tpc_dependency_tag' );
add_theme_support( 'tpc_unnecessary_class' );
add_theme_support( 'tpc_extra_styles' );
add_theme_support( 'tpc_embed_wrap' );
add_theme_support( 'tpc_nav_walker' );
```

### Custom clean navwalker

#### Simplest (need custom classes)

```php
class Clean_Navbar extends ThemePlate\NavWalker {
	public array $classes = array(
		'sub-menu' => 'sub-menu-list',
		'has-sub'  => 'has-children',
		'active'   => 'current-item',
		'item'     => 'menu-item',
		'depth'    => 'level-',
	);
}
```

#### Bootstrap Navbar (with dropdowns)

```php
class Clean_Navbar extends ThemePlate\NavWalker {
	public array $classes = array(
		'sub-menu' => 'dropdown-menu',
		'has-sub'  => 'dropdown',
		'active'   => '',
		'item'     => 'nav-item',
		'depth'    => '',
	);

	public function attributes( $atts, $item, $args, $depth ) {
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
}
```

#### Complex (more control?)

```php
class Clean_Navbar extends ThemePlate\NavWalker {
	public function submenu_css_class( array $classes, stdClass $args, int $depth ): array {
		$classes[] = 'sub-' . $depth;

		return $classes;
	}

	public function css_class( array $classes, WP_Post $menu_item, stdClass $args, int $depth ): array {
		if ( '_blank' === $item->target ) {
			$classes[] = 'external';
		}

		return $classes;
	}

	public function item_id( string $menu_id, WP_Post $menu_item, stdClass $args, int $depth ): string {
		if ( 10 === $item->ID ) {
			$id = 'i-ten';
		}

		return $id;
	}

	public function link_attributes( array $atts, WP_Post $menu_item, stdClass $args, int $depth ): array {
		if ( in_array( 'icon', $item->classes, true ) ) {
			$atts['aria-hidden'] = true;
		}

		return $atts;
	}
}
```
