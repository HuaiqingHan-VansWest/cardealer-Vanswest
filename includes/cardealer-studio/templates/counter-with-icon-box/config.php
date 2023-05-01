<?php
defined( 'ABSPATH' ) || exit('restricted access');

return array(
    'title'            => esc_html__( 'Counter With Icon Box', 'cardealer' ), // Required
    'demo_url'         => '',
    'type'             => 'block',                                 // Required
    'category'         => array(                                   // Required
        esc_html__( 'Counter', 'cardealer' ),
    ),
    'tags'             => array(
        esc_html__( 'Counter', 'cardealer' ),
        esc_html__( 'feature', 'cardealer' ),
    ),
);