<?php
/**
 * Template Name: Promo Code
 * Description: A Page Template that display promo code.
 *
 * @package CarDealer
 */

get_header();
?>
	<div class="site-content container" id="primary">
		<div role="main" id="content">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/promocode/content', 'promocode' );
		endwhile; // end of the loop.
		?>
		</div>
	</div>
<?php
get_footer();
