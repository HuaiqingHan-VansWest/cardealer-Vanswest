<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */
global $car_dealer_options;
$cardealer_post_id = get_the_ID();
$is_hover_overlay  = cardealer_is_hover_overlay();
do_action( 'cardealer_car_loop_link_open', $cardealer_post_id, $is_hover_overlay );

/**
 * Hook car_before_overlay_banner.
 *
 * @hooked cardealer_get_cars_condition - 10
 * @hooked cardealer_get_cars_status - 20
 */
do_action( 'car_before_overlay_banner', $cardealer_post_id, true );

if ( ! wp_is_mobile() ) {
	$img_size_key = 'vehicle-catalog-image-size';
	$catalog_size = ( isset( $car_dealer_options[ $img_size_key ] ) && ! empty( $car_dealer_options[ $img_size_key ] ) ) ? $car_dealer_options[ $img_size_key ] : 'car_catalog_image';
} else {
	$img_size_key = 'vehicle_catalog_image_size_mobile';
	$catalog_size = ( isset( $car_dealer_options[ $img_size_key ] ) && ! empty( $car_dealer_options[ $img_size_key ] ) ) ? $car_dealer_options[ $img_size_key ] : 'car_tabs_image';
}

echo wp_kses_post( cardealer_get_cars_image( $catalog_size, $cardealer_post_id ) );

$getlayout  = cardealer_get_cars_list_layout_style();
$list_style = cardealer_get_inv_list_style();

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
			if ( 'classic' === $list_style ) {
				if ( 'view-list' === $getlayout ) {
					do_action( 'vehicle_classic_list_overlay_gallery', $cardealer_post_id );
				} else {
					do_action( 'vehicle_classic_grid_overlay', $cardealer_post_id );
				}
			} else {
				do_action( 'car_overlay_banner', $cardealer_post_id );
			}
			?>
		</ul>
	</div>
	<?php
}

do_action( 'cardealer_car_loop_link_close', $cardealer_post_id, $is_hover_overlay );
