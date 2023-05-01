<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$listing_sidebar = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
?>
<div class="row">
	<?php
	if ( $args['inv_page_id'] && $args['inv_page_content'] ) {
		?>
		<div class="col-sm-12">
			<?php get_template_part( 'template-parts/cars/archive-sections/page-content', null, $args ); ?>
		</div>
		<?php
	}
	?>
	<div class="col-sm-12 <?php echo esc_attr( $args['inv_page_content_class'] ); ?>">
		<?php
		$cars_term = get_queried_object();
		if ( is_tax() && $cars_term && ! empty( $cars_term->description ) ) {
			?>
			<div class="term-description"><?php echo do_shortcode( $cars_term->description ); ?></div>
			<?php
		}
		?>
	</div>
</div>
<div class="row">
	<?php
	/*
	 * Custome left-sidebar
	 */

	if ( 'left' === $listing_sidebar ) {
		cardealer_get_car_catlog_sidebar();
	}
	?>
	<div <?php cardealer_cars_content_class(); ?>>
		<?php
		$getlayout            = cardealer_get_cars_list_layout_style();
		$masonry_style_status = ( 'view-masonry' === $getlayout ) ? true : false;

		$listing_breadcrumb_visibility = isset( $car_dealer_options['listing_breadcrumb_visibility'] ) ? $car_dealer_options['listing_breadcrumb_visibility'] : 'yes';
		$listing_sidebar               = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
		$desktop_filter_location       = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 'inline';
		$show_filter_box               = true;

		if ( 'no' != $listing_sidebar ) {
			if ( 'off-canvas' === $desktop_filter_location && function_exists( 'bcn_display_list' ) ) {
				$show_filter_box = false;
				?>
				<div class="cars-filters-box-top cars-top-filters-box">
					<ul class="page-breadcrumb" typeof="BreadcrumbList" vocab="http://schema.org/">
						<?php bcn_display_list(); ?>
					</ul>
				</div>
				<?php
			}
		}
		?>
		<div class="cars-top-filters-box">
			<div class="cars-top-filters-box-left">
				<?php
				if ( 'off-canvas' === $desktop_filter_location && 'no' != $listing_sidebar ) {
					$show_sidebar_label = ( isset( $car_dealer_options['vehicle_listing_desktop_show_sidebar_label'] ) && ! empty( $car_dealer_options['vehicle_listing_desktop_show_sidebar_label'] ) ) ? $car_dealer_options['vehicle_listing_desktop_show_sidebar_label'] : esc_html__( 'Show sidebar', 'cardealer' );
					?>
					<div class="off-canvas-toggle">
						<a href="#" rel="nofollow"><i class="fas fa-bars"></i> <?php echo esc_html( $show_sidebar_label ); ?></a>
					</div>
					<?php
				}
				if ( 'yes' === $listing_breadcrumb_visibility && $show_filter_box && function_exists( 'bcn_display_list' ) ) {
					?>
					<ul class="page-breadcrumb" typeof="BreadcrumbList" vocab="http://schema.org/">
						<?php bcn_display_list(); ?>
					</ul>
					<?php
				}
				?>
			</div>
			<div class="cars-top-filters-box-right">
				<?php
				cardealer_cars_catalog_ordering();
				cardealer_get_catlog_view();
				?>
			</div>
			<div class="clearfix"></div>
		</div>

		<?php
		$listing_filters_visibility    = isset( $car_dealer_options['listing_filters_visibility'] ) ? $car_dealer_options['listing_filters_visibility'] : 'disable';
		if ( 'enable' === $listing_filters_visibility ) {
			?>
			<div class="sorting-options-main">
				<div class="sort-filters-box">
					<?php cardealer_get_all_filters(); ?>
				</div>
			</div>
			<?php
		}

		global $cars_grid;

		$cars_grid = cardealer_get_cars_catlog_style();

		do_action( 'cardealer_before_vehicle_listing' );

		if ( 'yes' === $cars_grid && true === $masonry_style_status ) {
			?>
			<div class="masonry-main">
			<?php
		}

		if ( have_posts() ) {
			$masonry_style_class = ( true === $masonry_style_status ) ? esc_attr( ' isotope-2 masonry filter-container' ) : '';
			?>
			<div class="vehicle-listing-wrapper vehicle-listing-<?php echo esc_attr( $getlayout ); ?>">
				<div class="row all-cars-list-arch vehicle-listing vehicle-listing-main clearfix<?php echo esc_attr( $masonry_style_class ); ?>">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/cars/content', 'cars' );
					endwhile; // end of the loop.
					?>
				</div>
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
		if ( 'yes' === $cars_grid && true === $masonry_style_status ) {
			?>
			</div>
			<?php
		}

		do_action( 'cardealer_after_vehicle_listing' );

		if ( have_posts() ) {
			get_template_part( 'template-parts/cars/pagination' );
		}
		?>
	</div>
	<?php
	/**
	 * Custome right-sidebar
	 * */
	if ( 'off-canvas' === $desktop_filter_location && 'no' != $listing_sidebar ) {
		cardealer_get_offcanvas();
	}
	if ( 'right' === $listing_sidebar ) {
		cardealer_get_car_catlog_sidebar();
	}
	?>
</div>
