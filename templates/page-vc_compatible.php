<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template Name: Visual Composer Compatible
 * Description: A Page Template that display visual composer elements.
 *
 * @package CarDealer
 */

get_header(); ?>

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
get_footer();
