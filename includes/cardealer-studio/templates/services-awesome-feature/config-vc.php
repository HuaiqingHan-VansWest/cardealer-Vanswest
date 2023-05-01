<?php
/**
 *Services Awesome Feature
 *
 * @package Cardealer
 */

return array(
	'name'              => esc_html__( 'Services Awesome Feature', 'cardealer' ),
	'template_category' => esc_html__( 'Services', 'cardealer' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'config'            => array(
		'images' => array(
			'image_1' => 'http://sample-data.potenzaglobal.com/cardealer/elementor-demo/placeholder_520x410.jpg',
		),
	),
	'content'           => '<<<CONTENT
[vc_row full_width="stretch_row" css=".vc_custom_1526271830772{padding-top: 80px !important;padding-bottom: 80px !important;}"][vc_column][vc_row_inner][vc_column_inner][cd_section_title heading_tag="h1" title_align="text-center" hide_seperator="true" style="style_1" section_title="AWESOME SERVICES" section_sub_title="What we do?"][/cd_section_title][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3" css=".vc_custom_1526880560852{margin-bottom: 30px !important;}" offset="vc_col-lg-4 vc_col-md-4 vc_col-xs-12"][cd_feature_box icon_type="flaticon" icon_flaticon="glyph-icon flaticon-travel" back_image="true" style="style-10" back_image_url="{{image_1}}" title="BATTERIES"][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1526880570393{margin-bottom: 30px !important;}" offset="vc_col-lg-4 vc_col-md-4 vc_col-xs-12"][cd_feature_box icon_type="flaticon" back_image="true" style="style-10" back_image_url="{{image_1}}" title="AIR CONDITIONING"][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1526880584290{margin-bottom: 30px !important;}" offset="vc_col-lg-4 vc_col-md-4 vc_col-xs-12"][cd_feature_box icon_type="flaticon" icon_flaticon="glyph-icon flaticon-steering-wheel" back_image="true" style="style-10" back_image_url="{{image_1}}" title="STEERING"][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3" css=".vc_custom_1526880617567{margin-bottom: 30px !important;}" offset="vc_col-lg-4 vc_col-md-4 vc_col-xs-12"][cd_feature_box icon_type="flaticon" icon_flaticon="glyph-icon flaticon-alloy-wheel" back_image="true" style="style-10" back_image_url="{{image_1}}" title="ENGINE MANAGEMENT"][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1526880627962{margin-bottom: 30px !important;}" offset="vc_col-lg-4 vc_col-md-4 vc_col-xs-12"][cd_feature_box icon_type="flaticon" icon_flaticon="glyph-icon flaticon-wheel-alignment" back_image="true" style="style-10" back_image_url="{{image_1}}" title="SUSPENSION"][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1526880640837{margin-bottom: 30px !important;}" offset="vc_col-lg-4 vc_col-md-4 vc_col-xs-12"][cd_feature_box icon_type="flaticon" icon_flaticon="glyph-icon flaticon-alloy-wheel" back_image="true" style="style-10" back_image_url="{{image_1}}" title="WEELS"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]
CONTENT',
);
