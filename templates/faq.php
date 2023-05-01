<?php
/**
 * Template Name: FAQ
 * Description: A page template that display FAQ items.
 *
 * @package CarDealer
 * @author  Potenza Global Solutions
 */

get_header(); ?>
<?php
global $car_dealer_options, $cardealer_page_sidebar, $cardealer_faq_layout;

if ( ! isset( $car_dealer_options['page_sidebar'] ) || empty( $car_dealer_options['page_sidebar'] ) ) {
	$cardealer_page_sidebar = 'right_sidebar';
} else {
	$cardealer_page_sidebar = $car_dealer_options['page_sidebar'];
}

$page_layout_custom = get_post_meta( get_the_ID(), 'page_layout_custom', true );
if ( $page_layout_custom ) {
	$page_sidebar = get_post_meta( get_the_ID(), 'page_sidebar', true );
	if ( $page_sidebar ) {
		$cardealer_page_sidebar = $page_sidebar;
	}
}

$width = 12;

$sidebar_stat = '';

if ( 'left_sidebar' === $cardealer_page_sidebar || 'right_sidebar' === $cardealer_page_sidebar ) {
	$width_lg      = $width - 3;
	$width_md      = $width - 3;
	$width_sm      = $width - 4;
	$sidebar_stat .= ' with-sidebar';
	$sidebar_stat .= " with-$cardealer_page_sidebar";
} elseif ( 'two_sidebar' === $cardealer_page_sidebar ) {

	$width_lg = 6;
	$width_md = 6;
	$width_sm = 6;

	$sidebar_stat .= ' with-sidebar';
	$sidebar_stat .= " with-$cardealer_page_sidebar";
} else {

	$width_lg = $width;
	$width_md = $width;
	$width_sm = $width;

	$sidebar_stat .= 'without-sidebar';
}
?>

<section class="content-wrapper-vc-enabled">

	<div class="container">
		<div class="row <?php echo esc_attr( $sidebar_stat ); ?>">

			<?php if ( ( 'left_sidebar' === $cardealer_page_sidebar || 'two_sidebar' === $cardealer_page_sidebar ) ) { ?>
				<div class="col-lg-3 col-md-3 col-sm-4 sidebar-left">
					<div role="complementary" class="widget-area" id="secondary">
				<?php get_sidebar( 'left' ); ?>
					</div>
				</div>
			<?php } ?>

			<div class="col-lg-<?php echo esc_attr( $width_lg ); ?> col-md-<?php echo esc_attr( $width_md ); ?> col-sm-<?php echo esc_attr( $width_sm ); ?>">
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

							<div class="faqs-wrapper faq-layout-default">
								<?php get_template_part( 'template-parts/faq/default' ); ?>
							</div>

						<?php endwhile; // end of the loop. ?>

					</div>

				</div>
			</div>

			<?php if ( ( 'right_sidebar' === $cardealer_page_sidebar || 'two_sidebar' === $cardealer_page_sidebar ) ) { ?>
				<div class="col-lg-3 col-md-3 col-sm-4 sidebar-right">
					<div role="complementary" class="widget-area" id="secondary">
				<?php get_sidebar(); ?>
					</div>
				</div>
			<?php } ?>

		</div>
	</div>

</section>

<?php
get_footer();
