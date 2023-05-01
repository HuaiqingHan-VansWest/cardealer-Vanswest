<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Recent Vehicle Grid', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Vehicle', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Vehicle', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);