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
		$add_row = false;
		if ( class_exists( 'CDHL_CPT_Template' ) ) {
			$template_built_with = CDHL_CPT_Template::template_built_with( get_the_ID() );
			if ( 'elementor' === $template_built_with ) {
				$add_row = true;
			}
		}

		if ( $add_row ) {
			echo '<div class="row">';
		}

		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile; // end of the loop.
		
		if ( $add_row ) {
			echo '</div>';
		}
		?>
	</div>
</section>
<?php
get_footer();
