<?php
/**
 * Do not allow directly accessing this file.
 *
 * @package Cardealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<div class="wrap cardealer-admin-theme-page cardealer-admin-wrap cardealer-admin-plugins-screen">
	<?php
	cardealer_get_cardealer_tabs( 'plugins' );
	$tgm_page_plugins = new TGM_Plugin_Activation();
	if ( ! isset( $_POST['action'] ) || ! in_array( $_POST['action'], array( 'tgmpa-bulk-install', 'tgmpa-bulk-install' ), true ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		do_action( 'cardealer_tgmpa_plugins_panel_notice' );
	}
	?>
	<div class="cardealer-theme-panel-tgmpa-wrap">
		<?php $tgm_page_plugins->install_plugins_page(); ?>
	</div>
</div>
