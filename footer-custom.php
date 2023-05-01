<?php
/**
 * The template for displaying the footer without footer section
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CarDealer
 */

global $car_dealer_options;
?>
	</div> <!-- #main .wrapper  -->
	<?php
	if ( wp_is_mobile() ) {
		// Script to disable Top Bar in Mobile if disabled from Admin.
		if ( isset( $car_dealer_options['back_top_mobile'] ) && '1' === (string) $car_dealer_options['back_top_mobile'] ) {
			?>
			<div class="car-top">
				<span>
					<?php if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) { ?>
						<img class="cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( CARDEALER_URL . '/images/car.png' ); ?>" alt="<?php esc_attr_e( 'Top', 'cardealer' ); ?>" title="<?php esc_attr_e( 'Back to top', 'cardealer' ); ?>"/>
					<?php } else { ?>
						<img src="<?php echo esc_url( CARDEALER_URL . '/images/car.png' ); ?>" alt="<?php esc_attr_e( 'Top', 'cardealer' ); ?>" title="<?php esc_attr_e( 'Back to top', 'cardealer' ); ?>"/>
					<?php } ?>
				</span>
			</div>
			<?php
		}
	} elseif ( isset( $car_dealer_options['back_to_top'] ) && '1' === (string) $car_dealer_options['back_to_top'] ) {
		?>
			<div class="car-top">
				<span>
					<?php if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) { ?>
						<img class="cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( CARDEALER_URL . '/images/car.png' ); ?>" alt="<?php esc_attr_e( 'Top', 'cardealer' ); ?>" title="<?php esc_attr_e( 'Back to top', 'cardealer' ); ?>"/>
					<?php } else { ?>
						<img src="<?php echo esc_url( CARDEALER_URL . '/images/car.png' ); ?>" alt="<?php esc_attr_e( 'Top', 'cardealer' ); ?>" title="<?php esc_attr_e( 'Back to top', 'cardealer' ); ?>"/>
					<?php } ?>
				</span>
			</div>
	<?php } ?>

</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
