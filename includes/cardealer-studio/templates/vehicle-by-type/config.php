<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Vehicle By Type', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Vehicle By Type', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Vehicle By Type', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);