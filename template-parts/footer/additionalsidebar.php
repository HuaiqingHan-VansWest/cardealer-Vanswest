<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

if ( ( isset( $car_dealer_options['show_footer_bottom'] ) && 'yes' === $car_dealer_options['show_footer_bottom'] ) ) {
	?>
	<div class="row">
		<?php
		if ( is_active_sidebar( 'sidebar-footer-5' ) ) {
			dynamic_sidebar( 'sidebar-footer-5' );
		}
		?>
	</div>
	<?php
}
