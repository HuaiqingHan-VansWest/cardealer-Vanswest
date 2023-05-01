<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options, $cardealer_header_settings;

$container_class               = ( 'container' === $cardealer_header_settings['header_width'] ) ? 'container' : 'container-fluid';
$logo_max_height               = isset( $car_dealer_options['logo_max_height']['height'] ) ? $car_dealer_options['logo_max_height']['height'] : '32px';
$logo_max_height_sticky_header = isset( $car_dealer_options['logo_max_height_sticky_header']['height'] ) ? $car_dealer_options['logo_max_height_sticky_header']['height'] : '32px';
?>
<!-- .site-header-main -->
<div class="site-header-main mega-menu">
	<div class="<?php echo esc_attr( $container_class ); ?>">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="site-branding">
					<div class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<img class="logo-type-default" src="<?php echo esc_url( cardealer_get_site_logo() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $logo_max_height ) ?>" width="100%" />
							<img class="logo-type-sticky" src="<?php echo esc_url( cardealer_get_site_sticky_logo() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $logo_max_height_sticky_header ) ?>" width="100%" />
						</a>
					</div>
				</div>
				<div class="site-header-menu menu-links">
					<?php
					if ( 'hamburger' === $cardealer_header_settings['header_style'] ) {
						?>
						<div id="hamburger_menu_toggle">
							<div id="hamburger_menu_icon">
								<span></span>
								<span></span>
								<span></span>
								<span></span>
								<span></span>
								<span></span>
							</div>
						</div>
						<?php
					} else {
						?>
						<nav id="site-navigation" class="main-navigation mega-menu">
							<?php cardealer_primary_menu(); ?>
						</nav>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
