<?php
/**
 *Recent Vehicle Grid
 *
 * @package Cardealer
 */

return array(
	'name'              => esc_html__( 'Recent Vehicle Grid', 'cardealer' ),
	'template_category' => esc_html__( 'Vehicle', 'cardealer' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => '<<<CONTENT
[vc_row full_width="stretch_row" css=".vc_custom_1630131764934{background-color: #f6f6f6 !important;}"][vc_column][cd_space desktop="80" tablet="60" portrait="60" mobile="40" mobile_portrait="40"][vc_row_inner][vc_column_inner][cd_section_title heading_tag="h3" title_align="text-center" style="style_1" section_title="STYLE 01 WITH GRID"][/cd_section_title][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner][pgs_cars_carousel silder_type="without_silder" carousel_type="pgs_new_arrivals" image_size_text="car_tabs_image" number_of_column="4" number_of_item="8" categories="audi,bentley,bmw,bugatti,cadillac,chevrolet,ferrari,ford" item_background="white-bg" hide_sold_vehicles="" carousel_layout="carousel_1"][/vc_column_inner][/vc_row_inner][cd_space desktop="80" tablet="60" portrait="60" mobile="40" mobile_portrait="40"][/vc_column][/vc_row]




















CONTENT',
);
