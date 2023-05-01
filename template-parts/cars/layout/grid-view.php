<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

?>
<div <?php cardealer_grid_view_class(); ?>>
	<?php
	$list_style = cardealer_get_inv_list_style();
	if ( 'classic' === $list_style ) {
		?>
		<div class="car-item gray-bg text-center style-classic <?php echo esc_attr( cardealer_cars_loop() ); ?>">
			<div class="car-image">
				<?php
				$grid_view_post_id = get_the_ID();
				get_template_part( 'template-parts/cars/layout/img-overlay' );
				?>
			</div>
			<div class="car-content">
				<?php
				/**
				 * Hook cardealer_classic_list_car_title.
				 *
				 * @hooked cardealer_list_car_link_title - 10
				 */

				do_action( 'cardealer_classic_list_car_title' );
				cardealer_car_price_html( $grid_view_post_id );
				cardealer_get_cars_list_attribute( $grid_view_post_id );
				cardealer_get_vehicle_review_stamps( $grid_view_post_id );
				?>
				<ul class="car-bottom-actions classic-grid">
					<?php
					cardealer_classic_view_cars_overlay_link( $grid_view_post_id );
					cardealer_classic_vehicle_video_link( $grid_view_post_id );
					?>
				</ul>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="car-item gray-bg text-center <?php echo esc_attr( cardealer_cars_loop() ); ?>">
			<div class="car-image">
				<?php
				$grid_view_post_id = get_the_ID();
				get_template_part( 'template-parts/cars/layout/img-overlay' );
				cardealer_get_cars_list_attribute( $grid_view_post_id );
				?>
			</div>
			<div class="car-content">
				<?php
				/**
				 * Hook cardealer_list_car_title.
				 *
				 * @hooked cardealer_list_car_link_title - 5
				 * @hooked cardealer_list_car_title_separator - 10
				 */

				do_action( 'cardealer_list_car_title' );
				cardealer_car_price_html( $grid_view_post_id );
				cardealer_get_vehicle_review_stamps( $grid_view_post_id );
				?>
			</div>
		</div>
		<?php
	}
	?>
</div>
