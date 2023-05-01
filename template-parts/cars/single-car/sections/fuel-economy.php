<?php
$sidebar_position = cardealer_get_cars_details_page_sidebar_position();
if ( 'no' === $sidebar_position ) {
	cardealer_get_widget_fuel_efficiency();
}
