<?php
/**
 * Demo Element
 *
 * @package Cardealer
 */

return array(
	'name'              => esc_html__( 'Demo Element', 'cardealer' ),
	'template_category' => esc_html__( 'Demo Element', 'cardealer' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => '<<<CONTENT
Content goes here...
CONTENT',
	'config' => array(
		'images' => array(
			'image_1' => 'https://www.example.com/image_1.jpg',
			'image_2' => 'https://www.example.com/image_2.jpg',
		),
	),
);
