<?php
if ( ! apply_filters( 'cardealer_studio_enabled', true ) ) {
	return;
}

// Elementor.
if ( did_action( 'elementor/loaded' ) ) {
	require_once trailingslashit( CARDEALER_INC_PATH ) . 'cardealer-studio/elementor/elementor.php';
}

// WPBakery.
global $vc_manager;
if ( $vc_manager ) {
	require_once trailingslashit( CARDEALER_INC_PATH ) . 'cardealer-studio/vc/vc-init-admin.php';
}
