<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

get_template_part( 'template-parts/header/topbar' );
?>

<div class="menu">
	<!-- menu start -->
	<nav id="menu-1" class="mega-menu">
		<!-- menu list items container -->
		<section class="menu-list-items">
			<div class='menu-inner'>
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12 header-main-inner">
							<!-- menu logo -->
							<ul class="menu-logo">
								<li>
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
										<?php
										$logo_max_height    = isset( $car_dealer_options['logo_max_height']['height'] ) ? $car_dealer_options['logo_max_height']['height'] : '32px';
										$mobile_logo_height = isset( $car_dealer_options['mobile_logo_height']['height'] ) ? $car_dealer_options['mobile_logo_height']['height'] : '32px';

										$logo_max_height_sticky_header        = isset( $car_dealer_options['logo_max_height_sticky_header']['height'] ) ? $car_dealer_options['logo_max_height_sticky_header']['height'] : '32px';
										$mobile_logo_max_height_sticky_header = isset( $car_dealer_options['mobile_logo_max_height_sticky_header']['height'] ) ? $car_dealer_options['mobile_logo_max_height_sticky_header']['height'] : '32px';

										// site-logo.
										if ( wp_is_mobile() ) {
											if ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['mobile_logo_img']['url'] ) && 'yes' === $car_dealer_options['show_mobile_logo'] ) {
												if ( cardealer_lazyload_enabled() ) {
													?>
													<img class="site-logo cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $car_dealer_options['mobile_logo_img']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_height ) ?>" width="100%" />
													<?php
												} else {
													?>
													<img class="site-logo" src="<?php echo esc_url( $car_dealer_options['mobile_logo_img']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_height ) ?>" width="100%" />
													<?php
												}
											} elseif ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['logo_image']['url'] ) ) {
												if ( cardealer_lazyload_enabled() ) {
													?>
													<img class="site-logo cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $car_dealer_options['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_height ) ?>" width="100%" />
													<?php
												} else {
													?>
													<img class="site-logo" src="<?php echo esc_url( $car_dealer_options['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_height ) ?>" width="100%" />
													<?php
												}
											} elseif ( isset( $car_dealer_options['logo_type'] ) && 'text' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['logo_text'] ) ) {
												?>
												<span class="site-logo logo-text"><?php echo esc_html( $car_dealer_options['logo_text'] ); ?></span>
												<?php
											} else {
												?>
												<span class="site-logo logo-text"><?php bloginfo( 'name' ); ?></span>
												<?php
											}
										} else {
											if ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['logo_image']['url'] ) ) {
												if ( cardealer_lazyload_enabled() ) {
													?>
													<img class="site-logo cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $car_dealer_options['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $logo_max_height ) ?>" width="100%" />
													<?php
												} else {
													?>
													<img class="site-logo" src="<?php echo esc_url( $car_dealer_options['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $logo_max_height ) ?>" width="100%" />
													<?php
												}
											} elseif ( isset( $car_dealer_options['logo_type'] ) && 'text' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['logo_text'] ) ) {
												?>
												<span class="site-logo logo-text"><?php echo esc_html( $car_dealer_options['logo_text'] ); ?></span>
												<?php
											} else {
												?>
												<span class="site-logo logo-text"><?php bloginfo( 'name' ); ?></span>
												<?php
											}
										}
										// stickey-logo.
										if ( wp_is_mobile() ) {
											if ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['mobile_sticky_logo_img']['url'] ) && 'yes' === $car_dealer_options['show_mobile_logo'] ) {
												if ( cardealer_lazyload_enabled() ) {
													?>
													<img class="sticky-logo cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $car_dealer_options['mobile_sticky_logo_img']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												} else {
													?>
													<img class="sticky-logo" src="<?php echo esc_url( $car_dealer_options['mobile_sticky_logo_img']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												}
											} elseif ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['sticky_logo_img']['url'] ) ) {
												if ( cardealer_lazyload_enabled() ) {
													?>
													<img class="sticky-logo cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $car_dealer_options['sticky_logo_img']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												} else {
													?>
													<img class="sticky-logo" src="<?php echo esc_url( $car_dealer_options['sticky_logo_img']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												}
											} elseif ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['logo_image']['url'] ) ) {
												if ( cardealer_lazyload_enabled() ) {
													?>
													<img class="sticky-logo cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $car_dealer_options['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												} else {
													?>
													<img class="sticky-logo" src="<?php echo esc_url( $car_dealer_options['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $mobile_logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												}
											} elseif ( isset( $car_dealer_options['logo_type'] ) && 'text' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['logo_text'] ) ) {
												?>
												<span class="sticky-logo sticky-logo-text"><?php echo esc_html( $car_dealer_options['logo_text'] ); ?></span>
												<?php
											} else {
												?>
												<span class="sticky-logo sticky-logo-text"><?php bloginfo( 'name' ); ?></span>
												<?php
											}
										} else {
											if ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['sticky_logo_img']['url'] ) ) {
												if ( cardealer_lazyload_enabled() ) {
													?>
													<img class="sticky-logo cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $car_dealer_options['sticky_logo_img']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												} else {
													?>
													<img class="sticky-logo" src="<?php echo esc_url( $car_dealer_options['sticky_logo_img']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												}
											} elseif ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['logo_image']['url'] ) ) {
												if ( cardealer_lazyload_enabled() ) {
													?>
													<img class="sticky-logo cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $car_dealer_options['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												} else {
													?>
													<img class="sticky-logo" src="<?php echo esc_url( $car_dealer_options['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" height="<?php echo esc_attr( $logo_max_height_sticky_header ) ?>" width="100%" />
													<?php
												}
											} elseif ( isset( $car_dealer_options['logo_type'] ) && 'text' === $car_dealer_options['logo_type'] && ! empty( $car_dealer_options['logo_text'] ) ) {
												?>
												<span class="sticky-logo sticky-logo-text"><?php echo esc_html( $car_dealer_options['logo_text'] ); ?></span>
												<?php
											} else {
												?>
												<span class="sticky-logo sticky-logo-text"><?php bloginfo( 'name' ); ?></span>
												<?php
											}
										}
										?>
									</a>
									<?php
									$description      = get_bloginfo( 'description', 'display' );
									$site_description = ( isset( $car_dealer_options['site_description'] ) ) ? $car_dealer_options['site_description'] : '0';
									if ( ( $site_description && $description ) || ( $site_description && is_customize_preview() ) ) {
										?>
										<p class="site-description"><?php echo esc_html( $description ); ?></p>
										<?php
									}

									// Mobile view icons.
									if ( wp_is_mobile() ) {
										?>
										<div class="mobile-icons-trigger">
											<?php
											$show_search = ( isset( $car_dealer_options['show_search'] ) ) ? ( true === $car_dealer_options['show_search'] ? '1' : $car_dealer_options['show_search'] ) : '1';
											$show_cart   = ( isset( $car_dealer_options['cart_icon'] ) ) ? $car_dealer_options['cart_icon'] : 'yes';
											if ( '1' === $show_search ) {
												get_template_part( 'template-parts/header/menu-elements/search-mobile' );
											}
											if ( 'yes' === $show_cart ) {
												?>
												<div class="mobile-cart-wrapper">
													<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
												</div>
												<?php
											}
											?>
											<div class="menu-item menu-item-compare" style="<?php echo esc_attr( ( ! isset( $_COOKIE['cars'] ) || empty( $_COOKIE['cars'] ) ) ? 'display:none;' : '' ); ?>">
												<a class="" href="javascript:void(0)">
													<span class="compare-items">
														<i class="fas fa-exchange-alt"></i>
													</span>
													<span class="compare-details count">0</span>
												</a>
											</div>
										</div>
										<?php
										// WooCommerce.
										if ( class_exists( 'woocommerce' ) ) {
											?>
											<div class="widget_shopping_cart_content hidden-xs">
												<?php
												$mini_cart_defaults = array(
													'list_class' => '',
												);

												$mini_cart_args = array();
												$mini_cart_args = wp_parse_args( $mini_cart_args, $mini_cart_defaults );

												wc_get_template( 'cart/mini-cart.php', $mini_cart_args );
												?>
											</div>
											<?php
										}
									}
									?>
								</li>
							</ul>
							<!-- menu links -->
							<?php cardealer_primary_menu(); ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	</nav>
	<!-- menu end -->
</div>
