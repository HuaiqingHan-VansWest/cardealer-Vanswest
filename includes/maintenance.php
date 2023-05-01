<?php
/**
 * Check if site is in undermaintatance
 *
 * @package cardealer
 */

add_action( 'wp_loaded', 'cardealer_under_maintenance', 21 );
if ( ! function_exists( 'cardealer_under_maintenance' ) ) {
	/**
	 * Under maintenance
	 */
	function cardealer_under_maintenance() {
		global $car_dealer_options;

		if ( is_admin() || cardealer_is_login_page() || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
			return;
		}

		do_action( 'cardealer_maintenance_before' );
		$enable_maintenance = isset( $car_dealer_options['enable_maintenance'] ) ? $car_dealer_options['enable_maintenance'] : '';

		if ( $enable_maintenance ) {
			add_filter( 'body_class', 'cardealer_maintenance_body_class' );
			wp_enqueue_script( 'cardealer-countdown' );

			$maintenance_mode = $car_dealer_options['maintenance_mode'];
			if ( empty( $maintenance_mode ) ) {
				$maintenance_mode = 'maintenance';
			}
			if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'manage_network' ) ) ) {
				get_template_part( 'template-parts/maintenance/maintenance' );
				exit();
			}
		}
	}
}
if ( ! function_exists( 'cardealer_maintenance_body_class' ) ) {
	/**
	 * Body class
	 *
	 * @param string $classes .
	 */
	function cardealer_maintenance_body_class( $classes ) {
		global $car_dealer_options;
		if ( is_admin() || cardealer_is_login_page() || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
			return $classes;
		}

		$enable_maintenance = $car_dealer_options['enable_maintenance'];
		if ( ! current_user_can( 'administrator' ) && $enable_maintenance ) {

			$maintenance_mode = $car_dealer_options['maintenance_mode'];
			if ( empty( $maintenance_mode ) ) {
				$maintenance_mode = 'maintenance';
			}

			$classes[] = 'cd_maintenance';
			$classes[] = 'cd_maintenance_mode-' . $maintenance_mode;
		}
		return $classes;
	}
}
