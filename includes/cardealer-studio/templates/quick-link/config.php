<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Quick Link', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Quick Link', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Quick Link', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);