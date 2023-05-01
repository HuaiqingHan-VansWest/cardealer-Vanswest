<?php
/**
 * Custom Filters 02
 *
 * @package Cardealer
 */

return array(
	'name'              => esc_html__( 'Custom Filters 02', 'cardealer' ),
	'template_category' => esc_html__( 'Custom Filters', 'cardealer' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => '<<<CONTENT
<p>[vc_row][vc_column][cars_custom_filters cars_filters="car_year,car_make,car_model,car_body_style,car_mileage,car_condition" filter_style="box" filter_position="default" filter_background="red-bg" filters_type="wide" filters_background="white-bg" filters_position="default" custom_filters_style="car_filter_style_1"][/vc_column][/vc_row]</p>







CONTENT',
);
