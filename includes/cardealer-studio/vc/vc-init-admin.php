<?php
/**
 * VC init admin
 *
 * @package Cardealer
 */

if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
	include trailingslashit( CARDEALER_INC_PATH ) . 'cardealer-studio/vc/class-cardealer-vc-templates-panel-editor.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	add_action( 'admin_print_scripts-post.php', 'cardealer_vc_templates_enqueue_scripts' );
	add_action( 'admin_print_scripts-post-new.php', 'cardealer_vc_templates_enqueue_scripts' );
}
/**
 * Templates enqueue scripts
 */
function cardealer_vc_templates_enqueue_scripts() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	$post_type = get_post_type();

	wp_register_script( 'jquery-lazy', get_parent_theme_file_uri( '/js/library/lazyload/lazyload.min.js' ), array(), '1.7.9', true );

	// VC Templates Backend.
	if ( ! vc_is_frontend_editor() && vc_check_post_type( $post_type ) ) {

		wp_register_script( 'cardealer_vc_templates_js', get_parent_theme_file_uri( '/js/admin/vc-templates' . $suffix . '.js' ), array( 'jquery-lazy' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'cardealer_vc_templates_js' );
	}

	// VC Templates Frontend.
	if ( vc_is_frontend_editor() ) {
		wp_register_script( 'cardealer_vc_templates_js', get_parent_theme_file_uri( '/js/admin/vc-templates' . $suffix . '.js' ), array( 'vc-frontend-editor-min-js', 'jquery-lazy' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'cardealer_vc_templates_js' );
	}
	wp_enqueue_style( 'cardealer-studio-css', trailingslashit( CARDEALER_INC_URL ) . 'cardealer-studio/vc/assets/css/cardealer-studio.css', array(), CARDEALER_VERSION );
}
