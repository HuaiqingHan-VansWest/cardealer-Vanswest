<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $cardealer_blog_layout;

$layout_sr = 0;
// Start the loop.
while ( have_posts() ) {
	the_post();

	$layout_sr++;
	get_template_part( "template-parts/blog/$cardealer_blog_layout/content", get_post_format() );
}
// End the loop.
