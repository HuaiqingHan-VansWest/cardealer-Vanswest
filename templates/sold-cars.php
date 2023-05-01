<?php
/**
 * Template Name: Sold Vehicles
 * Description: A page template that display only sold cars.
 *
 * @package CarDealer
 * @author  Potenza Global Solutions
 */

get_header();

global $sold_vehicle_pg;

$sold_vehicle_pg = true;
?>

<section <?php post_class( 'product-listing page-section-ptb default sold-car' ); ?>>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<form class="sold-filter-frm" method="get" action="">
					<div class="cars-top-filters-box">
						<div class="cars-top-filters-box-left">
							<?php cardealer_get_sold_cars_price_filters(); ?>
							<div class="price">
								<button id="pgs_price_filter_btn-sold" class="button"><?php esc_html_e( 'Filter', 'cardealer' ); ?></button>
							</div>
						</div>
						<div class="cars-top-filters-box-right">
							<?php
							cardealer_cars_sold_ordering();
							cardealer_get_sold_view();
							?>
						</div>
						<div class="clearfix"></div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<?php add_filter( 'cardealer_list_layout_style', 'cardealer_sold_list_layout_style' ); ?>
			<div class="content col-lg-12 col-md-12 col-sm-12">
				<?php
				$cars_grid = cardealer_get_cars_catlog_style();
				if ( 'yes' === $cars_grid ) {
					?>
					<div class="row vehicle-listing">
					<?php
				}
					$args  = get_sold_query();
					$query = new WP_Query( $args );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) :
						$query->the_post();
						get_template_part( 'template-parts/cars/content', 'sold' );
					endwhile;
					wp_reset_postdata();
				} else {
					?>
					<div class="col-sm-12"><div class="alert alert-warning"><?php echo esc_html__( 'No result were found matching your selection.', 'cardealer' ); ?></div></div>
					<?php
				}
				if ( 'yes' === $cars_grid ) {
					?>
					</div>
					<?php
				}
				if ( $query->have_posts() ) {
					?>
					<div id="cars-sold-pagination-nav" class="pagination-nav text-center">
						<?php cardealer_cars_pagination( true, $query ); ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
<!--.product-listing-->

<?php
get_footer();
