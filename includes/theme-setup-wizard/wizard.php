<?php
/**
 * CarDealer functions and definitions for Theme Setup Wizard.
 *
 * @package CarDealer
 */

global $cardealer_globals;

require_once get_parent_theme_file_path( '/includes/theme-setup-wizard/envato_setup/class-envato-theme-setup-wizard.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require_once get_parent_theme_file_path( '/includes/theme-setup-wizard/envato_setup/class-dtbwp-envato-theme-setup-wizard.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

add_filter( 'envato_theme_setup_wizard_theme_name', 'cardealer_set_theme_setup_wizard_theme_name' );
/**
 * Filter theme name in setup wizard.
 *
 * @param string $theme_name Theme slug.
 */
function cardealer_set_theme_setup_wizard_theme_name( $theme_name ) {
	global $cardealer_globals;

	$theme_name = $cardealer_globals['theme_name'];

	return $theme_name;
}

add_filter( 'envato_setup_logo_image', 'cardealer_set_envato_setup_logo_image' );
/**
 * Filter logo image url in setup wizard.
 *
 * @param string $image_url Image URL.
 */
function cardealer_set_envato_setup_logo_image( $image_url ) {

	$logo_path = get_parent_theme_file_path( 'images/default/logo.png' );
	$logo_url  = get_parent_theme_file_uri( 'images/default/logo.png' );

	if ( file_exists( $logo_path ) ) {
		$image_url = $logo_url;
	}

	return $image_url;
}

add_filter( 'cardealer_theme_setup_wizard_steps', 'cardealer_theme_setup_wizard_steps_extend' );
/**
 * Filter theme setup wizard step.
 *
 * @param array $steps Array of steps.
 */
function cardealer_theme_setup_wizard_steps_extend( $steps ) {

	if ( isset( $steps['design'] ) ) {
		unset( $steps['design'] );
	}

	return $steps;
}

// Please don't forgot to change filters tag.
// It must start from your theme's name.
add_filter( $cardealer_globals['theme_name'] . '_theme_setup_wizard_username', 'cardealer_set_theme_setup_wizard_username', 10 );
if ( ! function_exists( 'cardealer_set_theme_setup_wizard_username' ) ) {
	/**
	 * Filter envato username in theme setup wizard.
	 *
	 * @param string $username Envato username.
	 */
	function cardealer_set_theme_setup_wizard_username( $username ) {
		return 'potenzaglobalsolutions';
	}
}

add_filter( $cardealer_globals['theme_name'] . '_theme_setup_wizard_oauth_script', 'cardealer_set_theme_setup_wizard_oauth_script', 10 );
if ( ! function_exists( 'cardealer_set_theme_setup_wizard_oauth_script' ) ) {
	/**
	 * Filter oauth url in theme setup wizard.
	 *
	 * @param string $oauth_url Envato oauth url.
	 */
	function cardealer_set_theme_setup_wizard_oauth_script( $oauth_url ) {
		return 'http://themes.potenzaglobalsolutions.com/api/envato/auth.php';
	}
}

add_filter( 'envato_theme_setup_wizard_styles', 'cardealer_set_theme_setup_wizard_site_styles', 10 );
if ( ! function_exists( 'cardealer_set_theme_setup_wizard_site_styles' ) ) {
	/**
	 * Filter styles in theme setup wizard.
	 *
	 * @param array $styles Array of styles.
	 */
	function cardealer_set_theme_setup_wizard_site_styles( $styles ) {

		$styles = array(
			'style_1' => 'Style 1',
			'style_2' => 'Style 2',
			'style_3' => 'Style 3',
		);

		$styles = cardealer_sample_data_items();

		return $styles;
	}
}

add_filter( $cardealer_globals['theme_name'] . '_theme_setup_wizard_default_theme_style', 'cardealer_set_envato_setup_default_theme_style' );
/**
 * Filter theme styles in theme setup wizard.
 *
 * @param string $style Name of style.
 */
function cardealer_set_envato_setup_default_theme_style( $style ) {

	$style = 'default';

	return $style;
}

/**
 * Function to set setup scripts.
 */
function cardealer_theme_setup_wizard_set_assets() {
	wp_print_scripts( 'cardealer-theme-setup' );
}
add_action( 'admin_head', 'cardealer_theme_setup_wizard_set_assets', 0 );

add_filter( 'envato_setup_wizard_footer_copyright', 'cardealer_envato_setup_wizard_footer_copyright', 10, 2 );
/**
 * Filter footer copyright in theme setup wizard.
 *
 * @param string $copyright     Copyright content.
 * @param array  $theme_data    Array of theme data.
 */
function cardealer_envato_setup_wizard_footer_copyright( $copyright, $theme_data ) {

	$copyright = wp_kses(
		sprintf(
			/* translators: %s: Name of Theme Developer. */
			__( '&copy; Created by %s', 'cardealer' ),
			/* translators: %1$s: Theme developers URL %2$s: Name of Theme Developer */
			sprintf(
				'<a href="%1$s" target="_blank" rel="noopener">%2$s</a>',
				'http://www.potenzaglobalsolutions.com/',
				esc_html__( 'Potenza Global Solutions', 'cardealer' )
			)
		),
		array(
			'a' => array(
				'href'   => true,
				'target' => true,
				'rel'    => true,
			),
		)
	);

	return $copyright;
}

add_filter( 'envato_theme_setup_wizard_themeforest_profile_url', 'cardealer_envato_theme_setup_wizard_themeforest_profile_url' );
/**
 * Filter logo image url in setup wizard.
 *
 * @param string $url  Themeforest profile url.
 */
function cardealer_envato_theme_setup_wizard_themeforest_profile_url( $url ) {

	$url = '';

	return $url;
}
