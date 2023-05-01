<?php
/**
 * The Template for minicart ajax call
 *
 * @package CarDealer
 */

if ( class_exists( 'woocommerce' ) ) {

	if ( cardealer_woocommerce_version_check( '2.5' ) ) {
		$cart_url = wc_get_cart_url();
	} else {
		$cart_url = WC()->cart->get_cart_url();
	}
	?>
	<a class="cart-contents cart-mobile-content" href="<?php echo esc_url( $cart_url ); ?>">
		<span class="woo-cart-items"><i class="fas fa-shopping-cart" aria-hidden="true"></i></span>
		<span class="woo-cart-details count">
			<?php echo WC()->cart->get_cart_contents_count(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</span>
	</a>
	<?php
	if ( ! wp_is_mobile() ) {
		?>
		<div class="widget_shopping_cart_content hidden-xs">
			<?php
			$mini_cart_defaults = array(
				'list_class' => '',
			);

			$mini_cart_args = array();

			$mini_cart_args = wp_parse_args( $mini_cart_args, $mini_cart_defaults );

			wc_get_template( 'cart/mini-cart.php', $mini_cart_args );
			?>
		</div>
		<?php
	}
}
