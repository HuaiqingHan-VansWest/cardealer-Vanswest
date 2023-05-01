<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Contact Info 02', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Contact Info', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Contact Info', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);