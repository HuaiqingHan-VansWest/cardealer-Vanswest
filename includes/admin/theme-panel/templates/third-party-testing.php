<?php
/**
 * Third party testing template.
 *
 * @package Cardealer
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$current_debug_tab    = ( isset( $_COOKIE['cardealer_debug_current_tab'] ) && ! empty( $_COOKIE['cardealer_debug_current_tab'] ) ) ? sanitize_text_field( wp_unslash( $_COOKIE['cardealer_debug_current_tab'] ) ) : 'cd-mail';
$cardealer_debug_tabs = array(
	'cd-mail'       => array(
		'title' => esc_html__( 'Mail', 'cardealer' ),
	),
	'cd-google-map' => array(
		'title' => esc_html__( 'Google Map', 'cardealer' ),
	),
);
if ( function_exists( 'cdhl_plugin_active_status' ) && cdhl_plugin_active_status( 'cardealer-vinquery-import/cardealer-vinquery-import.php' ) ) {
	$cardealer_debug_tabs = array_merge(
		$cardealer_debug_tabs,
		array(
			'cd-vinquery' => array(
				'title' => esc_html__( 'VINquery VIN Import', 'cardealer' ),
			),
		)
	);
}

$cardealer_debug_tabs['cd-mailchimp'] = array(
	'title' => esc_html__( 'Mailchimp Keys', 'cardealer' ),
);

if ( function_exists( 'cdhl_plugin_active_status' ) && cdhl_plugin_active_status( 'cardealer-pdf-generator/cardealer-pdf-generator.php' ) ) {
	$cardealer_debug_tabs['cd-pdf-generator'] = array(
		'title' => esc_html__( 'PDF Generator', 'cardealer' ),
	);
}

$current_debug_tab = ( array_key_exists( $current_debug_tab, $cardealer_debug_tabs ) ) ? $current_debug_tab : 'cd-mail';
?>
<div class="wrap cardealer-admin-theme-page cardealer-admin-wrap cardealer-system-status cardealer-admin-status-screen">
	<?php cardealer_get_cardealer_tabs( 'third-party-testing' ); ?>
	<div class="cardealer-debug-tab-container">
		<div class="cardealer-debug-tab-lists">
			<?php
			$cardealer_debug_tabs_sr = 1;
			foreach ( $cardealer_debug_tabs as $tab_k => $tab ) {
				$tab_class = 'cardealer-debug-tab';

				if ( array_key_exists( $current_debug_tab, $cardealer_debug_tabs ) ) {
					if ( $current_debug_tab === $tab_k ) {
						$tab_class .= ' activelink';
					}
				} elseif ( 1 === $cardealer_debug_tabs_sr ) {
					$tab_class .= ' activelink';
				}
				?>
				<div class="cardealer-debug-tab"><a href="#" data-tag="<?php echo esc_attr( $tab_k ); ?>" class="<?php echo esc_attr( $tab_class ); ?>"><?php echo esc_html( $tab['title'] ); ?></a></div>
				<?php
				$cardealer_debug_tabs_sr++;
			}
			?>
		</div>
		<div class="cardealer-debug-tab-content">
			<?php
			$tab_content_id    = 'cd-mail';
			$tab_content_class = 'cardealer-debug-content' . ( ( $current_debug_tab !== $tab_content_id ) ? ' hide' : '' );
			?>
			<div id="<?php echo esc_attr( $tab_content_id ); ?>" class="<?php echo esc_attr( $tab_content_class ); ?>">
				<form id="cardealer-debug-send-mail" name="cardealer-debug-send-mail" method="post" action="">
					<?php
					$sitename = wp_parse_url( network_home_url(), PHP_URL_HOST );
					if ( 'www.' === substr( $sitename, 0, 4 ) ) {
						$sitename = substr( $sitename, 4 );
					}
					$from_email = 'wordpress@' . $sitename;
					?>
					<label for="debug-from-user-email"><?php esc_html_e( 'From Mail', 'cardealer' ); ?></label>
					<input type="email" id="debug-from-user-email" name="debug-from-user-email" value="<?php echo esc_attr( $from_email ); ?>" >
					<p class="description">
						<?php
						echo sprintf(
							wp_kses(
								/* translators: 1: Email */
								__( 'Enter the email you wanted to test. If the email field is empty, it will send the test mail from <strong>%s</strong>.', 'cardealer' ),
								array(
									'strong' => array(),
								)
							),
							esc_html( $from_email )
						);
						?>
					</p>
					<label for="debug-to-user-email"><?php esc_html_e( 'To Mail', 'cardealer' ); ?></label>
					<input type="email" id="debug-to-user-email" name="debug-to-user-email" value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
					<p class="description">
						<?php
						echo sprintf(
							wp_kses(
								/* translators: 1: Email */
								__( 'Enter the email you wanted to test. If the email field is empty, it will send the test mail to <strong>%s</strong>.', 'cardealer' ),
								array(
									'strong' => array(),
								)
							),
							esc_html( get_option( 'admin_email' ) )
						);
						?>
					</p>
					<input type="submit" value="Submit">
				</form>
				<div class="cardealer-debug-content-response hide"></div>
			</div>
			<?php
			$tab_content_id    = 'cd-google-map';
			$tab_content_class = 'cardealer-debug-content' . ( ( $current_debug_tab !== $tab_content_id ) ? ' hide' : '' );
			?>
			<div id="<?php echo esc_attr( $tab_content_id ); ?>" class="<?php echo esc_attr( $tab_content_class ); ?>">
				<div class="cardealer-debug-map-content-notice">
					<?php
					echo esc_html__( 'If the map is not loading correctly, please check the Browser console for the errors/instructions for Google Maps.', 'cardealer' )
					. '<br>'
					. sprintf(
						wp_kses(
							/* translators: %s: URL */
							__( '<strong>Note: </strong>Make sure you have added the Google Maps API key in <a href="%s" target="_blank">Theme Options</a>.', 'cardealer' ),
							array(
								'strong' => array(),
								'br'     => array(),
								'a'      => array(
									'href'   => true,
									'target' => true,
								),
							),
						),
						esc_url( admin_url( 'themes.php?page=cardealer&tab=' . cardealer_get_redux_tab_id( 'google_api_settings_section' ) ) )
					);
					?>
				</div>
				<div id="cd-map-canvas"></div>
				<?php wp_enqueue_script( 'cardealer-debug-google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . cardealer_get_google_maps_api_key() . '&callback=show_cd_map_canvas', array(), CARDEALER_VERSION, true ); ?>
			</div>
			<?php
			if ( function_exists( 'cdhl_plugin_active_status' ) && cdhl_plugin_active_status( 'cardealer-vinquery-import/cardealer-vinquery-import.php' ) ) {
				$tab_content_id    = 'cd-vinquery';
				$tab_content_class = 'cardealer-debug-content' . ( ( $current_debug_tab !== $tab_content_id ) ? ' hide' : '' );
				?>
				<div id="<?php echo esc_attr( $tab_content_id ); ?>" class="<?php echo esc_attr( $tab_content_class ); ?>">
					<div class="cardealer-debug-vinquery-content-notice">
						<?php
						printf(
							wp_kses(
								/* translators: %s: URL */
								__( '<strong>Note: </strong>Make sure you have added the API key for VINQuery VIN Import in the <a href="%s" target="_blank">Theme Options</a>.', 'cardealer' ),
								array(
									'strong' => array(),
									'a'      => array(
										'href'   => true,
										'target' => true,
									),
								)
							),
							esc_url( admin_url( 'themes.php?page=cardealer&tab=' . cardealer_get_redux_tab_id( 'vinquery_vin_settings' ) ) )
						);
						?>
					</div>
					<form id="cardealer-debug-vinquery" name="cardealer-debug-vinquery" method="post" action="">
						<label for="fname"><?php esc_html_e( 'VIN Number', 'cardealer' ); ?></label>
						<input type="text" id="debug-vinnumber" name="debug-vinnumber">
						<p class="description"><?php esc_html_e( 'Enter the VIN in the VIN Number you wanted to test. If the VIN Number field is empty, then the test process will use the default VIN.', 'cardealer' ); ?></p>
						<input type="submit" value="Submit">
					</form>
					<div class="cardealer-vinquery-debug-content-response hide"></div>
				</div>
				<?php
			}
			$tab_content_id    = 'cd-mailchimp';
			$tab_content_class = 'cardealer-debug-content' . ( ( $current_debug_tab !== $tab_content_id ) ? ' hide' : '' );
			?>
			<div id="<?php echo esc_attr( $tab_content_id ); ?>" class="<?php echo esc_attr( $tab_content_class ); ?>">
				<div class="cardealer-debug-mailchimp-content-notice">
					<?php
					printf(
						wp_kses(
							/* translators: %s: Mail chimp theme option URL */
							__( '<strong>Note:</strong> Make sure you have added the Mailchimp List ID and Mailchimp API Key in the <a href="%s" target="_blank">Theme Options</a>. The test process will generate the response based on the Mailchimp List ID and Mailchimp API Key. ', 'cardealer' ),
							array(
								'strong' => array(),
								'a'      => array(
									'href'   => true,
									'target' => true,
								),
							)
						),
						esc_url( admin_url( 'themes.php?page=cardealer&tab=' . cardealer_get_redux_tab_id( 'mailchimp_settings_section' ) ) )
					);
					?>
				</div>
				<button type="button" id="debug-user-mailchimp" class="debug-user-mailchimp" ><?php esc_html_e( 'Check Mailchimp', 'cardealer' ); ?></button>
				<div class="cardealer-mailchimp-debug-content-response"></div>
			</div>
			<?php
			if ( isset( $cardealer_debug_tabs['cd-pdf-generator'] ) && ! empty( $cardealer_debug_tabs['cd-pdf-generator'] ) ) {
				$tab_content_id    = 'cd-pdf-generator';
				$tab_content_class = 'cardealer-debug-content' . ( ( $current_debug_tab !== $tab_content_id ) ? ' hide' : '' );
				?>
				<div id="<?php echo esc_attr( $tab_content_id ); ?>" class="<?php echo esc_attr( $tab_content_class ); ?>">
					<?php cardealer_load_theme_template( 'third-party-testing-pdf-generator', array( 'tab_id' => $tab_content_id ) ); ?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
