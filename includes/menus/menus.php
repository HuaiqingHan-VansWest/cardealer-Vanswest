<?php
/**
 * Menu
 *
 * Main menu file for the theme.
 *
 * @package Cardealer
 * @version 1.0.0
 */

/**
 * Require file
 */
get_template_part( 'includes/menus/primary-nav-walker' );
get_template_part( 'includes/menus/max-mega-menu/maxmegamenu' );

if ( ! function_exists( 'cardealer_primary_menu' ) ) {
	/**
	 * Primary menu
	 */
	function cardealer_primary_menu() {
		if ( has_nav_menu( 'primary-menu' ) ) :
			// Fetch menu details and check whether mega menu data is found or not.
			$menu_args = array(
				'theme_location' => 'primary-menu',
				'menu_id'        => 'primary-menu',
				'menu_class'     => 'menu-links',
				'container'      => 'ul',
				'walker'         => new CarDealer_Walker_Primary_Nav_Menu(),
			);
			wp_nav_menu( $menu_args );
		endif;
	}
}

add_filter( 'nav_menu_css_class', 'cardealer_nav_menu_css_class', 10, 3 );
if ( ! function_exists( 'cardealer_nav_menu_css_class' ) ) {
	/**
	 * Add "Inventory" to nav menus
	 *
	 * @see cardealer_nav_menu_css_class()
	 *
	 * @param array    $classes used for html.
	 * @param string   $item used for html.
	 * @param stdClass $args An object of arguments.
	 */
	function cardealer_nav_menu_css_class( $classes, $item, $args ) {
		global $car_dealer_options;
		if ( is_post_type_archive( 'cars' ) ) {
			if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
				$car_url = get_permalink( $car_dealer_options['cars_inventory_page'] );
				if ( function_exists( 'icl_object_id' ) ) {
					$lang    = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : null;
					$car_url = apply_filters( 'wpml_permalink', $car_url, $lang );
				}
			} else {
				$car_url = get_post_type_archive_link( 'cars' );
			}

			if ( isset( $item->url ) && ! empty( $item->url ) ) {
				$home_url = esc_url( home_url( '/' ) );
				if ( strstr( $car_url, $item->url ) && $item->url !== $home_url ) {
					$classes[] = 'current-menu-item';
				}
			}
		}
		return $classes;
	}
}

add_filter( 'wp_nav_menu_items', 'cardealer_add_cart_to_wp_menu', 10, 2 );
if ( ! function_exists( 'cardealer_add_cart_to_wp_menu' ) ) {
	/**
	 * Add "Cart" to nav menus
	 *
	 * @see cardealer_add_cart_to_wp_menu()
	 *
	 * @param string   $items used for html.
	 * @param stdClass $args An object of arguments.
	 */
	function cardealer_add_cart_to_wp_menu( $items, $args ) {
		global $car_dealer_options;

		/* WooCommerce specific: check if woocommerce cart object is actually loaded */
		if ( 'primary-menu' !== $args->theme_location || ( isset( $car_dealer_options['cart_icon'] ) && 'no' === $car_dealer_options['cart_icon'] ) ) {
			return $items;
		}

		/* Top Navigation Area Only */
		if ( ( isset( $ajax ) && $ajax ) || ( property_exists( $args, 'theme_location' ) && 'primary-menu' === $args->theme_location ) ) {

			/* WooCommerce */
			if ( class_exists( 'woocommerce' ) ) {
				$css_class = 'menu-item menu-item-type-cart menu-item-type-woocommerce-cart';

				// Is this the cart page?
				if ( is_cart() ) {
					$css_class .= ' current-menu-item';
				}

				$items     .= '<li class="' . esc_attr( $css_class ) . '">';
					$items .= '<div class="menu-item-woocommerce-cart-wrapper">';

						ob_start();
						get_template_part( 'woocommerce/minicart-ajax' );
						$items .= ob_get_clean();

					$items .= '</div>';
				$items     .= '</li>';
			}
		}
		return $items;
	}
}

if ( ! function_exists( 'cardealer_add_compare_to_wp_menu' ) ) {
	/**
	 * Add comapare to nav menus
	 *
	 * @see cardealer_add_compare_to_wp_menu()
	 *
	 * @param string   $items used for html.
	 * @param stdClass $args An object of arguments.
	 */
	function cardealer_add_compare_to_wp_menu( $items, $args ) {

		if ( 'primary-menu' !== $args->theme_location || wp_is_mobile() ) {
			return $items;
		}

		$compare_style   = '';
		$compare_items   = '';
		$car_compare_ids = car_dealer_get_car_compare_ids();

		if ( ! $car_compare_ids ) {
			$compare_style = 'display:none';
		}

		$compare_items          = '<li class="menu-item menu-item-compare" style="' . esc_attr( $compare_style ) . '">';
			$compare_items     .= '<a class="" href="javascript:void(0)">';
				$compare_items .= '<span class="compare-items"><i class="fas fa-exchange-alt"></i></span><span class="compare-details count">0</span>';
			$compare_items     .= '</a>';
		$compare_items         .= '</li>';

		return $items . $compare_items;
	}
}
add_filter( 'wp_nav_menu_items', 'cardealer_add_compare_to_wp_menu', 10, 2 );


