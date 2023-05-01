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
global $cardealer_requirements;
?>
<div class="wrap cardealer-admin-theme-page cdhl-admin-wrap cdhl-system-status cdhl-admin-status-screen">
	<?php cardealer_get_cardealer_tabs( 'system-status' ); ?>
	<table class="widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3" data-export-label="WordPress Environment"><?php esc_html_e( 'WordPress Environment', 'cardealer' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Home URL"><?php esc_html_e( 'Home URL:', 'cardealer' ); ?></td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The URL of your site\'s homepage.', 'cardealer' ) . '">[?]</a>'; ?></td>
			<td><?php echo esc_url( home_url() ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Site URL"><?php esc_html_e( 'Site URL:', 'cardealer' ); ?></td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The root URL of your site.', 'cardealer' ) . '">[?]</a>'; ?></td>
			<td><?php echo esc_url( site_url() ); ?></td>
		</tr>
		<tr>
			<td data-export-label="WP Version"><?php esc_html_e( 'WP Version:', 'cardealer' ); ?></td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of WordPress installed on your site.', 'cardealer' ) . '">[?]</a>'; ?></td>
			<td><?php bloginfo( 'version' ); ?></td>
		</tr>
		<tr>
			<td data-export-label="WP Multisite"><?php esc_html_e( 'WP Multisite:', 'cardealer' ); ?></td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'cardealer' ) . '">[?]</a>'; ?></td>
			<td><?php echo ( is_multisite() ) ? '&#10004;' : '&ndash;'; ?></td>
		</tr>
		<tr>
			<td data-export-label="WP Memory Limit"><?php esc_html_e( 'WP Memory Limit:', 'cardealer' ); ?></td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'cardealer' ) . '">[?]</a>'; ?></td>
			<td>
				<?php
				$wp_memory_limit = cardealer_convert_memory( $cardealer_requirements['wp_memory_limit'] );
				$memory_limit    = cardealer_convert_memory( ini_get( 'memory_limit' ) );
				if ( ! is_wp_error( $memory_limit ) && $memory_limit < $wp_memory_limit ) {
					/* translators: %1$s: Memory*/
					echo '<mark class="error">' .
					sprintf(
						/* Translators: %1$s: Memory limit, , %2$s: Recommended Memory, %3$s: Docs link. */
						esc_html__( '%1$s - We recommend setting memory to at least %2$s See: %3$s', 'cardealer' ),
						esc_html( size_format( $memory_limit ) ),
						'<strong>128MB</strong>. <br>',
						'<a href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" rel="noopener" target="_blank">' . esc_html__( 'Increasing memory allocated to PHP', 'cardealer' ) . '</a>'
					)
					. '</mark>';
				} else {
					echo esc_html( size_format( $memory_limit ) );
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Debug Mode"><?php esc_html_e( 'WP Debug Mode:', 'cardealer' ); ?></td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'cardealer' ) . '">[?]</a>'; ?></td>
			<td>
				<?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
					<mark class="yes">&#10004;</mark>
				<?php else : ?>
					&ndash;
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Language"><?php esc_html_e( 'Language:', 'cardealer' ); ?></td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The current language used by WordPress. Default = English', 'cardealer' ) . '">[?]</a>'; ?></td>
			<td><?php echo esc_html( get_locale() ); ?></td>
		</tr>
		</tbody>
	</table>

	<table class="widefat" cellspacing="0">
		<thead>
			<tr>
				<th colspan="3" data-export-label="Server Environment"><?php esc_html_e( 'Server Environment', 'cardealer' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td data-export-label="Server Info"><?php esc_html_e( 'Server Info:', 'cardealer' ); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Information about the web server that is currently hosting your site.', 'cardealer' ) . '">[?]</a>'; ?></td>
				<td><?php echo isset( $_SERVER['SERVER_SOFTWARE'] ) ? esc_html( sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) ) : ''; // the context is safe and reliable. ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Version"><?php esc_html_e( 'PHP Version:', 'cardealer' ); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of PHP installed on your hosting server.', 'cardealer' ) . '">[?]</a>'; ?></td>
				<td>
				<?php
				if ( function_exists( 'phpversion' ) ) {
					$phpversion = phpversion();
					if ( version_compare( $phpversion, $cardealer_requirements['php_version'], '<' ) ) {
						/* translators: %1$s: PHP version */
						echo '<mark class="error">' . sprintf( __( '%1$s - We recommend PHP Version at least 7.4', 'cardealer' ), esc_html( $phpversion ) ) . '</mark>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					} else {
						echo esc_html( $phpversion );
					}
				}
				?>
				</td>
			</tr>
		<?php if ( function_exists( 'ini_get' ) ) : ?>
			<tr>
				<td data-export-label="Max Input Time"><?php esc_html_e( 'PHP Max Input Time:', 'cardealer' ); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'This sets the maximum time in seconds a script is allowed to parse input data, like POST, GET and file uploads.', 'cardealer' ) . '">[?]</a>'; ?></td>
				<td>
				<?php
				$max_input_time          = ini_get( 'max_input_time' );
				$max_input_time_required = $cardealer_requirements['max_input_time'];
				if ( $max_input_time_required > $max_input_time && 0 !== $max_input_time ) {
					/* translators: %1$s: Max input time, %2$s: Required Max input time */
					echo '<mark class="error">'
						. sprintf(
							__( '%1$s - We recommend setting upload maximum input time to at least %2$s.', 'cardealer' ),
							esc_html( $max_input_time ),
							$max_input_time_required
						)
						. '</mark>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				} else {
					if ( 0 === $max_input_time ) {
						echo esc_html__( 'Unlimited', 'cardealer' );
					} else {
						echo sprintf(
							esc_html__( '%s Seconds', 'cardealer' ),
							esc_html( $max_input_time )
						);
					}
				}
				?>
				</td>
			</tr>
			<tr>
				<td data-export-label="PHP Max Execution Time"><?php esc_html_e( 'PHP Max Execution Time:', 'cardealer' ); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'cardealer' ) . '">[?]</a>'; ?></td>
				<td>
					<?php
					$max_execution_time          = ini_get( 'max_execution_time' );
					$max_execution_time_required = $cardealer_requirements['max_execution_time'];
					if ( $max_execution_time_required > $max_execution_time && 0 !== $max_execution_time ) {
						/* translators: %1$s: Time Limit */
						echo '<mark class="error">'
							. sprintf(
								__( '%1$s - We recommend setting max execution time to at least %2$s.<br>See: <a href="%3$s" target="_blank" rel="noopener noreferrer">Increasing max execution to PHP</a>', 'cardealer' ),
								esc_html( $max_execution_time ),
								esc_html( $max_execution_time_required ),
								esc_url( 'https://wordpress.org/support/article/common-wordpress-errors/#maximum-execution-time-exceeded' )
							)
							. '</mark>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					} else {
						if ( 0 === $max_execution_time ) {
							echo esc_html__( 'Unlimited', 'cardealer' );
						} else {
							echo sprintf(
								esc_html__( '%s Seconds', 'cardealer' ),
								esc_html( $max_execution_time )
							);
						}
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars:', 'cardealer' ); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'cardealer' ) . '">[?]</a>'; ?></td>
				<td>
					<?php
					$max_input_vars          = ini_get( 'max_input_vars' );
					$max_input_vars_required = $cardealer_requirements['max_input_vars'];
					if ( $max_input_vars < $max_input_vars_required ) {
						/* translators: %1$s: Required Input Vars */
						echo '<mark class="error">'
							. sprintf(
								__( '%1$s - Recommended Value: %2$s.<br/>Max input vars limitation will truncate POST data such as menus. See: <a href="%3$s" target="_blank" rel="noopener noreferrer">Increasing max input vars limit.</a>', 'cardealer' ),
								esc_html( $max_input_vars ),
								esc_html( $max_input_vars_required ),
								'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit'
							)
							. '</mark>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					} else {
						echo esc_html( $max_input_vars );
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="SUHOSIN Installed"><?php esc_html_e( 'SUHOSIN Installed:', 'cardealer' ); ?></td>
				<td class="help">
				<?php
				echo '<a href="#" class="help_tip" data-tip="' . esc_attr__(
					'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself.
			If enabled on your server, Suhosin may need to be configured to increase its data submission limits.',
					'cardealer'
				) . '">[?]</a>';
				?>
									</td>
				<td><?php echo extension_loaded( 'suhosin' ) ? '&#10004;' : '&ndash;'; ?></td>
			</tr>
			<?php if ( extension_loaded( 'suhosin' ) ) : ?>
				<tr>
					<td data-export-label="Suhosin Post Max Vars"><?php esc_html_e( 'Suhosin Post Max Vars:', 'cardealer' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'cardealer' ) . '">[?]</a>'; ?></td>
					<?php
					$registered_navs  = get_nav_menu_locations();
					$menu_items_count = array( '0' => '0' );
					foreach ( $registered_navs as $handle => $registered_nav ) {
						$menu = wp_get_nav_menu_object( $registered_nav ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
						if ( $menu ) {
							$menu_items_count[] = $menu->count;
						}
					}
					$required_input_vars = $max_items * 12;
					?>
					<td>
						<?php
						$max_input_vars      = ini_get( 'suhosin.post.max_vars' );
						$required_input_vars = $required_input_vars + ( 500 + 1000 );

						if ( $max_input_vars < $required_input_vars ) {
							/* translators: %1$s: Max input vars */
							echo '<mark class="error">' . sprintf( __( '%1$s - Recommended Value: %2$s.<br />Max input vars limitation will truncate POST data such as menus. See: <a href="%3$s" target="_blank" rel="noopener noreferrer">Increasing max input vars limit.</a>', 'cardealer' ), esc_html( $max_input_vars ), '<strong>' . esc_html( $required_input_vars ) . '</strong>', 'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit' ) . '</mark>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
						} else {
							echo '<mark class="yes">' . esc_html( $max_input_vars ) . '</mark>';
						}
						?>
					</td>
				</tr>
				<tr>
					<td data-export-label="Suhosin Request Max Vars"><?php esc_html_e( 'Suhosin Request Max Vars:', 'cardealer' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'cardealer' ) . '">[?]</a>'; ?></td>
					<?php
					$registered_navs  = get_nav_menu_locations();
					$menu_items_count = array( '0' => '0' );
					foreach ( $registered_navs as $handle => $registered_nav ) {
						$menu = wp_get_nav_menu_object( $registered_nav ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
						if ( $menu ) {
							$menu_items_count[] = $menu->count;
						}
					}

					$max_items           = max( $menu_items_count );
					$required_input_vars = ini_get( 'suhosin.request.max_vars' );
					?>
					<td>
						<?php
						$max_input_vars      = ini_get( 'suhosin.request.max_vars' );
						$required_input_vars = $required_input_vars + ( 500 + 1000 );

						if ( $max_input_vars < $required_input_vars ) {
							/* translators: %1$s: Required Input Vars */
							echo '<mark class="error">' . sprintf( __( '%1$s - Recommended Value: %2$s.<br />Max input vars limitation will truncate POST data such as menus. See: <a href="%3$s" target="_blank" rel="noopener noreferrer">Increasing max input vars limit.</a>', 'cardealer' ), esc_html( $max_input_vars ), '<strong>' . esc_html( $required_input_vars + ( 500 + 1000 ) ) . '</strong>', 'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit' ) . '</mark>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
						} else {
							echo '<mark class="yes">' . esc_html( $max_input_vars ) . '</mark>';
						}
						?>
					</td>
				</tr>
				<tr>
					<td data-export-label="Suhosin Post Max Value Length"><?php esc_html_e( 'Suhosin Post Max Value Length:', 'cardealer' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Defines the maximum length of a variable that is registered through a POST request.', 'cardealer' ) . '">[?]</a>'; ?></td>
					<td>
					<?php
						$suhosin_max_value_length     = ini_get( 'suhosin.post.max_value_length' );
						$recommended_max_value_length = 2000000;

					if ( $suhosin_max_value_length < $recommended_max_value_length ) {
						/* translators: %1$s: Max Value Lenth */
						echo '<mark class="error">' . sprintf( __( '%1$s - Recommended Value: %2$s.<br />Post Max Value Length limitation may prohibit the Theme Options data from being saved to your database. See: <a href="%3$s" target="_blank" rel="noopener noreferrer">Suhosin Configuration Info</a>.', 'cardealer' ), esc_html( $suhosin_max_value_length ), '<strong>' . esc_html( $recommended_max_value_length ) . '</strong>', 'http://suhosin.org/stories/configuration.html' ) . '</mark>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					} else {
						echo '<mark class="yes">' . esc_html( $suhosin_max_value_length ) . '</mark>';
					}
					?>
						</td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
			<tr>
				<td data-export-label="ZipArchive"><?php esc_html_e( 'ZipArchive:', 'cardealer' ); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'ZipArchive is required for importing demos. They are used to import and export zip files specifically for sliders.', 'cardealer' ) . '">[?]</a>'; ?></td>
				<td><?php echo class_exists( 'ZipArchive' ) ? '&#10004;' : '<mark class="error">ZipArchive is not installed on your server, but is required if you need to import demo content.</mark>'; ?></td>
			</tr>
			<tr>
				<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL Version:', 'cardealer' ); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of MySQL installed on your hosting server.', 'cardealer' ) . '">[?]</a>'; ?></td>
				<td>
					<?php
					global $wpdb;
					echo esc_html( $wpdb->db_version() );
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max Upload Size:', 'cardealer' ); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The largest file size that can be uploaded to your WordPress installation.', 'cardealer' ) . '">[?]</a>'; ?></td>
				<td>
				<?php
				$upload_max_filesize            = ini_get( 'upload_max_filesize' );
				$upload_max_filesize_i          = cardealer_convert_memory( $upload_max_filesize );
				$upload_max_filesize_required   = $cardealer_requirements['upload_max_filesize'];
				$upload_max_filesize_required_i = cardealer_convert_memory( $upload_max_filesize_required );

				if ( $upload_max_filesize_required_i > $upload_max_filesize_i && 0 !== $upload_max_filesize ) {
					/* translators: %1$s: Upload Max Filesize */
					echo '<mark class="error">'
						. sprintf(
							__( '%1$s - We recommend setting upload maximum file size to at least %2$s.<br>See: <a href="%3$s" target="_blank" rel="noopener noreferrer">Increasing max upload size in WordPress.</a>', 'cardealer' ),
							esc_html( $upload_max_filesize ),
							esc_html( $upload_max_filesize_required ),
							esc_url( 'http://www.wpbeginner.com/wp-tutorials/how-to-increase-the-maximum-file-upload-size-in-wordpress/' )
						)
						. '</mark>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				} else {
					echo esc_html( $upload_max_filesize );
				}
				?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Post Max Size"><?php esc_html_e( 'Post Max Size:', 'cardealer' ); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Maximum size of POST data that PHP will accept.', 'cardealer' ) . '">[?]</a>'; ?></td>
				<td>
				<?php
				$post_max_size = (int) ( ini_get( 'post_max_size' ) );

				$post_max_size            = ini_get( 'post_max_size' );
				$post_max_size_i          = cardealer_convert_memory( $post_max_size );
				$post_max_size_required   = $cardealer_requirements['post_max_size'];
				$post_max_size_required_i = cardealer_convert_memory( $post_max_size_required );

				if ( $post_max_size_required_i > $post_max_size_i && 0 !== $post_max_size_i ) {
					/* translators: 1%s: max upload size, 2%s: url */
					echo '<mark class="error">'
					. sprintf(
						__( '%1$s - We recommend setting post max size size to at least %2$s.<br>See: <a href="%3$s" target="_blank" rel="noopener noreferrer">Increasing post max size in WordPress.</a>', 'cardealer' ),
						esc_html( $post_max_size ),
						esc_html( $post_max_size_required ),
						esc_url( 'http://www.wpbeginner.com/wp-tutorials/how-to-increase-the-maximum-file-upload-size-in-wordpress/' )
					)
					. '</mark>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				} else {
					echo esc_html( $post_max_size );
				}
				?>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="widefat" cellspacing="0" id="status">
		<thead>
			<tr>
				<th colspan="3" data-export-label="Active Plugins (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)"><?php esc_html_e( 'Active Plugins', 'cardealer' ); ?> (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		foreach ( $active_plugins as $plugin ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride
			$plugin_data    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			$dirname        = dirname( $plugin );
			$version_string = '';
			$network_string = '';

			if ( ! empty( $plugin_data['Name'] ) ) {

				// Link the plugin name to the plugin url if available.
				$plugin_name = esc_html( $plugin_data['Name'] );

				if ( ! empty( $plugin_data['PluginURI'] ) ) {
					$plugin_name = '<a target="_blank" href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_html__( 'Visit plugin homepage', 'cardealer' ) . '">' . $plugin_name . '</a>';
				}
				?>
				<tr>
					<td>
						<?php
						echo wp_kses(
							$plugin_name,
							array(
								'a' => array(
									'href'   => true,
									'target' => true,
									'title'  => true,
									'rel'    => true,
								),
							)
						);
						?>
					</td>
					<td class="help">&nbsp;</td>
					<td>
						<?php
						printf(
							/* translators: %s: author name */
							_x( 'by %s', 'by author', 'cardealer' ),
							wp_kses( $plugin_data['Author'], array( 'a' => array( 'href' => true ) ) )
						) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
						?>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>
</div>
