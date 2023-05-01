<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Video 01', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Video', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Video', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);