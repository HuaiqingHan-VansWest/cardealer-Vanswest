<?php
/**
 * Blog
 *
 * @package Cardealer
 */

return array(
	'name'              => esc_html__( 'Blog 01', 'cardealer' ),
	'template_category' => esc_html__( 'Blog', 'cardealer' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => '<<<CONTENT
[vc_section full_width="stretch_row"][vc_row css=".vc_custom_1495519436552{padding-right: 15px !important;padding-left: 15px !important;}"][vc_column][cd_space desktop="80" tablet="70" portrait="60" mobile="50" mobile_portrait="40"][cd_section_title heading_tag="h1" section_number_tag="h1" title_align="text-center" section_title="Latest News" section_sub_title="Read our latest news"][/cd_section_title][cd_recent_posts style="list_view" no_of_posts="1"][cd_space desktop="80" tablet="70" portrait="60" mobile="50" mobile_portrait="40"][/vc_column][/vc_row][vc_row full_width="stretch_row_content" el_class="car-objects-bg"][vc_column width="1/2" css=".vc_custom_1495169446281{padding-right: 0px !important;padding-left: 0px !important;}" offset="vc_hidden-sm vc_hidden-xs"][vc_single_image image="7600" img_size="full"][/vc_column][vc_column width="1/2" css=".vc_custom_1495169454028{padding-right: 0px !important;padding-left: 0px !important;}" offset="vc_hidden-sm vc_hidden-xs"][vc_single_image image="7600" img_size="full" alignment="right"][/vc_column][/vc_row][/vc_section]
CONTENT',
);
