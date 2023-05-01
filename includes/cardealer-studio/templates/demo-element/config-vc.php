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
);
