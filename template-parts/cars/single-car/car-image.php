<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$vehicle_single_image_size = ( isset( $car_dealer_options['vehicle-single-image-size'] ) && ! empty( $car_dealer_options['vehicle-single-image-size'] ) ) ? $car_dealer_options['vehicle-single-image-size'] : 'car_single_slider';

$layout  = cardear_get_vehicle_detail_page_layout();
$class   = ( '3' === $layout && ! wp_is_mobile() ) ? 'slider-for-full' : 'slider-for';
$post_id = get_the_ID();
?>
<div class="slider-slick">
	<?php
	if ( function_exists( 'get_field' ) ) {
		?>
		<div class="my-gallery cars-image-gallery">
			<?php do_action( 'before_image_gallery_slider', $post_id ); ?>
			<div class="slider <?php echo esc_attr( $class ); ?> detail-big-car-gallery">
				<?php
				$i      = 0;
				$images = get_field( 'car_images' );
				if ( ! empty( $images ) ) {
					foreach ( $images as $image ) {
						$image_url    = $image['url'];
						$image_width  = $image['width'];
						$image_height = $image['height'];
						$imag_alt     = ( '' !== $image['alt'] ) ? $image['alt'] : get_the_title();
						?>
						<figure>
							<img src="<?php echo esc_url( $image['sizes'][$vehicle_single_image_size] ); ?>" class="img-responsive ps-car-listing" id="pscar-<?php echo esc_attr( $i++ ); ?>" alt="<?php echo esc_attr( $imag_alt ); ?>"  data-src="<?php echo esc_url( $image_url ); ?>" data-width="<?php echo esc_attr( $image_width ); ?>" data-height="<?php echo esc_attr( $image_height ); ?>"/>
						</figure>
						<?php
					}
				} else {
					echo wp_kses_post( cardealer_get_cars_image( 'large' ) );
				}
				?>
			</div>
			<?php do_action( 'after_image_gallery_slider'); ?>
		</div>
		<?php
		if ( '3' !== $layout || wp_is_mobile() ) {
			?>
			<div class="slider slider-nav">
				<?php
				$images = get_field( 'car_images' );
				if ( $images ) {
					?>
					<?php
					foreach ( $images as $image ) {
						$imag_alt = ( '' !== $image['alt'] ) ? $image['alt'] : get_the_title();
						?>
						<img class="img-responsive" src="<?php echo esc_url( $image['sizes']['car_thumbnail'] ); ?>" alt="<?php echo esc_attr( $imag_alt ); ?>">
						<?php
					}
				}
				?>
			</div>
			<?php
		}
	}
	?>
</div>
