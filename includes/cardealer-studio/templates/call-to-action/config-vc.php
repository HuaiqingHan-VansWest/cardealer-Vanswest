<?php
/**
 * Call To Action
 *
 * @package Cardealer
 */

return array(
	'name'              => esc_html__( 'Call To Action', 'cardealer' ),
	'template_category' => esc_html__( 'Call To Action', 'cardealer' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'config'            => array(
	'images' => array(
			'image_1' => 'http://sample-data.potenzaglobal.com/cardealer/elementor-demo/placeholder_430x450.jpg',
		),
	),
	'content'           => '<<<CONTENT
[vc_row][vc_column][cd_space desktop="80" tablet="70" portrait="60" mobile="50" mobile_portrait="40"][cd_section_title heading_tag="h2" title_align="text-center" section_title="Default Action Box" style="style_1"][/cd_section_title][vc_row_inner][vc_column_inner width="1/2" css=".vc_custom_1549613267123{margin-bottom: 30px !important;}" offset="vc_col-lg-3 vc_col-md-3"][cd_call_to_action icon_type="flaticon" icon_flaticon="glyph-icon flaticon-beetle" style="Default-Layout" title="NEW VEHICLES" description="Get yourself nice and relaxed and settled. Concentrate on your breathing, engage in the" box_bg_image="{{image_1}}" readmore="url:https%3A%2F%2Fcardealer.potenzaglobalsolutions.com%2Fcars%2F%3Fcars_pp%3D12%26cars_order%3Dasc%26cars_grid%3Dyes%26lay_style%3Dview-grid-masonry-left%26layout-style%3Ddefault|||"][/vc_column_inner][vc_column_inner width="1/2" css=".vc_custom_1549613278814{margin-bottom: 30px !important;}" offset="vc_col-lg-3 vc_col-md-3"][cd_call_to_action icon_type="flaticon" icon_flaticon="glyph-icon flaticon-car-repair" style="Default-Layout" title="VEHICLES SERVICE" description="About something that you know you need to be doing, but are not. Then with that thing" box_bg_image="{{image_1}}" readmore="url:https%3A%2F%2Fcardealer.potenzaglobalsolutions.com%2Fservice-01%2F|||"][/vc_column_inner][vc_column_inner width="1/2" css=".vc_custom_1549613287869{margin-bottom: 30px !important;}" offset="vc_col-lg-3 vc_col-md-3"][cd_call_to_action icon_type="flaticon" icon_flaticon="glyph-icon flaticon-reparation" style="Default-Layout" title="VEHICLES PARTS" description="You will begin to realise why this exercise is called the Dickens Pattern reference" box_bg_image="{{image_1}}" readmore="url:https%3A%2F%2Fcardealer.potenzaglobalsolutions.com%2Fservice-02%2F|||"][/vc_column_inner][vc_column_inner width="1/2" css=".vc_custom_1549613297070{margin-bottom: 30px !important;}" offset="vc_col-lg-3 vc_col-md-3"][cd_call_to_action icon_type="flaticon" icon_flaticon="glyph-icon flaticon-car" style="Default-Layout" title="OLD VEHICLES" description="Scrooge some different futures as you notice that the idea of this exercise is hypnotize" box_bg_image="{{image_1}}" readmore="url:https%3A%2F%2Fcardealer.potenzaglobalsolutions.com%2Fcars%2F%3Fcars_pp%3D12%26cars_order%3Dasc%26cars_grid%3Dno%26lay_style%3Dview-list-full%26layout-style%3Ddefault|||"][/vc_column_inner][/vc_row_inner][cd_space desktop="50" tablet="40" portrait="30" mobile="20" mobile_portrait="10"][/vc_column][/vc_row]
CONTENT',
);
