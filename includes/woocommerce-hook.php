<?php
/**
 * Woocommerce hook file
 *
 * @package Cardealer
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Minicart on header via Ajax
 */
if ( cardealer_woocommerce_version_check( '2.7.0' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'cardealer_add_to_cart_fragment', 100 );
} else {
	add_filter( 'add_to_cart_fragments', 'cardealer_add_to_cart_fragment', 100 );
}

if ( ! function_exists( 'cardealer_add_to_cart_fragment' ) ) {
	/**
	 * Add to cart fragment
	 *
	 * @param array $fragments array variable.
	 */
	function cardealer_add_to_cart_fragment( $fragments ) {
		// Menu Cart.
		ob_start();
		?>
		<div class="menu-item-woocommerce-cart-wrapper">
			<?php
			get_template_part( 'woocommerce/minicart-ajax' );
			?>
		</div>
		<?php
		$menu_cart = ob_get_clean();

		// Mobile Cart.
		ob_start();
		?>
		<div class="mobile-cart-wrapper">
			<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
		</div>
		<?php
		$mobile_cart                                      = ob_get_clean();
		$fragments['.menu-item-woocommerce-cart-wrapper'] = $menu_cart;
		$fragments['.mobile-cart-wrapper']                = $mobile_cart;

		return $fragments;
	}
}

/**
 * Remove default woocommerce_before_main_content
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

if ( ! function_exists( 'cardealer_show_related_products' ) ) {
	/**
	 * Cardealer show related products
	 */
	function cardealer_show_related_products() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['show_related_products'] ) && 'no' === $car_dealer_options['show_related_products'] ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}
	}
}
add_action( 'init', 'cardealer_show_related_products' );

add_filter( 'woocommerce_cart_item_permalink', 'cardealer_add_custom_link_to_car_page', 10, 3 );
if ( ! function_exists( 'cardealer_add_custom_link_to_car_page' ) ) {
	/**
	 * Update cart page product page link to car details page link
	 *
	 * @param string $permalink store permalink.
	 * @param array  $cart_item store cart item.
	 * @param string $cart_item_key store key.
	 */
	function cardealer_add_custom_link_to_car_page( $permalink, $cart_item, $cart_item_key ) {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT * FROM ' . $wpdb->postmeta . '
				WHERE meta_key = "car_to_woo_product_id" AND meta_value = %d
				',
				$cart_item['product_id']
			),
			OBJECT
		);
		if ( ! empty( $results ) ) {
			if ( isset( $results[0]->post_id ) ) {
				$permalink = get_the_permalink( $results[0]->post_id );
			}
		}
		return $permalink;
	}
}
