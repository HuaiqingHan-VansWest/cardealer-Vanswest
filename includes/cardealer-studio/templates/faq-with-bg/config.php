<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Faq With Bg Image', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Faq', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Faq', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);