if ( ! function_exists( 'cardealer_add_search_to_wp_menu' ) ) {
	/**
	 * Add "Search Form" to nav menus
	 *
	 * @see cardealer_add_search_to_wp_menu()
	 *
	 * @param string   $items used for html.
	 * @param stdClass $args An object of arguments.
	 */
	function cardealer_add_search_to_wp_menu( $items, $args ) {
		global $car_dealer_options;

		if ( 'primary-menu' !== $args->theme_location ) {
			return $items;
		}
		if ( isset( $car_dealer_options['show_search'] ) && '1' !== $car_dealer_options['show_search'] ) {
			return $items;
		}

		if ( ! isset( $car_dealer_options['search_placeholder_text'] ) ) {
			$car_dealer_options['search_placeholder_text'] = '';
		}

		$search_placeholder_text = ( isset( $car_dealer_options['search_placeholder_text'] ) ) ? $car_dealer_options['search_placeholder_text'] : esc_html__( 'Search...', 'cardealer' );
		$search_post_type = cardealer_search_post_type();

		ob_start();
		?>
		<li class="menu-item menu-item-search">
			<form role="search" method="get" id="menu-searchform" name="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<div class="search cd-search-wrap menu-search-wrap">
					<a class="search-open-btn not_click" href="javascript:void(0);"> </a>
					<div class="search-box not-click">
						<?php
						if ( 'any' !== $search_post_type ) {
							?>
							<input type="hidden" name="post_type" value="<?php echo esc_attr( $search_post_type ); ?>" />
							<?php
						}
						?>
						<input type="text" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="menu-s"  placeholder="<?php echo esc_attr( $search_placeholder_text ); ?>" class="cd-search-autocomplete-input not-click" data-seach_type="default"/>
						<button class="cd-search-submit" value="Search" type="submit"><i class="fas fa-search"></i></button>
						<div class="cd-search-autocomplete"><ul class="cd-search-autocomplete-list"></ul></div>
					</div>
				</div>
			</form>
		</li>
		<?php
		$search_items = ob_get_clean();



		return $items . $search_items;
	}
}
add_filter( 'wp_nav_menu_items', 'cardealer_add_search_to_wp_menu', 10, 2 );

if ( ! function_exists( 'cardealer_add_burger_to_wp_menu' ) ) {
	/**
	 * Add "Burger" to nav menus
	 *
	 * @see cardealer_add_burger_to_wp_menu()
	 *
	 * @param string   $items used for html.
	 * @param stdClass $args An object of arguments.
	 */
	function cardealer_add_burger_to_wp_menu( $items, $args ) {
		global $car_dealer_options;

		if ( 'primary-menu' !== $args->theme_location ) {
			return $items;
		}

		if ( ! empty( $car_dealer_options['header_type'] ) && 'burger' !== $car_dealer_options['header_type'] && 'burger-left' !== $car_dealer_options['header_type'] ) {
			return $items;
		}

		$burger_items              = '<li class="menu-item menu-item-burger-toggle">';
			$burger_items         .= '<div id="menu-toggle">';
				$burger_items     .= '<div id="menu-icon">';
					$burger_items .= '<span></span>';
					$burger_items .= '<span></span>';
					$burger_items .= '<span></span>';
					$burger_items .= '<span></span>';
					$burger_items .= '<span></span>';
					$burger_items .= '<span></span>';
				$burger_items     .= '</div>';
			$burger_items         .= '</div>';
		$burger_items             .= '</li>';

		if ( ! empty( $car_dealer_options['header_type'] ) && 'burger-left' === $car_dealer_options['header_type'] ) {
			return $burger_items . $items;
		} else {
			return $items . $burger_items;
		}
	}
}
add_filter( 'wp_nav_menu_items', 'cardealer_add_burger_to_wp_menu', 10, 2 );

if ( ! function_exists( 'cardealer_first_last_menu_classes' ) ) {
	/**
	 * Add first and last menu classes
	 *
	 * This now works with nested uls.
	 *
	 * @see cardealer_first_last_menu_classes()
	 *
	 * @param stdClass $objects store the ID and other data.
	 * @param stdClass $args An object of arguments.
	 */
	function cardealer_first_last_menu_classes( $objects, $args ) {

		/* Add first/last classes to nested menu items. */
		$ids        = array();
		$parent_ids = array();
		$top_ids    = array();

		if ( ! empty( $objects ) ) {

			foreach ( $objects as $i => $object ) {
				/* If there is no menu item parent, store the ID and skip over the object. */
				if ( 0 === $object->menu_item_parent || '0' === $object->menu_item_parent ) {
					$top_ids[ $i ] = $object;
					continue;
				}

				/* Add first item class to nested menus. */
				if ( ! in_array( $object->menu_item_parent, $ids ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					$objects[ $i ]->classes[] = 'first-item';
					$ids[]                    = $object->menu_item_parent;
				}

				/* If we have just added the first menu item class, skip over adding the ID. */
				if ( in_array( 'first-item', $object->classes ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					continue;
				}

				/* Store the menu parent IDs in an array. */
				$parent_ids[ $i ] = $object->menu_item_parent;
			}

			/* Remove any duplicate values and pull out the last menu item. */
			$sanitized_parent_ids = array_unique( array_reverse( $parent_ids, true ) );

			/* Loop through the IDs and add the last menu item class to the appropriate objects. */
			foreach ( $sanitized_parent_ids as $i => $id ) {
				$objects[ $i ]->classes[] = 'last-item';
			}

			/* Finish it off by adding classes to the top level menu items. */
			if ( isset( $objects[1] ) ) {
				$objects[1]->classes[] = 'first-item';
			}

			/* We can be assured 1 will be the first item in the menu. :-) */
			$keys                               = array_keys( $top_ids );
			$objects[ end( $keys ) ]->classes[] = 'last-item';

			/* Return the menu objects. */
			return $objects;
		}
	}
}
add_filter( 'wp_nav_menu_objects', 'cardealer_first_last_menu_classes', 9, 2 );
