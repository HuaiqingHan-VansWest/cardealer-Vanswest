<?php
/**
 * Template Name: Page Without Header and Footer
 * Description: A page template that display pages without header and footer sections.
 *
 * @package CarDealer
 * @author  Potenza Global Solutions
 */

get_header( 'custom' ); ?>
<div class="site-content container" id="primary">
	<div role="main" id="content">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<?php get_template_part( 'template-parts/content', 'page' ); ?>

		<?php endwhile; // end of the loop. ?>

	</div>
</div>
<?php
get_footer( 'custom' );
