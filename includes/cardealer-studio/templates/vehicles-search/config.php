<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Vehicles Search', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Vehicles Search', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Vehicles Search', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);