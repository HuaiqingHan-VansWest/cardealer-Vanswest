<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
	'title'            => esc_html__( 'Demo Element', 'cardealer' ), // Required
	'demo_url'         => '',
	'type'             => 'block',                                 // Required
	'category'         => array(                                   // Required
		esc_html__( 'Demo', 'cardealer' ),
	),
	'tags'             => array(
		esc_html__( 'Test', 'cardealer' ),
		esc_html__( 'Demo', 'cardealer' ),
	),
);
