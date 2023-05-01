<?php
global $post;

if ( wp_is_mobile() ) {
	$template_id = CDHL_CPT_Template::get_template_id( 'vehicle_detail_mobile' );
} else {
	$template_id = CDHL_CPT_Template::get_template_id( 'vehicle_detail' );
}
$template_built_with  = CDHL_CPT_Template::template_built_with( $template_id );

get_template_part( 'template-parts/cars/single-car/layout/builders/' . $template_built_with, '', array(
	'template_id' => $template_id,
) );
