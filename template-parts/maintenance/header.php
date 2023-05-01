<?php
/**
 * The template for displaying the header.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

?>
<!DOCTYPE html>
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
</head>
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
<?php global $car_dealer_options; ?>

	<!-- Main Body Wrapper Element -->
	<div id="page" class="hfeed site page-wrapper">

		<header id="header" class="topbar-light">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<?php
				if ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['maintenance_logo_image'] ) ) {
					$width  = isset( $car_dealer_options['maintenance_logo_image']['width'] ) ? $car_dealer_options['maintenance_logo_image']['width'] : '';
					$height = isset( $car_dealer_options['maintenance_logo_image']['height'] ) ? $car_dealer_options['maintenance_logo_image']['height'] : '';
					?>
					<img class="maintenance-site-logo" src="<?php echo esc_url( $car_dealer_options['maintenance_logo_image']['url'] ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" width="<?php echo esc_attr( $width ); ?>" height="<?php echo esc_attr( $height ); ?>" />
					<?php
				} elseif ( isset( $car_dealer_options['logo_type'] ) && 'text' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['logo_text'] ) ) {
					?>
					<span class="site-logo logo-text"><?php echo esc_html( $car_dealer_options['logo_text'] ); ?></span>
					<?php
				} else {
					?>
					<span class="site-logo logo-text"><?php bloginfo( 'name' ); ?></span>
					<?php
				}
				?>
			</a>
		</header><!-- #header -->

		<div id="main" class="wrapper">
