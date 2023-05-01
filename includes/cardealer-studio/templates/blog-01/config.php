<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Blog 01', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Blog', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Blog', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);