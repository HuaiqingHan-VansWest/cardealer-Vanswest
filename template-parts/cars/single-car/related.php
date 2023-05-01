<?php
/**
 * Template part to show related cars.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $car_dealer_options;

$layout           = cardear_get_vehicle_detail_page_layout();
$sidebar_position = cardealer_get_cars_details_page_sidebar_position();
$data_item        = 3;

if ( isset( $car_dealer_options['cars-related-vehicle'] ) && ! $car_dealer_options['cars-related-vehicle'] ) {
	return;
}

if ( '2' === $layout ) {
	$data_item = 4;
}

if ( 'no' === $sidebar_position ) {
	$data_item = 4;
}

if ( 'modern-1' === $layout && 'no' !== $sidebar_position ) {
	$data_item = 3;
}

$args = array(
	'post_type'      => 'cars',
	'posts_status'   => 'publish',
	'posts_per_page' => 10,
	'post__not_in'   => array( get_the_ID() ),
);

$terms = get_the_terms( get_the_ID(), 'car_make' );
if ( ! empty( $terms ) ) {
	$cars_cat_slug     = $terms[0]->slug;
	$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'car_make',
			'field'    => 'slug',
			'terms'    => $cars_cat_slug,
		),
	);
}

$args = apply_filters( 'cardealer/single-vehicle/related-vehicles-query/args', $args );

$loop       = new WP_Query( $args );
$tot_result = 0;
$nav_arrow  = false;
$tot_result = $loop->post_count;

if ( $tot_result > 4 ) {
	$nav_arrow = true;
}

if ( $loop->have_posts() ) {
	?>
	<div class="feature-car">
		<h6 class="related-title"><?php esc_html_e( 'Related Vehicle', 'cardealer' ); ?></h6>
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="owl-carousel related-vehicle" data-lazyload="<?php echo esc_attr( cardealer_lazyload_enabled() ); ?>" data-nav-arrow="<?php echo esc_attr( $nav_arrow ); ?>" data-nav-dots="false" data-items="<?php echo esc_attr( $data_item ); ?>" data-md-items="3" data-sm-items="2" data-xs-items="2" data-xx-items="2" data-space="20">
					<?php
					$list_style       = cardealer_get_inv_list_style();
					$is_hover_overlay = cardealer_is_hover_overlay();
					$getlayout        = cardealer_get_cars_list_layout_style();
					if ( 'classic' === $list_style ) {
						while ( $loop->have_posts() ) :
							$loop->the_post();
							?>
							<div class="item ">
								<div class="car-item gray-bg text-center style-classic">
									<div class="car-image">
										<?php
										$cardealer_post_id = get_the_ID();

										do_action( 'cardealer_car_loop_link_open', $cardealer_post_id, $is_hover_overlay );

										cardealer_get_cars_condition( $cardealer_post_id, true );
										cardealer_get_cars_status( $cardealer_post_id, true );
										cardealer_featured_vehicle_badge( $cardealer_post_id );
										echo wp_kses_post( cardealer_get_cars_image( 'car_catalog_image', $cardealer_post_id ) );

										if ( 'yes' === $is_hover_overlay ) {
											?>
											<div class="car-overlay-banner">
												<ul>
													<?php
													/**
													 * Hook car_overlay_banner.
													 *
													 * @hooked cardealer_view_cars_overlay_link - 10
													 * @hooked cardealer_compare_cars_overlay_link - 20
													 * @hooked cardealer_images_cars_overlay_link - 30
													 */
													if ( 'view-list' === $getlayout ) {
														do_action( 'vehicle_classic_list_overlay_gallery', $cardealer_post_id );
													} else {
														do_action( 'vehicle_classic_grid_overlay', $cardealer_post_id );
													}
													?>
												</ul>
											</div>
											<?php
										}
										do_action( 'cardealer_car_loop_link_close', $cardealer_post_id, $is_hover_overlay );
										?>
									</div>
									<div class="car-content">
										<?php
										/**
										 * Hook cardealer_classic_list_car_title.
										 *
										 * @hooked cardealer_list_car_link_title - 5
										 * @hooked cardealer_list_car_title_separator - 10
										 */
										do_action( 'cardealer_classic_list_car_title' );
										cardealer_car_price_html( 'related-slider', $cardealer_post_id, true );
										cardealer_get_cars_list_attribute( $cardealer_post_id );
										cardealer_get_vehicle_review_stamps( $cardealer_post_id );
										?>
										<ul class="car-bottom-actions classic-grid">
											<?php
											cardealer_classic_view_cars_overlay_link( $cardealer_post_id );
											cardealer_classic_vehicle_video_link( $cardealer_post_id );
											?>
										</ul>
									</div>
								</div>
							</div>
							<?php
						endwhile;
					} else {
						while ( $loop->have_posts() ) :
							$loop->the_post();
							?>
							<div class="item">
								<div class="car-item gray-bg text-center">
									<div class="car-image">
										<?php
										$cardealer_post_id = get_the_ID();
										cardealer_get_cars_condition( $cardealer_post_id, true );
										cardealer_get_cars_status( $cardealer_post_id, true );
										cardealer_featured_vehicle_badge( $cardealer_post_id );
										echo wp_kses_post( cardealer_get_cars_image( 'car_catalog_image', $cardealer_post_id ) );

										if ( 'yes' === $is_hover_overlay ) {
											?>
											<div class="car-overlay-banner">
												<ul>
													<?php
													/**
													 * Hook car_overlay_banner.
													 *
													 * @hooked cardealer_view_cars_overlay_link - 10
													 * @hooked cardealer_compare_cars_overlay_link - 20
													 * @hooked cardealer_images_cars_overlay_link - 30
													 */
													do_action( 'car_overlay_banner', $cardealer_post_id );
													?>
												</ul>
											</div>
											<?php
										}
										cardealer_get_cars_list_attribute( $cardealer_post_id );
										?>
									</div>
									<div class="car-content">
										<?php
										/**
										 * Hook cardealer_list_car_title.
										 *
										 * @hooked cardealer_list_car_link_title - 5
										 * @hooked cardealer_list_car_title_separator - 10
										 */
										do_action( 'cardealer_list_car_title' );
										cardealer_car_price_html( 'related-slider', $cardealer_post_id, true );
										cardealer_get_vehicle_review_stamps( $cardealer_post_id );
										?>
									</div>
								</div>
							</div>
							<?php
						endwhile;
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	wp_reset_postdata();
}
