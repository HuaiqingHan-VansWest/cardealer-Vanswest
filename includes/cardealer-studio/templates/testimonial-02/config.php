<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Testimonial 02', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Testimonial', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Testimonial', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);