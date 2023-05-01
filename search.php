<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package CarDealer
 */

get_header();
?>

<div class="content-wrapper page-section-ptb">

	<div class="container">
		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12">

				<div id="primary" class="site-content">
					<div id="content" role="main">

						<?php if ( have_posts() ) : ?>

							<?php
							while ( have_posts() ) :
								the_post();
								?>
								<?php get_template_part( 'template-parts/search/content' ); ?>
							<?php endwhile; ?>

							<?php
							if ( function_exists( 'cardealer_wp_bs_pagination' ) ) {
								cardealer_wp_bs_pagination();
							}
							?>

						<?php else : ?>

							<article id="post-0" class="post no-results not-found">
								<header class="entry-header">
									<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'cardealer' ); ?></h1>
								</header>

								<div class="entry-content">
									<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'cardealer' ); ?></p>
									<div class="search-no-results-searchform error-search-box">
										<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
											<input type="search" class="search-field placeholder" placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'cardealer' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'cardealer' ); ?>" />
											<input type="submit" class="search-submit button" value="<?php echo esc_attr_x( 'Search', 'submit button', 'cardealer' ); ?>" />
										</form>
									</div>
								</div><!-- .entry-content -->
							</article><!-- #post-0 -->

						<?php endif; ?>

					</div><!-- #content -->
				</div>

			</div>

		</div>
	</div>

</div><!-- #primary -->

<?php
get_footer();
