<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'About with Feature Box', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'About Us', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'about', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);