<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CarDealer
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="potenzaglobalsolutions.com" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php do_action( 'cardealer_head_before' ); ?>
	<?php wp_head(); ?>
	<?php do_action( 'cardealer_head_before_close' ); ?>
</head>
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
	global $hide_header_banner, $car_dealer_options, $wp;
	$cardealer_current_post_id = cardealer_get_current_post_id();
	$hide_header_banner        = get_post_meta( $cardealer_current_post_id, 'hide_header_banner', true );

	if ( ! empty( $hide_header_banner ) && ( is_search() ) ) {
		$hide_header_banner = false;
	}

	// check inventory page set as front page or not.
	$inventory_pg_id = cardealer_get_current_post_id();
	$front_page      = get_option( 'page_on_front' );

	global $is_inv_front_page;

	$is_inv_front_page = false;
	if ( ! empty( $front_page ) && (int) $front_page === (int) $inventory_pg_id ) {
		$is_inv_front_page = true;
	}

	do_action( 'cardealer_page_before' );

	$hide_header_banner_class = ( $hide_header_banner && ! is_front_page() && ! $is_inv_front_page ) ? 'header-hidden' : '';
	?>
	<!-- Main Body Wrapper Element -->
	<div id="page" class="hfeed site page-wrapper <?php echo esc_attr( $hide_header_banner_class ); ?>">

		<?php cardealer_display_loader(); ?>

		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>

		<!-- header -->
		<?php get_template_part( 'template-parts/header/site_header' ); ?>

		<?php endif ?>

		<div class="wrapper" id="main">

		<?php get_template_part( 'template-parts/content', 'intro' ); ?>
