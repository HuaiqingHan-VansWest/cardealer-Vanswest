<?php
$show_featured_vehicles = cardealer_show_featured_vehicles();

if ( ! $show_featured_vehicles ) {
	return;
}

$section_title              = cardealer_featured_vehicles_section_title();
$featured_vehicles_count    = cardealer_get_featured_vehicles_count();
$featured_vehicles_filtered = cardealer_show_featured_vehicles_filtered();

global $wp_query;

$featured_args = array(
	'post_type'      => 'cars',
	'posts_per_page' => $featured_vehicles_count,
	'paged'          => 1,
	'orderby'        => 'rand',
	'tax_query'      => array(),
	'meta_query'     => array(),
);

if ( 'non_filtered' !== $featured_vehicles_filtered ) {
	$featured_tax_query          = $wp_query->tax_query;
	$featured_meta_query         = $wp_query->meta_query;
	$featured_args['tax_query']  = $featured_tax_query->queries;
	$featured_args['meta_query'] = $featured_meta_query->queries;

	if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$featured_args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
	}
}

/*
$current_date        = new DateTime();
$current_timestamp   = $current_date->getTimestamp();
*/

$featured_args['post_status'] = array(
	'publish'
);

$featured_args['meta_query']['relation'] = 'AND';
$featured_args['meta_query'][] = array(
	'key'     => 'featured',
	'value'   => '1',
	'compare' => '=',
);

/*
$featured_args['meta_query'][] = array(
	'key'     => 'cdfs_advertise_item_status',
	'value'   => $current_timestamp,
	'compare' => '>',
	'type'    => 'NUMERIC'
);
*/

$featured_args = apply_filters( 'cardealer/featured_vehicles/query_args', $featured_args );

// The Query
$featured_query = new WP_Query( $featured_args );

$featured_cars_count = $featured_query->post_count;
$featured_cars_found = $featured_query->found_posts;

global $cars_grid, $cardealer_is_featured_vehicles_section;

$getlayout      = cardealer_get_cars_list_layout_style();
$listing_layout = cardealer_get_vehicle_listing_page_layout();

// The Loop.
if ( $featured_query->have_posts() ) {
	$cardealer_is_featured_vehicles_section  = true;
	$list_style                              = cardealer_get_featured_vehicles_list_style();
	$featured_vehicles_listing_content_class = array(
		'featured-vehicles-listing-content',
		'featured-vehicles-list-style-' . $list_style,
		'vehicle-listing-wrapper',
		'vehicle-listing-' . $getlayout,
		'vehicle-listing-layout-' . $listing_layout,
	);
	?>
	<div class="featured-vehicles-listing-wrapper">
		<div class="featured-vehicles-listing-header">
			<h2 class="featured-vehicles-listing-section-title"><?php echo esc_html( $section_title ); ?></h2>
		</div>
		<div class="<?php cardealer_class_generator( $featured_vehicles_listing_content_class ); ?>">
			<?php
			if ( 'carousel' === $list_style ) {

				if ( 'lazyload' === $listing_layout ) {
					$responsive = array(
						0    => array(
							'items' => 1,
						),
						480  => array(
							'items' => 2,
						),
						768  => array(
							'items' => 3,
						),
						992  => array(
							'items' => 4,
						),
						1800 => array(
							'items' => 5,
						),
					);
				} else {
					$columns    = ( isset( $car_dealer_options['cars-col-sel'] ) && ! empty( $car_dealer_options['cars-col-sel'] ) ) ? $car_dealer_options['cars-col-sel'] : 3;
					$columns    = cardealer_get_grid_column();
					$responsive = array(
						0    => array(
							'items' => 1,
						),
						480  => array(
							'items' => 2,
						),
						768  => array(
							'items' => $columns,
						),
					);
				}
				$owl_options_args = array(
					'items'              => 3,
					'loop'               => false,
					'dots'               => false,
					'nav'                => true,
					'margin'             => 30,
					'autoplay'           => false,
					'autoplayHoverPause' => true,
					'smartSpeed'         => 1000,
					'responsive'         => $responsive,
					'navText'            => array(
						"<i class='fas fa-angle-left fa-2x'></i>",
						"<i class='fas fa-angle-right fa-2x'></i>",
					),
					'lazyLoad'           => cardealer_lazyload_enabled(),
				);

				$owl_options_args = apply_filters( 'cardealer/featured_vehicles/carousel/args', $owl_options_args );

				$owl_options = wp_json_encode( $owl_options_args );
				/*
				data-lazyload  =
				data-nav-arrow = $nav_arrow
				data-nav-dots  = false
				data-items     = $data_item
				data-md-items  = 3
				data-sm-items  = 2
				data-xs-items  = 2
				data-xx-items  = 2
				data-space     = 20
				*/
				?>
				<div class="owl-carousel owl-carousel-options owl-carousel-loader vehicle-listing vehicle-listing-featured" data-owl_options="<?php echo esc_attr( $owl_options ); ?>">
					<?php
					while ( $featured_query->have_posts() ) {
						$featured_query->the_post();
						get_template_part( 'template-parts/cars/content', 'cars' );
					}
					?>
				</div>
				<?php
			} else {
				?>
				<div class="all-cars-list-arch vehicle-listing vehicle-listing-featured clearfix">
					<?php
					while ( $featured_query->have_posts() ) {
						$featured_query->the_post();
						get_template_part( 'template-parts/cars/content', 'cars' );
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	$cardealer_is_featured_vehicles_section = false;
}

// Restore original Post Data.
wp_reset_postdata();
