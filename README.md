# ThemePlate Cleaner

## Usage

```php
add_action( 'init', array( 'ThemePlate\Cleaner', 'init' ) );

add_action( 'after_setup_theme', function() {
	add_theme_support( 'tpc_wp_head' );
	add_theme_support( 'tpc_emoji_detection' );
	add_theme_support( 'tpc_query_strings' );
	add_theme_support( 'tpc_dependency_tag' );
	add_theme_support( 'tpc_unnecessary_class' );
	add_theme_support( 'tpc_extra_styles' );
	add_theme_support( 'tpc_embed_wrap' );
	add_theme_support( 'tpc_nav_walker' );
} );
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
class Boostrap_Navbar extends ThemePlate\NavWalker {
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

		if ( isset( $item->current ) && $item->current ) {
			$atts['class'] .= ' active';
		}

		return $atts;
	}
}
```

#### Full control (override properties and methods)

```php
class Custom_Walker extends ThemePlate\NavWalker {
	public const FALLBACK = array(
		'Please add',
		'some menu',
		'items here',
	);

	public function submenu_css_class( array $classes, stdClass $args, int $depth ): array {
		if ( ! $args->walker instanceof $this ) {
			return $classes;
		}

		$classes[] = 'sub-' . $depth;

		return $classes;
	}

	public function css_class( array $classes, WP_Post $menu_item, stdClass $args, int $depth ): array {
		if ( ! $args->walker instanceof $this ) {
			return $classes;
		}

		if ( '_blank' === $menu_item->target ) {
			$classes[] = 'external';
		}

		return $classes;
	}

	public function item_id( string $menu_id, WP_Post $menu_item, stdClass $args, int $depth ): string {
		if ( ! $args->walker instanceof $this ) {
			return $menu_id;
		}

		if ( 10 === $menu_item->ID ) {
			$menu_id = 'i-ten';
		}

		return $menu_id;
	}

	public function link_attributes( array $atts, WP_Post $menu_item, stdClass $args, int $depth ): array {
		if ( ! $args->walker instanceof $this ) {
			return $atts;
		}

		if ( in_array( 'icon', $menu_item->classes, true ) ) {
			$atts['aria-hidden'] = true;
		}

		return $atts;
	}
}
```
