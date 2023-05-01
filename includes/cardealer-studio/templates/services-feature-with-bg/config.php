<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Services Feature With Bg', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Services', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Services', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);