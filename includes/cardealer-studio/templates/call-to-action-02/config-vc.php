<?php
/**
 * Call To Action 02
 *
 * @package Cardealer
 */

return array(
	'name'              => esc_html__( 'Call To Action 02', 'cardealer' ),
	'template_category' => esc_html__( 'Call To Action', 'cardealer' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'config'            => array(
	'images' => array(
			'image_1' => 'http://sample-data.potenzaglobal.com/cardealer/elementor-demo/placeholder_430x450.jpg',
		),
	),
	'content'           => '<<<CONTENT
<p>[vc_row full_width="stretch_row" cd_bg_type="row-background-dark" css=".vc_custom_1625577224194{padding-top: 80px !important;padding-bottom: 80px !important;background: #db2d2e url(https://sampledata.potenzaglobalsolutions.com/cardealer/wp-content/uploads/2021/07/placeholder_1920x493.png?id=10878) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][vc_custom_heading text="Schedule Your Appointment" font_container="tag:h2|font_size:35px|text_align:center|line_height:40px" use_theme_fonts="yes" css=".vc_custom_1526282764448{margin-bottom: 20px !important;}"][vc_custom_heading text="Today Call: 1-800-123-4567" font_container="tag:h2|font_size:35px|text_align:center|line_height:40px" use_theme_fonts="yes" css=".vc_custom_1526282771505{margin-bottom: 20px !important;}"][vc_column_text css=".vc_custom_1526282837630{margin-bottom: 20px !important;}"]</p>
<p class="text-white" style="text-align: center;">Your Automotive Repair &amp; Maintenance Service Specialist</p>
<p>[/vc_column_text][vc_row_inner][vc_column_inner el_class="text-center"][cd_button size="medium" hover_back_style="dark-color" title="Make And Appointment" style="border" css=".vc_custom_1529310489305{margin-top: 20px !important;}"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]</p>

CONTENT',
);
