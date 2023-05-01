<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Woocommerce functions file
 *
 * @package Cardealer
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Function to check woocommerce version
 *
 * @param string $version store version.
 */
function cardealer_woocommerce_version_check( $version = '2.7.0' ) {
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, $version, '>=' ) ) {
			return true;
		}
	}
	return false;
}

add_filter( 'loop_shop_columns', 'cardealer_loop_columns' );
if ( ! function_exists( 'cardealer_loop_columns' ) ) {
	/**
	 * Cardealder Loop Columns
	 */
	function cardealer_loop_columns() {
		global $car_dealer_options;
		$pro_col_sel = 4;
		if ( isset( $car_dealer_options['wc_product_list_column'] ) && ! empty( $car_dealer_options['wc_product_list_column'] ) ) {
			$pro_col_sel = $car_dealer_options['wc_product_list_column'];
		}
		return $pro_col_sel; // 3 products per row.
	}
}
if ( ! function_exists( 'cardealer_loop_columns_class' ) ) {
	/**
	 * Loop Column class
	 */
	function cardealer_loop_columns_class() {
		$column = cardealer_loop_columns();
		echo 'columns-' . esc_html( $column );
	}
}

if ( ! function_exists( 'cardealer_set_products_per_pages' ) ) {
	/**
	 * Set products per page theme option for woocommerce
	 *
	 * @param int $products_per_page product per page variable.
	 */
	function cardealer_set_products_per_pages( $products_per_page ) {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['products_per_pages'] ) && ! empty( $car_dealer_options['products_per_pages'] ) ) {
			$products_per_page = $car_dealer_options['products_per_pages'];
		}
		return $products_per_page;
	}
}
add_filter( 'loop_shop_per_page', 'cardealer_set_products_per_pages' );

if ( ! function_exists( 'cardealer_get_column_related_products' ) ) {
	/**
	 * Get Column Related Products
	 */
	function cardealer_get_column_related_products() {
		global $car_dealer_options;
		$column_related_products = 4;
		if ( isset( $car_dealer_options['column_related_products'] ) && ! empty( $car_dealer_options['column_related_products'] ) ) {
			$column_related_products = $car_dealer_options['column_related_products'];
		}
		return $column_related_products;
	}
}

if ( ! function_exists( 'cardealer_get_related_products_show' ) ) {
	/**
	 * Get related products show
	 */
	function cardealer_get_related_products_show() {
		global $car_dealer_options;
		$related_products_show = 4;
		if ( ( isset( $car_dealer_options['related_products_show'] ) && ! empty( $car_dealer_options['related_products_show'] ) ) ) {
			$related_products_show = $car_dealer_options['related_products_show'];
		}
		return $related_products_show;
	}
}
