<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$layout = cardear_get_vehicle_detail_page_layout();

get_template_part( 'template-parts/cars/single-car/layout/layout-' . $layout );
