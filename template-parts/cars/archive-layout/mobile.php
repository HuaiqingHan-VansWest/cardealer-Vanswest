<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$filter_location = cardealer_vehicle_listing_mobile_filter_location();
?>
<div class="row">
	<?php
	do_action( 'cardealer/vehicle_listing/mobile/before_page_content' );

	if ( $args['inv_page_id'] && $args['inv_page_content'] ) {
		?>
		<div class="col-sm-12">
			<?php get_template_part( 'template-parts/cars/archive-sections/page-content', null, $args ); ?>
		</div>
		<?php
	}

	/**
	 * Hook: cardealer/vehicle_listing/mobile/before_listing.
	 *
	 * @hooked cardealer_vehicle_listing_page_mobile_filters - 10
	 */
	do_action( 'cardealer/vehicle_listing/mobile/before_listing' );
	?>
	<div <?php cardealer_cars_content_class(); ?>>

		<?php
		$cars_term = get_queried_object();
		if ( is_tax() && $cars_term && ! empty( $cars_term->description ) ) {
			?>
			<div class="term-description"><?php echo do_shortcode( $cars_term->description ); ?></div>
			<?php
		}
		?>
		<div class="cars-top-filters-box cars-filters-mobile">
			<?php
			$listing_breadcrumb = isset( $car_dealer_options['listing_breadcrumb_visibility_mobile'] ) ? $car_dealer_options['listing_breadcrumb_visibility_mobile'] : 'yes';
			if ( function_exists( 'bcn_display_list' ) && 'yes' === $listing_breadcrumb ) {
				?>
				<div class="cars-top-filters-box-left">
					<ul class="page-breadcrumb" typeof="BreadcrumbList" vocab="http://schema.org/">
						<?php bcn_display_list(); ?>
					</ul>
				</div>
				<?php
			}
			?>
			<div class="cars-top-filters-box-right">
				<?php
				if ( 'off-canvas' === $filter_location ) {

					$show_sidebar_label = ( isset( $car_dealer_options['vehicle_listing_mobile_show_sidebar_label'] ) && ! empty( $car_dealer_options['vehicle_listing_mobile_show_sidebar_label'] ) ) ? $car_dealer_options['vehicle_listing_mobile_show_sidebar_label'] : esc_html__( 'Show sidebar', 'cardealer' );
					?>
					<div class="off-canvas-toggle">
						<a href="#" rel="nofollow"><i class="fas fa-bars"></i> <?php echo esc_html( $show_sidebar_label ); ?></a>
					</div>
					<?php
				}
				cardealer_cars_catalog_ordering();
				?>
			</div>
			<?php
			if ( 'off-canvas' === $filter_location ) {
				// Show active filter when off canvas is active.
				cardealer_get_all_filters( true );
			}
			?>
			<div class="clearfix"></div>
		</div>
		<?php
		cardealer_featured_vehicle_listing();

		global $cars_grid;

		$cars_grid = cardealer_get_cars_catlog_style();
		if ( 'yes' === $cars_grid ) {
			?>
			<div class="row">
			<?php
		}
		if ( have_posts() ) {
			?>
			<div class="all-cars-list-arch vehicle-listing vehicle-listing-main clearfix">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/cars/content', 'cars' );
				endwhile; // end of the loop.
				?>
			</div>
			<?php
		} else {
			?>
			<div class="all-cars-list-arch vehicle-listing vehicle-listing-main clearfix">
				<div class="col-sm-12">
					<div class="alert alert-warning">
					<?php
					esc_html_e( 'No result were found matching your selection.', 'cardealer' );
					?>
					</div>
				</div>
			</div>
			<?php
		}
		if ( 'yes' === $cars_grid ) {
			?>
			</div>
			<?php
		}
		if ( have_posts() ) {
			get_template_part( 'template-parts/cars/pagination' );
		}
		?>
	</div>
	<?php do_action( 'cardealer/vehicle_listing/mobile/after_listing' ); ?>
</div>
