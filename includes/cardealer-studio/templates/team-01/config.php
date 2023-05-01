<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Team 01', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
		esc_html_x( 'Team', 'cardealre-studio', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html_x( 'Team', 'cardealre-studio', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);
