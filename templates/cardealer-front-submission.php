<?php
/**
 * Template Name: CarDealer Front Submission
 * Description: A page template that display CarDealer Front Submission Plugin pages.
 *
 * @package CarDealer
 * @author  Potenza Global Solutions
 */

get_header(); ?>

<section class="content-wrapper-vc-enabled">

	<div class="container">
		<div class="row without-sidebar">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div id="primary" class="site-content">

					<div id="content" role="main">

						<?php
						while ( have_posts() ) :
							the_post();
							?>
							<div class="entry-content">
							<?php the_content(); ?>
							<?php
							wp_link_pages(
								array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cardealer' ),
									'after'  => '</div>',
								)
							);
							?>
							</div><!-- .entry-content -->
							<?php
						endwhile; // end of the loop.
						?>

					</div>

				</div>
			</div>
		</div>
	</div>

</section>

<?php
get_footer();
