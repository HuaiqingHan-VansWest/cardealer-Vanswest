<?php
require_once trailingslashit( CARDEALER_INC_PATH ) . 'cardealer-studio/elementor/pgs-library/class-pgs-library.php';

/**
 * Initializes the PGS_Library.
 */
$pgs_library = pgs_library(
	array(
		'pgs_library_title'         => esc_html__( 'Car Dealer Studio', 'cardealer' ),
		'pgs_library_templates_dir' => trailingslashit( CARDEALER_INC_PATH ) . 'cardealer-studio/templates',
		'pgs_library_templates_url' => trailingslashit( CARDEALER_INC_URL ) . 'cardealer-studio/templates'
	)
);
