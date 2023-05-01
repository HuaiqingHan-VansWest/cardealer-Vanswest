<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$cars_grid = cardealer_get_cars_catlog_style();
/**
 * Filter cars list style and allow third parties to set their own.
 *
 * @hooked cardealer_set_vehicle_list_view_type - 10
 */
$cars_grid = apply_filters( 'cardealer_vehicle_list_view_type', $cars_grid );

if ( 'yes' === $cars_grid ) {
	// Grid view layout.
	get_template_part( 'template-parts/cars/layout/grid-view' );
} else {
	get_template_part( 'template-parts/cars/layout/list-view' );
}
