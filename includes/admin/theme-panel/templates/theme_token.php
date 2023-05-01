<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Do not allow directly accessing this file.
 *
 * @package Cardealer
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

// theme information.
$template   = get_template();
$theme_info = wp_get_theme( $template );
$theme_name = $theme_info['Name'];

// verification information.
$purchase_token = Cardealer_Theme_Activation::cardealer_verify_theme();
$purchase_code  = get_option( 'cardealer_theme_purchase_key' );
$notices        = get_option( 'cardealer_purchase_code_notices' );
delete_option( 'cardealer_purchase_code_notices' );

$icon               = 'dashicons dashicons-admin-network';
$token_status_class = 'btn btn-secondary';
if ( ! empty( $purchase_token ) ) {
	$icon               = 'dashicons dashicons-yes';
	$token_status_class = 'btn btn-success';
}

// notices.
if ( ! empty( $notices ) ) {

	$notice      = $notices['notice'];
	$notice_type = $notices['notice_type'];

	$alert_class = 'p-3 mb-2 bg-light text-dark';

	if ( 'warning' === $notice_type ) {
		$alert_class = 'cardealer-admin-important-notice';
	} elseif ( 'error' === $notice_type ) {
		$alert_class = 'cardealer-admin-important-error';
	} elseif ( 'success' === $notice_type ) {
		$alert_class = 'cardealer-admin-important-success';
	}
	?>
	<div class="<?php echo esc_attr( $alert_class ); ?>"> 
		<?php echo esc_html( $notice ); ?>
	</div>
	<?php
}
?>
<div class="cardealer-theme-panel-left">
	<?php
	if ( empty( $purchase_token ) ) {
		?>
		<h3><?php esc_html_e( 'Please Enter Purchase Code', 'cardealer' ); ?></h3>
		<p class="cardealer-theme-description">
			<?php
			/* translators: %s: Theme name, %1$s  Theme name*/
			printf( __( 'Thank you for installing %1$s theme! Please provide the purchase code of the %1$s theme to be able to install demos and get updates of the theme.', 'cardealer' ), esc_html( $theme_name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<?php
	}
	?>
	<form id="cardealer_activate_theme" method="post" action="">
		<!-- validate_purchase_code -->
		<?php settings_fields( 'cardealer_validate_purchase_code' ); ?>
		<!-- purchase_code_nonce -->
		<?php wp_nonce_field( 'cardealer-verify-token', 'purchase_code_nonce' ); ?>

		<h4><?php esc_html_e( 'Purchase Code', 'cardealer' ); ?></h4>
		<div class="input-group mb-2 mr-sm-2 is-invalid cardealer-token-fields">
			<?php
			$purchase_code_input_val  = ( ! empty( $purchase_code ) ) ? $purchase_code : '';
			$purchase_code_input_type = ( ! empty( $purchase_code ) ) ? 'password' : 'text';
			?>
			<span class="<?php echo esc_attr( $icon ); ?>"></span>
			<input class="form-control" id="cardealer_purchase_code" type="<?php echo esc_attr( $purchase_code_input_type ); ?>" name="cardealer_purchase_code" value="<?php echo esc_attr( $purchase_code_input_val ); ?>" placeholder="<?php esc_attr_e( 'Purchase code ( e.g. 9svb13fa-10aa-2267-883a-9201a94cf9b5 )', 'cardealer' ); ?>">
			<?php submit_button( esc_attr__( 'Check', 'cardealer' ), array( 'button', 'button-primary', 'button-large' ) ); ?>
		</div>
	</form>
</div>
<?php if ( empty( $purchase_token ) ) { ?>
<div class="cardealer-theme-panel-right">
	<h3><?php esc_html_e( 'Instructions For Find Purchase Code', 'cardealer' ); ?></h3>
	<ul>
		<li>
		<span> 01  </span>
		<?php esc_html_e( 'Log into your Envato Market account.', 'cardealer' ); ?>
		</li>
		<li>
		<span> 02  </span>
		<?php esc_html_e( 'Hover the mouse over your username at the top of the screen.', 'cardealer' ); ?>
		</li>
		<li>
			<span> 03  </span>
			<?php esc_html_e( 'Click \'Downloads\' from the drop down menu.', 'cardealer' ); ?>
		</li>
		<li>
			<span> 04  </span>
			<?php
			printf(
				wp_kses(
					/* translators: \': for single quotes */
					__( 'Click \'License certificate & purchase code\' (available as PDF or text file). For more information <a href="%1$s" target="_blank">click here</a>.', 'cardealer' ),
					array(
						'br'     => array(),
						'strong' => array(),
						'a'      => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				),
				esc_url( 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code' ),
				esc_html( $theme_name )
			);
			?>
		</li>
	</ul>
</div>
<?php } ?>
