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
// Car Dealer dashboard page.
get_template_part( 'includes/admin/class', 'cardealer-theme-activation' ); // theme activation class.

$theme      = cardealer_get_theme_info();
$theme_name = $theme['name'];
$auth_token = Cardealer_Theme_Activation::cardealer_verify_theme();
?>
<div class="wrap cardealer-admin-theme-page cardealer-admin-wrap  cardealer-admin-support-screen">
	<?php cardealer_get_cardealer_tabs( 'support' ); ?>
	<div class="support-bg">
		<?php
		cardealer_display_theme_page();
		if ( ! empty( $auth_token ) ) {
			?>
			<div class="cardealer-admin-important-notice">
				<p class="cardealer-theme-description"><?php echo esc_html__( 'Car Dealer comes with 6 months of free support for every license you purchase. Support can be extended through subscriptions via ThemeForest.', 'cardealer' ); ?></p>
			</div>
			<div class="cardealer-admin-row">
				<div class="cardealer-admin-two-third">
					<div class="cardealer-admin-row">
						<div class="cardealer-admin-one-half ticket">
							<div class="cardealer-admin-one-half-inner">
								<h3><span><img src="<?php echo esc_url( CARDEALER_URL . '/images/theme-panel/ticket.png' ); ?>" /></span><?php esc_html_e( 'Ticket System', 'cardealer' ); ?></h3>
								<p><?php esc_html_e( 'We offer excellent support through our advanced ticket system. Make sure to register your purchase first to access our support services and other resources.', 'cardealer' ); ?></p>
								<a href="<?php echo esc_url( cardealer_get_theme_support_url() ); ?>" target="_blank"><?php esc_html_e( 'Submit a ticket', 'cardealer' ); ?></a>
							</div>
						</div>
						<div class="cardealer-admin-one-half documentation">
							<div class="cardealer-admin-one-half-inner">
								<h3><span><img src="<?php echo esc_url( CARDEALER_URL . '/images/theme-panel/documentation.png' ); ?>" /></span><?php esc_html_e( 'Documentation', 'cardealer' ); ?></h3>
								<?php /* translators: %s: theme name */ ?>
								<p><?php printf( esc_html__( 'Our online documentation is a useful resource for learning every aspect and feature of %s.', 'cardealer' ), esc_html( $theme_name ) ); ?></p>
								<a href="<?php echo esc_url( cardealer_get_theme_doc_url() ); ?>" target="_blank"><?php esc_html_e( 'Learn more', 'cardealer' ); ?></a>
							</div>
						</div>

						<div class="cardealer-admin-one-half video">
							<div class="cardealer-admin-one-half-inner">
								<h3><span><img src="<?php echo esc_url( CARDEALER_URL . '/images/theme-panel/video.png' ); ?>" /></span><?php esc_html_e( 'Video Tutorials', 'cardealer' ); ?></h3>
								<?php /* translators: %s: theme name */ ?>
								<p><?php printf( esc_html__( 'We recommend you watch video tutorials before you start the theme customization. Our video tutorials can teach you the different aspects of using %s.', 'cardealer' ), esc_html( $theme_name ) ); ?></p>
								<a href="<?php echo esc_url( cardealer_get_theme_video_url() ); ?>" target="_blank"><?php esc_html_e( 'Watch Videos', 'cardealer' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
