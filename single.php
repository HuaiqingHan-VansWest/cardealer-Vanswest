<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CarDealer
 */

get_header();

global $car_dealer_options, $cardealer_blog_sidebar, $cardealer_blog_layout;

$cardealer_blog_sidebar = isset( $car_dealer_options['blog_sidebar'] ) ? $car_dealer_options['blog_sidebar'] : '';
if ( ! isset( $cardealer_blog_sidebar ) || empty( $cardealer_blog_sidebar ) ) {
	$cardealer_blog_sidebar = 'right_sidebar';
}

$cardealer_blog_layout = 'classic';

$width        = 12;
$sidebar_stat = '';

if ( ( ( 'left_sidebar' === $cardealer_blog_sidebar ) || ( 'right_sidebar' === $cardealer_blog_sidebar ) ) ) {
	$width_lg      = $width - 3;
	$width_md      = $width - 3;
	$width_sm      = $width - 4;
	$sidebar_stat .= ' with-sidebar';
	$sidebar_stat .= " with-$cardealer_blog_sidebar";

	if ( 'timeline' === $cardealer_blog_layout ) {
		$section_class          .= ' timeline-sidebar';
		$cardealer_timeline_type = 'with_sidebar';
	}
} else {
	$width_lg = $width;
	$width_md = $width;
	$width_sm = $width;
}

while ( have_posts() ) :
	the_post();
	?>
	<div class="content-wrapper blog">
		<div class="container">
			<div class="row<?php echo esc_attr( $sidebar_stat ); ?>">

				<?php if ( ( 'left_sidebar' === $cardealer_blog_sidebar || 'two_sidebar' === $cardealer_blog_sidebar ) ) { ?>
					<div class="col-lg-3 col-md-3 col-sm-4 sidebar-left">
						<div role="complementary" class="widget-area" id="secondary">
							<?php get_sidebar( 'left' ); ?>
						</div>
					</div>
				<?php } ?>

				<div class="col-lg-<?php echo esc_attr( $width_lg ); ?> col-md-<?php echo esc_attr( $width_md ); ?> col-sm-<?php echo esc_attr( $width_sm ); ?>">

					<?php get_template_part( "template-parts/blog/$cardealer_blog_layout/content", get_post_format() ); ?>

					<?php
					// Check if post navigation is enabled or not.
					$post_nav = isset( $car_dealer_options['post_nav'] ) ? $car_dealer_options['post_nav'] : '';
					if ( $post_nav ) {
						?>
						<nav class="nav-single">
							<?php cardealer_single_nav(); ?>
						</nav><!-- .nav-single -->

						<?php
					}

					// Author Info.
					$author_details = isset( $car_dealer_options['author_details'] ) ? $car_dealer_options['author_details'] : '';

					// Check if user bio is enabled and if user has filled out their description, show a bio on their entries.
					if ( $author_details && get_the_author_meta( 'description' ) ) {
						get_template_part( 'template-parts/blog-extra/author-info' );
					}

					// Related Posts.
					$related_posts = isset( $car_dealer_options['related_posts'] ) ? $car_dealer_options['related_posts'] : '';
					if ( $related_posts ) {
						get_template_part( 'template-parts/blog-extra/related-posts' );
					}
					?>
					<!-- Comments -->
					<?php comments_template( '', true ); ?>
				</div>

				<?php if ( ( 'right_sidebar' === $cardealer_blog_sidebar || 'two_sidebar' === $cardealer_blog_sidebar ) ) { ?>
					<div class="col-lg-3 col-md-3 col-sm-4 sidebar-right">
						<div role="complementary" class="widget-area" id="secondary">
							<?php get_sidebar(); ?>
						</div>
					</div>
				<?php } ?>

			</div>
		</div>
	</div>

<?php endwhile; // end of the loop. ?>

<?php
get_footer();
