<?php
/**
 * The template for displaying single car posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CarDealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>
<section class="<?php cardealer_vehicle_detail_section_class(); ?>">
	<div class="<?php cardealer_vehicle_detail_section_container_class(); ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/cars/content', 'single-cars' );
		endwhile; // end of the loop.
		?>
	</div>
</section>
<?php
get_footer();
