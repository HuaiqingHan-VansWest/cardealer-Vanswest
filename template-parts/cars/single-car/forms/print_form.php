<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

if ( isset( $car_dealer_options['print_status'] ) && ! $car_dealer_options['print_status'] ) {
	return;
}
?>
<li id="cardealer-print-btn"><a href="javascript:void(0)" class="vehicle-button-link vehicle-button-link-print vehicle-button-link-type-js_event" data-btn_type="js_event" data-event="cardealer-vehicle-button-print"><i class="fas fa-print"></i><?php echo esc_html__( 'Print', 'cardealer' ); ?></a></li>
