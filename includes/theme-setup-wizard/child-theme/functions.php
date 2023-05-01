<?php
/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package CarDealer
 */

/*
 * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
 * you will have to make sure to maintain all of the parent theme dependencies.
 *
 * Make sure you're using the correct handle for loading the parent theme's styles.
 * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
 * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
 *
 * @link https://codex.wordpress.org/Child_Themes
 */
function {name}_enqueue_styles() {// phpcs:ignore WordPress.WhiteSpace.ControlStructureSpacing.NoSpaceAfterOpenParenthesis

	wp_enqueue_style( 'cardealer-main', get_parent_theme_file_uri( '/css/style.css' ) );

	if ( is_rtl() ) {
		wp_enqueue_style( 'rtl-style', get_parent_theme_file_uri( '/rtl.css' ) );
	}

	wp_enqueue_style(
		'{slug}-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'cardealer-main' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', '{name}_enqueue_styles', 11 );
