<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$listing_sidebar = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
?>
<div class="row">
	<?php
	if ( $args['inv_page_id'] ) {
		?>
		<div class="col-sm-12">
			<?php get_template_part( 'template-parts/cars/archive-sections/page-content', null, $args ); ?>
		</div>
		<?php
	}
	?>
	<div class="col-sm-12 <?php echo esc_attr( $args['inv_page_content_class'] ); ?>">
		<?php
		$cars_term = get_queried_object();
		if ( is_tax() && $cars_term && ! empty( $cars_term->description ) ) {
			?>
			<div class="term-description"><?php echo do_shortcode( $cars_term->description ); ?></div>
			<?php
		}
		?>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="listing-skeleton" style="margin-top: 15px;border:1px solid #eeeaea;">
			<img alt="<?php esc_attr_e( 'Listing Skeleton', 'cardealer' ); ?>" title="<?php esc_attr_e( 'Listing Skeleton', 'cardealer' ); ?>" src="<?php echo esc_url( CARDEALER_URL . '/images/listing-skeleton.jpg' ); ?>" style="width: 100%;">
		</div>
	</div>
</div>
