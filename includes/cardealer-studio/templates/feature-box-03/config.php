<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Feature Box 03', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Feature Box', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Feature Box', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);