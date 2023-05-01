<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#404-not-found
 *
 * @package CarDealer
 */

global $car_dealer_options;
get_header(); ?>

<section class="error-404 error-page white-bg page-section-ptb">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div id="primary" class="site-content">
					<div id="content" role="main">
						<article id="post-0" class="post error404 no-results not-found">
							<header class="entry-header">
								<?php
								if ( isset( $car_dealer_options['fourofour_enable_page_title'] ) && '1' === (string) $car_dealer_options['fourofour_enable_page_title'] ) {

									$fourofour_page_title = esc_html__( '404! Page Not Found!', 'cardealer' );
									if ( isset( $car_dealer_options['fourofour_page_title_source'] ) && 'custom' === $car_dealer_options['fourofour_page_title_source']
										&& isset( $car_dealer_options['fourofour_page_title'] ) && ! empty( $car_dealer_options['fourofour_page_title'] )
									) {
										$fourofour_page_title = $car_dealer_options['fourofour_page_title'];
									}
									?>
									<h1 class="page-title text-center"><?php echo esc_html( $fourofour_page_title ); ?></h1>
									<?php
								}
								?>
							</header>
							<div class="entry-content">
							<?php
							$page_content_type = 'default';
							$page_content_post = '';

							if ( isset( $car_dealer_options['fourofour_page_content_source'] ) && 'page' === $car_dealer_options['fourofour_page_content_source']
									&& isset( $car_dealer_options['fourofour_page_content_page'] ) && ! empty( $car_dealer_options['fourofour_page_content_page'] )
								) {

								$fourofour_page_content_page_id = $car_dealer_options['fourofour_page_content_page'];
								$fourofour_page                 = get_post( $fourofour_page_content_page_id );

								if ( $fourofour_page ) {
									$page_content_type = 'page';
									$post              = $fourofour_page; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
								}
							}
							if ( 'page' === $page_content_type ) {
								$more_link_text = null;
								$stripteaser    = false;
								setup_postdata( $post, $more_link_text, $stripteaser );
								the_content();
								wp_reset_postdata();
							} else {
								$page_content_title       = esc_html__( 'Ooopps...', 'cardealer' );
								$page_content_subtitle    = esc_html__( "The Page you were looking for, couldn't be found.", 'cardealer' );
								$page_content_description = sprintf(
									wp_kses(
										/* translators: 1: URL */
										__( "Can't find what you looking for? Take a moment and do a search below or start from our <a class='link' href='%s'>Home Page</a>", 'cardealer' ),
										array(
											'a' => array(
												'class' => array(),
												'href'  => array(),
											),
										)
									),
									esc_url( home_url( '/' ) )
								);

								if ( isset( $car_dealer_options['fourofour_page_content_title'] ) && ! empty( $car_dealer_options['fourofour_page_content_title'] ) ) {
									$page_content_title = $car_dealer_options['fourofour_page_content_title'];
								}
								if ( isset( $car_dealer_options['fourofour_page_content_subtitle'] ) && ! empty( $car_dealer_options['fourofour_page_content_subtitle'] ) ) {
									$page_content_subtitle = $car_dealer_options['fourofour_page_content_subtitle'];
								}
								if ( isset( $car_dealer_options['fourofour_page_content_description'] ) && ! empty( $car_dealer_options['fourofour_page_content_description'] ) ) {
									$page_content_description = $car_dealer_options['fourofour_page_content_description'];
								}

								// 404 Image
								if ( isset( $car_dealer_options['fourofour_page_image']['url'] ) && ! empty( $car_dealer_options['fourofour_page_image']['url'] ) ) {
									$img_url = $car_dealer_options['fourofour_page_image']['url'];
								}

								// Padding.
								if ( isset( $car_dealer_options['fourofour_page_content_padding'] ) && is_array( $car_dealer_options['fourofour_page_content_padding'] ) && ! empty( $car_dealer_options['fourofour_page_content_padding'] ) ) {
									if ( isset( $car_dealer_options['fourofour_page_content_padding']['padding-top'] ) && ! empty( $car_dealer_options['fourofour_page_content_padding']['padding-top'] ) ) {
										$content_style['padding-top'] = 'padding-top:' . $car_dealer_options['fourofour_page_content_padding']['padding-top'];
									}
									if ( isset( $car_dealer_options['fourofour_page_content_padding']['padding-right'] ) && ! empty( $car_dealer_options['fourofour_page_content_padding']['padding-right'] ) ) {
										$content_style['padding-right'] = 'padding-right:' . $car_dealer_options['fourofour_page_content_padding']['padding-right'];
									}
									if ( isset( $car_dealer_options['fourofour_page_content_padding']['padding-bottom'] ) && ! empty( $car_dealer_options['fourofour_page_content_padding']['padding-bottom'] ) ) {
										$content_style['padding-bottom'] = 'padding-bottom:' . $car_dealer_options['fourofour_page_content_padding']['padding-bottom'];
									}
									if ( isset( $car_dealer_options['fourofour_page_content_padding']['padding-left'] ) && ! empty( $car_dealer_options['fourofour_page_content_padding']['padding-left'] ) ) {
										$content_style['padding-left'] = 'padding-left:' . $car_dealer_options['fourofour_page_content_padding']['padding-left'];
									}
								}

								// Margin.
								if ( isset( $car_dealer_options['fourofour_page_content_margin'] ) && is_array( $car_dealer_options['fourofour_page_content_margin'] ) && ! empty( $car_dealer_options['fourofour_page_content_margin'] ) ) {
									if ( isset( $car_dealer_options['fourofour_page_content_margin']['margin-top'] ) && ! empty( $car_dealer_options['fourofour_page_content_margin']['margin-top'] ) ) {
										$content_style['margin-top'] = 'margin-top:' . $car_dealer_options['fourofour_page_content_margin']['margin-top'];
									}
									if ( isset( $car_dealer_options['fourofour_page_content_margin']['margin-right'] ) && ! empty( $car_dealer_options['fourofour_page_content_margin']['margin-right'] ) ) {
										$content_style['margin-right'] = 'margin-right:' . $car_dealer_options['fourofour_page_content_margin']['margin-right'];
									}
									if ( isset( $car_dealer_options['fourofour_page_content_margin']['margin-bottom'] ) && ! empty( $car_dealer_options['fourofour_page_content_margin']['margin-bottom'] ) ) {
										$content_style['margin-bottom'] = 'margin-bottom:' . $car_dealer_options['fourofour_page_content_margin']['margin-bottom'];
									}
									if ( isset( $car_dealer_options['fourofour_page_content_margin']['margin-left'] ) && ! empty( $car_dealer_options['fourofour_page_content_margin']['margin-left'] ) ) {
										$content_style['margin-left'] = 'margin-left:' . $car_dealer_options['fourofour_page_content_margin']['margin-left'];
									}
								}

								$content_css = '';
								if ( ! empty( $content_style ) && is_array( $content_style ) ) {
									$content_css = implode( ';', array_filter( array_unique( $content_style ) ) );
								}
								?>
								<div class="error-content text-center" style="<?php echo esc_attr( $content_css ); ?>">
									<h2 class="fourofour"><?php echo esc_html__( '404', 'cardealer' ); ?></h2>
								<?php
								if ( ! empty( $img_url ) ) {
									?>
									<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr__( '404', 'cardealer' ); ?>" title="<?php echo esc_attr__( '404', 'cardealer' ); ?>"/>
									<?php
								} else {
									?>
									<img src="<?php echo esc_url( CARDEALER_URL . '/images/404-error.png' ); ?>" alt="<?php echo esc_attr__( '404', 'cardealer' ); ?>" title="<?php echo esc_attr__( '404', 'cardealer' ); ?>"/>
									<?php
								}
								?>
									<h3 class="text-red"><?php echo esc_html( $page_content_title ); ?></h3>
									<strong class="text-black"><?php echo esc_html( $page_content_subtitle ); ?> </strong>
									<p>
									<?php
									echo sprintf(
										wp_kses(
											$page_content_description,
											array(
												'a' => array(
													'class' => array(),
													'href' => array(),
												),
											)
										),
										esc_url( home_url( '/' ) )
									);
									?>
									</p>
									<div class="error-search">
										<div class="row gray-form no-gutter">
											<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
											<div class="col-md-offset-2 col-md-7 col-sm-10 col-xs-7">
												<input type="search" class="form-control" placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'cardealer' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'cardealer' ); ?>" />
											</div>
											<div class="col-md-1 col-sm-2 col-xs-4">
												<input type="submit" class="button red" value="<?php echo esc_attr_x( 'Search', 'submit button', 'cardealer' ); ?>" />
											</div>
											</form>
										</div>
									</div>
								</div>
								<?php
							}
							?>
							</div><!-- .entry-content -->
						</article><!-- #post-0 -->
					</div><!-- #content -->
				</div><!-- #primary -->
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
