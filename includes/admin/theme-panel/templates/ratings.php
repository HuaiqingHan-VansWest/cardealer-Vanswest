<?php
/**
 * Do not allow directly accessing this file.
 *
 * @package Cardealer
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<div class="wrap cardealer-admin-theme-page cardealer-admin-wrap cardealer-system-status cardealer-admin-status-screen">
	<?php cardealer_get_cardealer_tabs( 'ratings' ); ?>
	<div class="ratings-content">
		<p><?php esc_html_e( 'Please don\'t forget to rate Car Dealer and leave a nice review, it means a lot to us and our theme.', 'cardealer' ); ?></p>
		<p>
		<?php
		printf(
			wp_kses(
				/* translators: %s: url */
				__( 'Simply login into your ThemeForest account, go to the <a target="_blank" href="%1$s">Downloads section </a>  and click 5 stars next to the Car Dealer WordPress theme as shown in the screenshot below:', 'cardealer' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			esc_url( 'https://themeforest.net/downloads' )
		);
		?>
		</p>
		<img src="<?php echo esc_url( CARDEALER_URL . '/images/theme-panel/rate.png' ); ?>" />
	</div>
</div>
